<?php

namespace TheWebmen\FacetFilters\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataExtension;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use TheWebmen\FacetFilters\Filters\Filter;

class FilterPageExtension extends DataExtension {

    private static $db = [
        'ShowSearchButton' => 'Boolean'
    ];

    private static $has_many = [
        'Filters' => Filter::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.Filters', new CheckboxField('ShowSearchButton', 'ShowSearchButton'));

        $filtersGridFieldConfig = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('Sort'))
            ->removeComponentsByType('GridFieldAddNewButton')
            ->addComponent(new GridFieldAddNewMultiClass());
        $filtersGridField = new GridField('Filters', 'Filters', $this->owner->Filters(), $filtersGridFieldConfig);
        $fields->addFieldToTab('Root.Filters', $filtersGridField);
    }

}
