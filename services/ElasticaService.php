<?php
class ElasticaService extends Object {

    /**
     * @var \Elastica\Index
     */
    protected $index;

    public function __construct()
    {
        $client = new Elastica\Client();

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

        foreach ($this->getIndexedClasses() as $class) {
            $instance = $class::singleton();
            $type = $this->index->getType($instance->getElasticaType());

            $mapping = $instance->getElasticaMapping();
            $mapping->setType($type);
            $mapping->send();

            $type = $this->index->getType($class::singleton()->getElasticaType());
            foreach ($class::get() as $record) {
                $type->addDocument($record->getElasticaDocument());
            }
        }
    }

    public function search(Elastica\Query $query)
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
