<?php
class TermsFilterField extends CheckboxSetField {

    protected $collapsed = false;

    public function __construct($name, $title=null, $source=array(), $collapsed=false, $value='', $form=null) {
        $this->collapsed = $collapsed;

        parent::__construct($name, $title, $source, $value, $form);
    }

    public function FieldHolder($properties = array())
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
