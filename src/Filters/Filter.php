<?php

namespace TheWebmen\FacetFilters\Filters;

use SilverStripe\ORM\DataObject;

class Filter extends DataObject {
    private static $table_name = 'TheWebmen_FacetFilters_Filters_Filter';
    
    private static $db = [
        'Name' => 'Varchar',
        'FieldName' => 'Varchar',
        'Placeholder' => 'Varchar',
        'Sort' => 'Int'
    ];

    private static $has_one = [
        'Page' => \Page::class
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

        $fields->removeByName('Sort');
        $fields->removeByName('PageID');

        return $fields;
    }

    public function canCreate($member = null, $context = [])
    {
        if ($this->class != __CLASS__) {
            return true;
        }

        return false;
    }

    public function getFormField()
    {
        return false;
    }

    public function createBucket()
    {
        return false;
    }

}
