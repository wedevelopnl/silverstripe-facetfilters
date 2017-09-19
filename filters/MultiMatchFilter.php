<?php
class MultiMatchFilter extends Filter {

    protected $options = [];

    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);

        if ($value) {
            $query = new Elastica\Query\MultiMatch();
            $query->setQuery($value);
            $query->setFields(['Title', 'Content']);
        }

        return $query;
    }

    public function getFormField()
    {
        return new MultiMatchFilterField($this->ID, $this->Name);
    }

}
