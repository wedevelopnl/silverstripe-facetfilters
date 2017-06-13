<?php
class FilterForm extends Form {

    public function __construct($controller, $name, $filters)
    {
        $fields = new FieldList(
            TextField::create('Query', 'Zoekterm'),
            TextField::create('Location', 'Plaats/postcode'),
            DropdownField::create('Distance', 'Afstand', [
                '10km' => '10 Km',
                '20km' => '20 Km',
                '50km' => '50 Km',
                '100km' => '100 Km',
                '150km' => '150 Km',
                '200km' => '200 Km',
            ])
        );

        foreach ($filters as $filter) {
            $fields->merge($filter->getFormFields());
        }

        $actions = new FieldList(
            FormAction::create('doFilter', 'Zoeken')
        );

        parent::__construct($controller, $name, $fields, $actions);

        $this->setFormMethod('GET');
        $this->disableSecurityToken();
        $this->loadDataFrom($controller->getRequest()->getVars());
    }

}