<?php
class FilterPageExtension extends DataExtension {

    private static $has_many = [
        'Filters' => 'Filter'
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $filtersGridFieldConfig = GridFieldConfig_RecordEditor::create()
            ->addComponent(new GridFieldOrderableRows('Sort'))
            ->removeComponentsByType('GridFieldAddNewButton')
            ->addComponent(new GridFieldAddNewMultiClass());
        $filtersGridField = new GridField('Filters', 'Filters', $this->owner->Filters(), $filtersGridFieldConfig);
        $fields->addFieldToTab('Root.Filters', $filtersGridField);
    }

}
