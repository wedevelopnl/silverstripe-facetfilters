<?php

namespace TheWebmen\FacetFilters\Filters;

use SilverStripe\Control\Controller;
use TheWebmen\FacetFilters\Forms\RangeFilterField;
use TheWebmen\FacetFilters\Services\ElasticaService;

class RangeFilter extends Filter
{
    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);
        $value = is_array($value) ? $value : [];

        if (isset($value['From']) && isset($value['To']) && is_numeric($value['From']) && is_numeric($value['To'])) {
            $query = new \Elastica\Query\Range($this->FieldName, [
                'gte' => $value['From'],
                'lte' => $value['To'],
            ]);
        }

        return $query;
    }

    public function getFormField()
    {
        $minMax = $this->getMinMax();

        return new RangeFilterField($this->ID, $this->Name, null, $minMax['min'], $minMax['max']);
    }

    public function getTitle()
    {
        return 'Range';
    }

    private function getMinMax()
    {
        $query = new \Elastica\Query([
            'aggs' => [
                'min' => [
                    'min' => [
                        'field' => $this->FieldName
                    ],
                ],
                'max' => [
                    'max' => [
                        'field' => $this->FieldName
                    ],
                ],
            ]
        ]);

        $response = ElasticaService::singleton()->search($query);

        return [
            'min' => $response->getAggregation('min')['value'],
            'max' => $response->getAggregation('max')['value'],
        ];
    }
}
