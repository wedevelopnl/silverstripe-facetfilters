<?php

namespace TheWebmen\FacetFilters\Filters;

use SilverStripe\Control\Controller;
use TheWebmen\FacetFilters\Forms\RangeFilterField;

class RangeFilter extends Filter
{
    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);
        $value = is_array($value) ? $value : [];

        if (isset($value['From']) && isset($value['To']) && is_numeric($value['From']) && is_numeric($value['To'])) {
            $query = new \Elastica\Query\Range($this->FieldName, [
                'gte' => $value['From'],
                'lte' => $value['To'],
            ]);
        }

        return $query;
    }

    public function getFormField()
    {
        return new RangeFilterField($this->ID, $this->Name);
    }

    public function getTitle()
    {
        return 'Range';
    }
}
