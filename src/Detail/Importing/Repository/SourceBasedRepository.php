<?php

namespace Detail\Importing\Repository;

use Detail\Importing\Source;

abstract class SourceBasedRepository extends BaseRepository
{
    /**
     * @var Source\SourceInterface
     */
    protected $source;

    /**
     * @param Source\SourceInterface $source
     * @param string $objectName Full class name
     */
    public function __construct(Source\SourceInterface $source, $objectName)
    {
        parent::__construct($objectName);

        $this->source = $source;
    }

    /**
     * @return Source\SourceInterface
     */
    protected function getSource()
    {
        return $this->source;
    }
}
