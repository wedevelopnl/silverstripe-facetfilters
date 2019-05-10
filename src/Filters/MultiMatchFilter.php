<?php

namespace TheWebmen\FacetFilters\Filters;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\TextField;

class MultiMatchFilter extends Filter
{
    protected $options = [];

    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);

        if ($value) {
            $query = new \Elastica\Query\MultiMatch();
            $query->setQuery($value);
            $query->setFields(['Title', 'Content']);
        }

        return $query;
    }

    public function getFormField()
    {
        $field = new TextField($this->ID, $this->Name);
        $field->setAttribute('placeholder', $this->Placeholder);

        return $field;
    }

    public function getTitle()
    {
        return 'MultiMatch';
    }
}
