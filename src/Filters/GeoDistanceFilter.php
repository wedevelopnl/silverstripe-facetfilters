<?php

namespace TheWebmen\FacetFilters\Filters;

use SilverStripe\Control\Controller;
use TheWebmen\FacetFilters\Forms\GeoDistanceFilterField;

/**
 * @property string PostFix
 */
class GeoDistanceFilter extends Filter
{
    private static $table_name = 'TheWebmen_FacetFilters_Filters_GeoDistanceFilter';

    private static $db = [
        'PostFix' => 'Varchar',
    ];

    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);
        $search = urlencode($value['Search'] . ($this->PostFix ? ' ' . $this->PostFix : null));

        $mapsKey = self::config()->mapsKey;
        if (!$mapsKey) {
            throw new \Exception('Maps key is empty');
        }

        if ($value['Search']) {
            $data = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$search}&key={$mapsKey}");
            $data = json_decode($data, true);
            if ($data['status'] == 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                $distance = !empty($value['Distance']) ? $value['Distance'] : '10km';

                $query = new \Elastica\Query\GeoDistance($this->FieldName, "{$location['lat']},{$location['lng']}", $distance);
            }
        }

        return $query;
    }

    public function getFormField()
    {
        $field = new GeoDistanceFilterField($this->ID);

        $field->getSearchField()->setAttribute('placeholder', $this->Placeholder);

        return $field;
    }

    public function getTitle()
    {
        return 'GeoDistance';
    }
}
