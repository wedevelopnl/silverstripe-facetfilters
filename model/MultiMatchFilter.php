<?php
class MultiMatchFilter extends Filter {

    protected $options = [];

    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);

        if ($value) {
            $multiMatch = new Elastica\Query\MultiMatch();
            $multiMatch->setQuery($value);
            $multiMatch->setFields(['Title', 'Content']);
        }

        return $query;
    }

    public function getFormField()
    {
        return new MultiMatchFilterField($this->ID, $this->Name);
    }

}
