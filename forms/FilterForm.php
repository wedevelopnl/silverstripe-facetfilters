<?php
class FilterForm extends Form {

    public function __construct($controller, $name, $filters)
    {
        $fields = new FieldList(
            TextField::create('Query', 'Zoekterm')
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