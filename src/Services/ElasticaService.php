<?php

namespace TheWebmen\FacetFilters\Services;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Core\Extensible;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\ORM\DataObject;
use TheWebmen\FacetFilters\Extensions\FilterIndexItemExtension;
use Translatable;

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
        $config = self::config()->get('client_config');
        $client = new \Elastica\Client($config ? $config : []);
        $this->index = $client->getIndex(self::config()->get('index_name'));
    }

    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param FilterIndexItemExtension $record
     */
    public function add($record)
    {
        $type = $this->index->getType($record->getElasticaType());
        $type->addDocument($record->getElasticaDocument());
    }

    /**
     * @param FilterIndexItemExtension $record
     */
    public function delete($record)
    {
        $type = $this->index->getType($record->getElasticaType());
        $type->deleteDocument($record->getElasticaDocument());
    }

    public function reindex()
    {
        if ($this->index->exists()) {
            $this->index->delete();
        }

        $this->index->create();

        $documents = [];

        foreach ($this->getIndexedClasses() as $class) {
            /** @var FilterIndexItemExtension $instance */
            $instance = $class::singleton();
            $type = $this->index->getType($instance->getElasticaType());

            $mapping = $instance->getElasticaMapping();
            $mapping->setType($type);
            $mapping->send();

            if (class_exists('Translatable')) {
                foreach (Translatable::get_allowed_locales() as $locale) {
                    Translatable::set_current_locale($locale);

                    /** @var FilterIndexItemExtension $record */
                    foreach ($class::get() as $record) {
                        $documents[] = $record->getElasticaDocument();
                    }
                }
            } else {
                /** @var FilterIndexItemExtension $record */
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
        foreach (ClassInfo::subclassesFor(DataObject::class) as $candidate) {
            if (singleton($candidate)->hasExtension(FilterIndexItemExtension::class)) {
                $classes[] = $candidate;
            }
        }
        return $classes;
    }
}
