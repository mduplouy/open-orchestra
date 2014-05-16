<?php

namespace Model\PHPOrchestraCMSBundle\Base;

/**
 * Base class of Model\PHPOrchestraCMSBundle\ContentType document.
 */
abstract class ContentType extends \Mandango\Document\Document
{
    /**
     * Initializes the document defaults.
     */
    public function initializeDefaults()
    {
    }

    /**
     * Set the document data (hydrate).
     *
     * @param array $data  The document data.
     * @param bool  $clean Whether clean the document.
     *
     * @return \Model\PHPOrchestraCMSBundle\ContentType The document (fluent interface).
     */
    public function setDocumentData($data, $clean = false)
    {
        if ($clean) {
            $this->data = array();
            $this->fieldsModified = array();
        }

        if (isset($data['_query_hash'])) {
            $this->addQueryHash($data['_query_hash']);
        }
        if (isset($data['_id'])) {
            $this->setId($data['_id']);
            $this->setIsNew(false);
        }
        if (isset($data['contentType'])) {
            $this->data['fields']['contentType'] = (string) $data['contentType'];
        } elseif (isset($data['_fields']['contentType'])) {
            $this->data['fields']['contentType'] = null;
        }
        if (isset($data['fields'])) {
            $embedded = new \Mandango\Group\EmbeddedGroup('Model\PHPOrchestraCMSBundle\ContentField');
            $embedded->setRootAndPath($this, 'fields');
            $embedded->setSavedData($data['fields']);
            $this->data['embeddedsMany']['fields'] = $embedded;
        }

        return $this;
    }

    /**
     * Set the "contentType" field.
     *
     * @param mixed $value The value.
     *
     * @return \Model\PHPOrchestraCMSBundle\ContentType The document (fluent interface).
     */
    public function setContentType($value)
    {
        if (!isset($this->data['fields']['contentType'])) {
            if (!$this->isNew()) {
                $this->getContentType();
                if ($this->isFieldEqualTo('contentType', $value)) {
                    return $this;
                }
            } else {
                if (null === $value) {
                    return $this;
                }
                $this->fieldsModified['contentType'] = null;
                $this->data['fields']['contentType'] = $value;
                return $this;
            }
        } elseif ($this->isFieldEqualTo('contentType', $value)) {
            return $this;
        }

        if (!isset($this->fieldsModified['contentType']) && !array_key_exists('contentType', $this->fieldsModified)) {
            $this->fieldsModified['contentType'] = $this->data['fields']['contentType'];
        } elseif ($this->isFieldModifiedEqualTo('contentType', $value)) {
            unset($this->fieldsModified['contentType']);
        }

        $this->data['fields']['contentType'] = $value;

        return $this;
    }

    /**
     * Returns the "contentType" field.
     *
     * @return mixed The $name field.
     */
    public function getContentType()
    {
        if (!isset($this->data['fields']['contentType'])) {
            if ($this->isNew()) {
                $this->data['fields']['contentType'] = null;
            } elseif (!isset($this->data['fields']) || !array_key_exists('contentType', $this->data['fields'])) {
                $this->addFieldCache('contentType');
                $data = $this->getRepository()->getCollection()->findOne(
                    array('_id' => $this->getId()),
                    array('contentType' => 1)
                );
                if (isset($data['contentType'])) {
                    $this->data['fields']['contentType'] = (string) $data['contentType'];
                } else {
                    $this->data['fields']['contentType'] = null;
                }
            }
        }

        return $this->data['fields']['contentType'];
    }

    private function isFieldEqualTo($field, $otherValue)
    {
        $value = $this->data['fields'][$field];

        return $this->isFieldValueEqualTo($value, $otherValue);
    }

    private function isFieldModifiedEqualTo($field, $otherValue)
    {
        $value = $this->fieldsModified[$field];

        return $this->isFieldValueEqualTo($value, $otherValue);
    }

    protected function isFieldValueEqualTo($value, $otherValue)
    {
        if (is_object($value)) {
            return $value == $otherValue;
        }

        return $value === $otherValue;
    }

    /**
     * Process onDelete.
     */
    public function processOnDelete()
    {
    }

    private function processOnDeleteCascade($class, array $criteria)
    {
        $repository = $this->getMandango()->getRepository($class);
        $documents = $repository->createQuery($criteria)->all();
        if (count($documents)) {
            $repository->delete($documents);
        }
    }

    private function processOnDeleteUnset($class, array $criteria, array $update)
    {
        $this->getMandango()->getRepository($class)->update($criteria, $update, array('multiple' => true));
    }

    /**
     * Returns the "fields" embedded many.
     *
     * @return \Mandango\Group\EmbeddedGroup The "fields" embedded many.
     */
    public function getFields()
    {
        if (!isset($this->data['embeddedsMany']['fields'])) {
            $this->data['embeddedsMany']['fields'] = $embedded =
                new \Mandango\Group\EmbeddedGroup('Model\PHPOrchestraCMSBundle\ContentField');
            $embedded->setRootAndPath($this, 'fields');
        }

        return $this->data['embeddedsMany']['fields'];
    }

    /**
     * Adds documents to the "fields" embeddeds many.
     *
     * @param mixed $documents A document or an array or documents.
     *
     * @return \Model\PHPOrchestraCMSBundle\ContentType The document (fluent interface).
     */
    public function addFields($documents)
    {
        $this->getFields()->add($documents);

        return $this;
    }

    /**
     * Removes documents to the "fields" embeddeds many.
     *
     * @param mixed $documents A document or an array or documents.
     *
     * @return \Model\PHPOrchestraCMSBundle\ContentType The document (fluent interface).
     */
    public function removeFields($documents)
    {
        $this->getFields()->remove($documents);

        return $this;
    }

    /**
     * Resets the groups of the document.
     */
    public function resetGroups()
    {
        if (isset($this->data['embeddedsMany']['fields'])) {
            $this->data['embeddedsMany']['fields']->reset();
        }
    }

    /**
     * Set a document data value by data name as string.
     *
     * @param string $name  The data name.
     * @param mixed  $value The value.
     *
     * @return mixed the data name setter return value.
     *
     * @throws \InvalidArgumentException If the data name is not valid.
     */
    public function set($name, $value)
    {
        if ('contentType' == $name) {
            return $this->setContentType($value);
        }

        throw new \InvalidArgumentException(sprintf('The document data "%s" is not valid.', $name));
    }

    /**
     * Returns a document data by data name as string.
     *
     * @param string $name The data name.
     *
     * @return mixed The data name getter return value.
     *
     * @throws \InvalidArgumentException If the data name is not valid.
     */
    public function get($name)
    {
        if ('contentType' == $name) {
            return $this->getContentType();
        }
        if ('fields' == $name) {
            return $this->getFields();
        }

        throw new \InvalidArgumentException(sprintf('The document data "%s" is not valid.', $name));
    }

    /**
     * Imports data from an array.
     *
     * @param array $array An array.
     *
     * @return \Model\PHPOrchestraCMSBundle\ContentType The document (fluent interface).
     */
    public function fromArray(array $array)
    {
        if (isset($array['id'])) {
            $this->setId($array['id']);
        }
        if (isset($array['contentType'])) {
            $this->setContentType($array['contentType']);
        }
        if (isset($array['fields'])) {
            $embeddeds = array();
            foreach ($array['fields'] as $documentData) {
                $embeddeds[] = $embedded = new \Model\PHPOrchestraCMSBundle\ContentField($this->getMandango());
                $embedded->setDocumentData($documentData);
            }
            $this->getFields()->replace($embeddeds);
        }

        return $this;
    }

    /**
     * Export the document data to an array.
     *
     * @param Boolean $withReferenceFields Whether include the fields of references or not (false by default).
     *
     * @return array An array with the document data.
     */
    public function toArray($withReferenceFields = false)
    {
        $array = array('id' => $this->getId());

        $array['contentType'] = $this->getContentType();

        return $array;
    }

    /**
     * Query for save.
     */
    public function queryForSave()
    {
        $isNew = $this->isNew();
        $query = array();
        $reset = false;

        if (isset($this->data['fields'])) {
            if ($isNew || $reset) {
                if (isset($this->data['fields']['contentType'])) {
                    $query['contentType'] = (string) $this->data['fields']['contentType'];
                }
            } else {
                if (isset($this->data['fields']['contentType'])
                    || array_key_exists('contentType', $this->data['fields'])) {
                    $value = $this->data['fields']['contentType'];
                    $originalValue = $this->getOriginalFieldValue('contentType');
                    if ($value !== $originalValue) {
                        if (null !== $value) {
                            $query['$set']['contentType'] = (string) $this->data['fields']['contentType'];
                        } else {
                            $query['$unset']['contentType'] = 1;
                        }
                    }
                }
            }
        }
        if (true === $reset) {
            $reset = 'deep';
        }
        if (isset($this->data['embeddedsMany'])) {
            if ($isNew) {
                if (isset($this->data['embeddedsMany']['fields'])) {
                    foreach ($this->data['embeddedsMany']['fields']->getAdd() as $document) {
                        $query = $document->queryForSave($query, $isNew);
                    }
                }
            } else {
                if (isset($this->data['embeddedsMany']['fields'])) {
                    $group = $this->data['embeddedsMany']['fields'];
                    foreach ($group->getSaved() as $document) {
                        $query = $document->queryForSave($query, $isNew);
                    }
                    $groupRap = $group->getRootAndPath();
                    foreach ($group->getAdd() as $document) {
                        $q = $document->queryForSave(array(), true);
                        $rap = $document->getRootAndPath();
                        foreach (explode('.', $rap['path']) as $name) {
                            if (0 === strpos($name, '_add')) {
                                $name = substr($name, 4);
                            }
                            $q = $q[$name];
                        }
                        $query['$pushAll'][$groupRap['path']][] = $q;
                    }
                    foreach ($group->getRemove() as $document) {
                        $rap = $document->getRootAndPath();
                        $query['$unset'][$rap['path']] = 1;
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Maps the validation.
     *
     * @param \Symfony\Component\Validator\Mapping\ClassMetadata $metadata The metadata class.
     */
    public static function loadValidatorMetadata(\Symfony\Component\Validator\Mapping\ClassMetadata $metadata)
    {
        $validation = array(
            'constraints' => array(

            ),
            'getters' => array(

            ),
        );

        foreach (\Mandango\MandangoBundle\Extension\DocumentValidation
            ::parseNodes($validation['constraints']) as $constraint) {
            $metadata->addConstraint($constraint);
        }

        foreach ($validation['getters'] as $getter => $constraints) {
            foreach (\Mandango\MandangoBundle\Extension\DocumentValidation
            ::parseNodes($constraints) as $constraint) {
                $metadata->addGetterConstraint($getter, $constraint);
            }
        }

        return true;
    }
}
