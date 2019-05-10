<?php

namespace TheWebmen\FacetFilters\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;

/**
 * @property FilterIndexItemExtension owner
 * @mixin DataObject
 */
class FilterIndexItemExtension extends DataExtension
{
    public function getElasticaType()
    {
        return $this->owner->baseClass();
    }

    public function getElasticaFields()
    {
        $fields = [
            'ID' => ['type' => 'integer'],
            'ParentID' => ['type' => 'integer'],
            'Title' => ['type' => 'text'],
            'Content' => ['type' => 'text']
        ];

        if (method_exists($this->owner, 'updateElasticaFields')) {
            $this->owner->updateElasticaFields($fields);
        }

        $this->owner->extend('updateElasticaFields', $fields);

        return $fields;
    }

    public function getElasticaMapping()
    {
        $mapping = new \Elastica\Type\Mapping();
        $mapping->setProperties($this->getElasticaFields());
        $mapping->setParam('date_detection', false);

        return $mapping;
    }

    public function getElasticaDocument()
    {
        $data = [];
        foreach ($this->owner->getElasticaFields() as $fieldName => $fieldData) {
            if ($this->owner->hasField($fieldName)) {
                $data[$fieldName] = $this->owner->$fieldName;
            }
        }

        if (method_exists($this->owner, 'updateElasticaDocumentData')) {
            $this->owner->updateElasticaDocumentData($data);
        }

        $this->owner->extend('updateElasticaDocumentData', $data);

        return new \Elastica\Document(implode('_', [$this->owner->ClassName, $this->owner->ID]), $data, $this->getElasticaType());
    }
}
