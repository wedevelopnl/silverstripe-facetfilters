<?php

namespace TheWebmen\FacetFilters\Forms;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;

class FilterForm extends Form
{
    const SORT_FIELD_NAME = 'Sort';
    const SORT_ORDER_NAME = 'Direction';

    private $showSearchButton = true;

    public function __construct(RequestHandler $controller, $name = self::DEFAULT_NAME, array $filters = [])
    {
        $fields = new FieldList();

        if ($controller->SortItems()->count() > 0) {
            $fields->push(DropdownField::create(self::SORT_FIELD_NAME, '', $controller->SortItems()->map('FieldName', 'Label')));
            $fields->push(DropdownField::create(self::SORT_ORDER_NAME, '', [
                'asc' => 'Oplopend',
                'desc' => 'Aflopend',
            ]));
        }

        foreach ($filters as $filter) {
            if ($field = $filter->getFormField()) {
                $fields->push($field);
            }
        }

        $actions = new FieldList();

        if ($this->showSearchButton) {
            $actions->push(FormAction::create('', 'Zoeken')->setAttribute('name', ''));
        }

        parent::__construct($controller, $name, $fields, $actions);

        $this->setFormMethod('GET');
        $this->setFormAction($controller->getRequest()->getUrl());
        $this->disableSecurityToken();
        $this->loadDataFrom($controller->getRequest()->getVars());
    }

    public function setShowSearchButton($showSearchButton)
    {
        $this->showSearchButton = $showSearchButton;
    }
}
