<?php

namespace TheWebmen\FacetFilters\Forms;

use SilverStripe\Forms\CheckboxSetField;

class TermsFilterField extends CheckboxSetField
{
    protected $collapsed = false;

    public function __construct($name, $title=null, $source=[], $collapsed=false, $value='')
    {
        $this->collapsed = $collapsed;

        parent::__construct($name, $title, $source, $value);
    }

    public function FieldHolder($properties = [])
    {
        if ($this->Value()) {
            $this->collapsed = false;
        }

        return parent::FieldHolder($properties);
    }

    public function getCollapsed()
    {
        return $this->collapsed;
    }
}
