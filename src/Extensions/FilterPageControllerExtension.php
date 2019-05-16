<?php

namespace TheWebmen\FacetFilters\Extensions;

use SilverStripe\Control\RequestHandler;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\PaginatedList;
use TheWebmen\FacetFilters\Forms\FilterForm;
use TheWebmen\FacetFilters\Forms\SortForm;
use TheWebmen\FacetFilters\Model\FacetIndexItemsList;
use TheWebmen\FacetFilters\Services\ElasticaService;
use TheWebmen\FacetFilters\Sort\Item;

/**
 * @property RequestHandler|FilterPageExtension owner
 */
class FilterPageControllerExtension extends Extension
{
    private static $allowed_actions = [
        'FilterForm',
    ];

    /**
     * @var FacetIndexItemsList
     */
    protected $list;

    public function FilterForm()
    {
        $filters = [];
        foreach ($this->owner->Filters() as $filter) {
            if ($filter->createBucket()) {
                foreach ($this->getList()->getResultSet()->getAggregation($filter->ID)['buckets'] as $option) {
                    if (!$option['doc_count']) {
                        continue;
                    }

                    $filter->addOption($option);
                }
            }
            $filters[] = $filter;
        }

        $form = new FilterForm($this->owner, 'FilterForm', $filters);
        $form->setShowSearchButton((bool) $this->owner->ShowSearchButton);

        if (method_exists($this->owner, 'updateFilterForm')) {
            $this->owner->updateFilterForm($form);
        }

        return $form;
    }

    public function getList()
    {
        if (!$this->list) {
            $query = new \Elastica\Query();

            $sortField = $this->owner->getRequest()->getVar(FilterForm::SORT_FIELD_NAME);
            $sortOrderField = $this->owner->getRequest()->getVar(FilterForm::SORT_ORDER_NAME);
            if (!empty($sortField) && !empty($sortOrderField)) {
                $query->setSort([$sortField => $sortOrderField]);
            }

            $bool = new \Elastica\Query\BoolQuery();

            foreach ($this->owner->Filters() as $filter) {
                $filterQuery = $filter->getElasticaQuery();

                if ($filterQuery) {
                    $bool->addMust($filterQuery);
                }

                if ($filter->createBucket()) {
                    $terms = new \Elastica\Aggregation\Terms($filter->ID);
                    $terms->setField($filter->FieldName);
                    $terms->setOrder('_term', 'asc');
                    $terms->setSize(999);

                    $query->addAggregation($terms);
                }
            }

            $query->setSize(999);

            $query->setQuery($bool);

            if (method_exists($this->owner, 'updateQuery')) {
                $this->owner->updateQuery($query);
            }

            $list = new FacetIndexItemsList(ElasticaService::singleton()->getIndex(), $query);

            $this->list = $list;
        }

        return $this->list;
    }

    public function PaginatedList()
    {
        $list = new PaginatedList($this->getList());
        $list->setRequest($this->owner->getRequest());

        if (method_exists($this->owner, 'updatePaginatedList')) {
            $this->owner->updatePaginatedList($list);
        }

        return $list;
    }
}
