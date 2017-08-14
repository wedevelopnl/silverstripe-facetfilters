<?php
class TermsFilterField extends CheckboxSetField {

    protected $collapsed = false;

    public function __construct($name, $title=null, $source=array(), $collapsed=false, $value='', $form=null) {
        $this->collapsed = $collapsed;

        parent::__construct($name, $title, $source, $value, $form);
    }

    public function getCollapsed()
    {
        return $this->collapsed;
    }
}
