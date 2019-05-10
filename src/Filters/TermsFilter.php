<?php

namespace TheWebmen\FacetFilters\Filters;

use SilverStripe\Control\Controller;
use TheWebmen\FacetFilters\Forms\TermsFilterField;

/**
 * @property string Collapsed
 */
class TermsFilter extends Filter
{
    private static $table_name = 'TheWebmen_FacetFilters_Filters_TermsFilter';

    private static $db = [
        'Collapsed' => 'Boolean'
    ];

    protected $options = [];

    protected $labels = [];

    public function getElasticaQuery()
    {
        $query = false;
        $values = Controller::curr()->getRequest()->getVar($this->ID);
        $values = is_array($values) ? $values : [];

        $this->extend('updateValues', $values);

        if ($values) {
            $query = new \Elastica\Query\Terms($this->FieldName, $values);
        }

        return $query;
    }

    public function getFormField()
    {
        return new TermsFilterField($this->ID, $this->Name, $this->getOptions(), $this->Collapsed);
    }

    public function addOption($option)
    {
        if (array_key_exists($option['key'], $this->getLabels())) {
            $label = $this->generateLabel($this->labels[$option['key']], $option['doc_count']);
        } else {
            $label = $this->generateLabel($option['key'], $option['doc_count']);
        }

        $this->options[$option['key']] = $label;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function generateLabel($label, $count)
    {
        return "{$label}<span>{$count}</span>";
    }

    public function getLabels()
    {
        if (!$this->labels) {
            $this->extend('updateLabels', $this->labels);
        }

        return $this->labels;
    }

    public function getTitle()
    {
        return 'Terms';
    }

    public function createBucket()
    {
        return true;
    }
}
