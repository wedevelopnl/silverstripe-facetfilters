<?php

namespace TheWebmen\FacetFilters\Forms;

use SilverStripe\Forms\FormField;
use SilverStripe\Forms\TextField;

class RangeFilterField extends FormField
{
    protected $fromField = null;
    protected $minValue = 0;
    protected $maxValue = 0;

    protected $toField = null;

    public function __construct($name, $title = null, $value = null, $minValue = 0, $maxValue = 0)
    {
        $this->fromField = TextField::create($name . '[From]', 'Vanaf');
        $this->toField = TextField::create($name . '[To]', 'Tot');

        $this->minValue = $minValue;
        $this->maxValue = $maxValue;

        parent::__construct($name, $title, $value);
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

    public function getFromValue()
    {
        return (!empty($this->fromField->value)) ? $this->fromField->value : $this->minValue;
    }

    public function getToValue()
    {
        return (!empty($this->toField->value)) ? $this->toField->value : $this->maxValue;
    }

    public function getMinValue()
    {
        return $this->minValue;
    }

    public function getMaxValue()
    {
        return $this->maxValue;
    }
}
