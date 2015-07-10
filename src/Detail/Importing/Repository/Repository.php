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
    protected $dtoEntityName;

    /**
     * @param Source\SourceInterface $source
     * @param string $dtoEntityName DTO entity full class name
     */
    public function __construct(Source\SourceInterface $source, $dtoEntityName)
    {
        $this->source = $source;
        $this->dtoEntityName = $dtoEntityName;
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
        // array_walk($rows, $this->getNormalizer()->denormalize, $this->dtoEntityName);
        foreach ($rows as &$row) {
            $row = $this->getNormalizer()->denormalize($row, $this->dtoEntityName);
        }

        return $rows;
    }
}
