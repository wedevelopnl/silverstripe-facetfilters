<?php
class FilterPageControllerExtension extends Extension {

    /**
     * @var FacetIndexItemsList
     */
    protected $list;

    private static $allowed_actions = [
        'FilterForm'
    ];

    public function onAfterInit()
    {
        $vars = $this->owner->getRequest()->getVars();

        $query = new Elastica\Query();
        $bool = new Elastica\Query\BoolQuery();

        if (!empty($vars['Query'])) {
            $multiMatch = new Elastica\Query\MultiMatch();
            $multiMatch->setQuery($vars['Query']);
            $multiMatch->setFields(['Title', 'Content']);

            $bool->addMust($multiMatch);
        }

        foreach ($this->owner->Filters() as $filter) {
            $filterQuery = $filter->getElasticaQuery();
            if ($filterQuery) {
                $bool->addMust($filterQuery);
            }

            if ($filter->createBucket()) {
                $terms = new Elastica\Aggregation\Terms($filter->ID);
                $terms->setField($filter->FieldName);
                $terms->setOrder('_term', 'asc');
                $terms->setMinimumDocumentCount(0);

                $query->addAggregation($terms);
            }
        }

        $query->setQuery($bool);

        $this->list = new FacetIndexItemsList(ElasticaService::singleton()->getIndex(), $query);
    }

    public function Form()
    {
        $filters = [];
        foreach ($this->owner->Filters() as $filter) {
            if ($filter->createBucket()) {
                foreach ($this->list->getResultSet()->getAggregation($filter->ID)['buckets'] as $option) {
                    $filter->addOption($option['key'], "{$option['key']} ({$option['doc_count']})");
                }
            }
            $filters[] = $filter;
        }

        $form = new FilterForm($this->owner, 'FilterForm', $filters);

        if (method_exists($this->owner, 'updateFilterForm')) {
            $this->owner->updateFilterForm($form);
        }

        return $form;
    }

    public function Items()
    {
        $list = new PaginatedList($this->list);
        $list->setRequest($this->owner->getRequest());
        $list->setPageLength(9);

        return $list;
    }

}
