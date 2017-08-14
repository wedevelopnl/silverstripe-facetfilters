<?php
class GeoDistanceFilterField extends FormField {

    protected $searchField = null;

    protected $distanceField = null;

    public function __construct($name) {
        $this->searchField = TextField::create($name . '[search]', 'Plaats/postcode');
        $this->distanceField = DropdownField::create($name . '[distance]', 'Afstand', [
            '10km' => '10 Km',
            '20km' => '20 Km',
            '50km' => '50 Km',
            '100km' => '100 Km',
            '150km' => '150 Km',
            '200km' => '200 Km',
        ]);

        parent::__construct($name);
    }

    public function getSearchField()
    {
        return $this->searchField;
    }

    public function getDistanceField()
    {
        return $this->distanceField;
    }

}
