<?php

namespace Detail\Importing\Repository;

use Detail\Importing\Source;

class Repository implements
    RepositoryInterface
{
    /**
     * @var Source\SourceInterface
     */
    protected $source;

    /**
     * @param Source\SourceInterface $source
     */
    public function __construct(Source\SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $rows = $this->getSource()->fetchAll();

        /** @todo Hydrate/normalize $rows to DTOs */
        // $this->getNormalizer()->normalize(Denner\Importing\DTO\NavisionText::CLASS, $rows);
        $records = array();

        return $records;
    }

    /**
     * @return Source\SourceInterface
     */
    protected function getSource()
    {
        return $this->source;
    }
}
