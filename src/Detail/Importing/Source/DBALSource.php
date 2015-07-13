<?php

namespace Detail\Importing\Source;

use Doctrine\DBAL\Driver as DBALDriver;

abstract class DBALSource extends BaseSource
{
    /**
     * @var DBALDriver\Connection $connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $dbName;


    /**
     * @param DBALDriver\Connection $connection
     * @param string $dbName
     */
    public function __construct(DBALDriver\Connection $connection, $dbName)
    {
        $this->connection = $connection;
        $this->dbName = $dbName;
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        return $this->findBy();
    }

    /**
     * @return DBALDriver\Connection
     */
    protected function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param array $criteria
     * @return array
     */
    protected function findBy(
        array $criteria = array()
//        array $orderBy = null,
//        $limit = null,
//        $offset = null
    ) {
        $statement = $this->createSelectQuery($criteria);

        if (!$statement->execute()) {
            return array();
        };

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * @param array $criteria
     * @return DBALDriver\Statement
     */
    abstract protected function createSelectQuery(
//        $fields = "*",
        array $criteria = array()
//        array $orderBy = null,
//        $limit = null,
//        $offset = null
    );

    /**
     * @return string
     */
    protected function getDbName()
    {
        return $this->dbName;
    }
}
