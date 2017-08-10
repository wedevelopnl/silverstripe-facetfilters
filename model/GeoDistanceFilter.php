<?php
class GeoDistanceFilter extends Filter {

    public function getElasticaQuery()
    {
        $query = false;
        $location = Controller::curr()->getRequest()->getVar("{$this->ID}_Location");
        $distance = Controller::curr()->getRequest()->getVar("{$this->ID}_Distance");

        if ($location) {
            $cache = SS_Cache::factory('googlemapsgeocode');
            $cacheKey = sha1($location);
            if (!($data = $cache->load($cacheKey))) {
                $data = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$location}&key=AIzaSyDXgYLgmIgRgoUqwWH1BZwsO1YLMyNWqRA");

                $cache->save($data, $cacheKey);
            }

            $data = Convert::json2array($data);
            $location = $data['results'][0]['geometry']['location'];
            $distance = !empty($distance) ? $distance : '10km';

            $query = new Elastica\Query\GeoDistance($this->FieldName, "{$location['lat']},{$location['lng']}", $distance);
        }

        return $query;
    }

    public function getFormField()
    {
        return new GeoDistanceFilterField($this->ID);
    }

}
