<?php

namespace TheWebmen\FacetFilters\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\HasManyList;
use Symbiote\GridFieldExtensions\GridFieldAddNewMultiClass;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use TheWebmen\FacetFilters\Filters\Filter;

/**
 * @property FilterPageExtension owner
 * @property string ShowSearchButton
 * @method HasManyList|Filter[] Filters
 */
class FilterPageExtension extends DataExtension
{
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
