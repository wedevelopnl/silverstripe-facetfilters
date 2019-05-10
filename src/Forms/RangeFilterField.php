<?php

namespace TheWebmen\FacetFilters\Forms;

use SilverStripe\Forms\FormField;
use SilverStripe\Forms\TextField;

class RangeFilterField extends FormField
{
    protected $fromField = null;

    protected $toField = null;

    public function __construct($name)
    {
        $this->fromField = TextField::create($name . '[From]', 'Vanaf');
        $this->toField = TextField::create($name . '[To]', 'Tot');

        parent::__construct($name, '');
    }

    public function setValue($value, $data = null)
    {
        parent::setValue($value, $data);

        if (is_array($value)) {
            $this->fromField->setValue($value['From']);
            $this->toField->setValue($value['To']);
        }

        return $this;
    }

    public function getFromField()
    {
        return $this->fromField;
    }

    public function getToField()
    {
        return $this->toField;
    }
}
