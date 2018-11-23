<?php

namespace Detail\Importing\Source;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class DBALSource extends BaseSource
{
    /**
     * @var Connection $connection
     */
    protected $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this->getConnection());
    }

    /**
     * @param string $tableName
     * @return string
     */
    public function prepareTableName($tableName)
    {
        return sprintf("%s.%s", $this->getConnection()->getDatabase(), $tableName);
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }
}
