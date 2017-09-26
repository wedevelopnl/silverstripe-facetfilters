<?php
class FilterPageExtension extends DataExtension {

    private static $db = [
        'ShowSearchButton' => 'Boolean'
    ];

    private static $has_many = [
        'Filters' => 'Filter'
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
