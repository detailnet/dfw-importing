<?php

namespace Detail\Importing\Repository;

use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareTrait;

use Detail\Importing\Source;

abstract class BaseRepository implements
    RepositoryInterface,
    NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @var string
     */
    protected $objectName;

    /**
     * @param string $objectName Full class name
     */
    public function __construct($objectName)
    {
        $this->objectName = $objectName;
    }

    /**
     * @return string
     */
    protected function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function denormalizeRows(array $rows)
    {
        $result = array();

        foreach ($rows as $row) {
            $result[] = $this->getNormalizer()->denormalize($row, $this->getObjectName());
        }

        return $result;
    }
}
