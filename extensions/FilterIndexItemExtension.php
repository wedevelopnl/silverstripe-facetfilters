<?php
class FilterIndexItemExtension extends DataExtension {

    public function getElasticaType()
    {
        return $this->ownerBaseClass;
    }

    public function getElasticaFields()
    {
        $fields = [
            'ID' => ['type' => 'integer'],
            'ParentID' => ['type' => 'integer'],
            'Title' => ['type' => 'string'],
            'Content' => ['type' => 'string']
        ];

        if (method_exists($this->owner, 'updateElasticaFields')) {
            $this->owner->updateElasticaFields($fields);
        }

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

        return new \Elastica\Document(implode('_', [$this->owner->ClassName, $this->owner->ID]), $data);
    }

}
