<?php

namespace TheWebmen\FacetFilters\Sort;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\ORM\DataObject;
use TheWebmen\FacetFilters\Filters\SortFilter;

class Item extends DataObject
{
    private static $table_name = 'TheWebmen_FacetFilters_Filters_SortFilter_Item';

    private static $db = [
        'FieldName' => 'Varchar',
        'Label' => 'Varchar',
        'Sort' => 'Int',
    ];

    private static $has_one = [
        'Page' => SiteTree::class,
    ];

    private static $default_sort = '"Sort" ASC';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('PageID');
        $fields->removeByName('Sort');

        return $fields;
    }
}
