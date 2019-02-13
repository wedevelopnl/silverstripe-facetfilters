<?php

namespace TheWebmen\FacetFilters\Services;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Config\Configurable;

class ElasticaService
{
    use Extensible;
    use Injectable;
    use Configurable;


    /**
     * @var \Elastica\Index
     */
    protected $index;

    public function __construct()
    {
        $client = new \Elastica\Client();
        $client->setConfig(['url' => 'http://192.168.50.209:9200/']);
        $this->index = $client->getIndex(self::config()->get('index_name'));
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function add($record)
    {
        $type = $this->index->getType($record->getElasticaType());
        $type->addDocument($record->getElasticaDocument());
    }

    public function delete($record)
    {
        $type = $this->index->getType($record->getElasticaType());
        $type->deleteDocument($record->getElasticaDocument());
    }

    public function reindex()
    {
        $this->index->delete();
        $this->index->create();

        $documents = [];

        foreach ($this->getIndexedClasses() as $class) {
            $instance = $class::singleton();
            $type = $this->index->getType($instance->getElasticaType());

            $mapping = $instance->getElasticaMapping();
            $mapping->setType($type);
            $mapping->send();

            if (class_exists('Translatable')) {
                foreach (Translatable::get_allowed_locales() as $locale) {
                    Translatable::set_current_locale($locale);

                    foreach ($class::get() as $record) {
                        $documents[] = $record->getElasticaDocument();
                    }
                }
            } else {
                foreach ($class::get() as $record) {
                    $documents[] = $record->getElasticaDocument();
                }
            }
        }

        $this->index->addDocuments($documents);
    }

    public function search(\Elastica\Query $query)
    {
        return $this->index->search($query);
    }

    public function getIndexedClasses()
    {
        $classes = [];
        foreach (ClassInfo::subclassesFor('DataObject') as $candidate) {
            if (singleton($candidate)->hasExtension('FilterIndexItemExtension')) {
                $classes[] = $candidate;
            }
        }
        return $classes;
    }

}
