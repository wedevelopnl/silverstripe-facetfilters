<?php
class FilterForm extends Form {

    public function __construct($controller, $name, $filters)
    {
        $fields = new FieldList();

        foreach ($filters as $filter) {
            $fields->push($filter->getFormField());
        }

        $actions = new FieldList();

        parent::__construct($controller, $name, $fields, $actions);

        $this->setFormMethod('GET');
        $this->disableSecurityToken();
        $this->loadDataFrom($controller->getRequest()->getVars());
    }

}
