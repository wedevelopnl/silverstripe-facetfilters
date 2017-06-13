<?php
class ElasticaReindexTask extends BuildTask {

    public function run($request)
    {
        ElasticaService::singleton()->reindex();
    }

}
