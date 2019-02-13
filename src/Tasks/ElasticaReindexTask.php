<?php

namespace TheWebmen\FacetFilters\Tasks;

use SilverStripe\Dev\BuildTask;
use TheWebmen\FacetFilters\Services\ElasticaService;

class ElasticaReindexTask extends BuildTask {

    public function run($request)
    {
        ElasticaService::singleton()->reindex();
    }

}
