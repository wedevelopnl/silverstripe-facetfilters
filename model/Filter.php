<?php
class Filter extends DataObject {

    private static $db = [
        'Name' => 'Varchar',
        'FieldName' => 'Varchar',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        'Page' => 'Page'
    ];

    private static $summary_fields = [
        'ClassName',
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
        return false;
    }

    public function getFormFields()
    {
        return [];
    }

    public function canCreate($member = null)
    {
        if ($this->class != __CLASS__) {
            return true;
        }

        return false;
    }

}
