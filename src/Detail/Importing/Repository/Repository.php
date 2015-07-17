<?php

namespace Detail\Importing\Repository;

use Detail\Importing\Source;
use Detail\Normalization\Normalizer\Service\NormalizerAwareInterface;
use Detail\Normalization\Normalizer\Service\NormalizerAwareTrait;

class Repository implements
    RepositoryInterface,
    NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @var Source\SourceInterface
     */
    protected $source;

    /**
     * @var object
     */
    protected $objectName;

    /**
     * @param Source\SourceInterface $source
     * @param string $objectName DTO entity full class name
     */
    public function __construct(Source\SourceInterface $source, $objectName)
    {
        $this->source = $source;
        $this->objectName = $objectName;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $rows = $this->getSource()->fetchAll();

        return $this->denormalizeRows($rows);
    }

    /**
     * @return Source\SourceInterface
     */
    protected function getSource()
    {
        return $this->source;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function denormalizeRows(array $rows)
    {
        $result = array();

        foreach ($rows as $row) {
            $result[] = $this->getNormalizer()->denormalize($row, $this->objectName);
        }

        return $result;
    }
}
