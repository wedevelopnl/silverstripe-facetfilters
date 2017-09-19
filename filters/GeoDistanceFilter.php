<?php
class GeoDistanceFilter extends Filter {

    public function getElasticaQuery()
    {
        $query = false;
        $value = Controller::curr()->getRequest()->getVar($this->ID);

        if ($value['Search']) {
            $cache = SS_Cache::factory('googlemapsgeocode');
            $cacheKey = sha1($value['Search']);
            if (!($data = $cache->load($cacheKey))) {
                $data = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$value['Search']}&key=AIzaSyDXgYLgmIgRgoUqwWH1BZwsO1YLMyNWqRA");

                $cache->save($data, $cacheKey);
            }

            $data = Convert::json2array($data);
            $location = $data['results'][0]['geometry']['location'];
            $distance = !empty($value['Distance']) ? $value['Distance'] : '10km';

            $query = new Elastica\Query\GeoDistance($this->FieldName, "{$location['lat']},{$location['lng']}", $distance);
        }

        return $query;
    }

    public function getFormField()
    {
        return new GeoDistanceFilterField($this->ID);
    }

}
