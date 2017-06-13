<?php
class Filter extends DataObject {

    protected $options = [];

    private static $db = [
        'Name' => 'Varchar',
        'FieldName' => 'Varchar',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        'Page' => 'Page'
    ];

    private static $summary_fields = [
        'Name',
        'FieldName'
    ];

    private static $default_sort = '"Sort" ASC';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('PageID');

        return $fields;
    }

    public function getElasticaQuery()
    {
        $query = false;
        $values = Controller::curr()->getRequest()->getVar($this->FieldName);
        $values = is_array($values) ? $values : array();

        $this->extend('updateValues', $values);

        if ($values) {
            $query = new Elastica\Query\Terms($this->FieldName, $values);
        }

        return $query;
    }

    public function getFormFields()
    {
        return [
            new CheckboxSetField($this->FieldName, $this->Name, $this->getOptions())
        ];
    }

    public function addOption($key, $value) {
        $this->options[$key] = $value;
    }

    protected function getOptions() {
        return $this->options;
    }

}
