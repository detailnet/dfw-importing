<?php

namespace Detail\Importing\Source;

use Doctrine\DBAL;

abstract class DBALSource extends BaseSource
{
    /**
     * @var DBAL\Connection $connection
     */
    protected $connection;

    /**
     * @param DBAL\Connection $connection
     */
    public function __construct(DBAL\Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return DBAL\Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }
}
