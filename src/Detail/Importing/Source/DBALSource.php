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
     * @param DBALDriver\Connection $connection
     */
    public function __construct(DBALDriver\Connection $connection)
    {
        $this->connection = $connection;
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
     * @return string
     */
    abstract protected function getSelectString();

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
    protected function createSelectQuery(
//        $fields = "*",
        array $criteria = array()
//        array $orderBy = null,
//        $limit = null,
//        $offset = null
    ) {
        /** @todo Very rudimentary for the moment, refactor to get entityAliases, use constants for operators ... */

        $where = '';

        foreach ($criteria as $condition) {
            $part = '';

            switch ($condition['operator']) {
                case 'IN':
                    $part .= sprintf(
                        "%s IN (%s)",
                        $condition['field'],
                        is_array($condition['value']) ? implode(', ', $condition['value']) : $condition['value']
                    );
                    break;
                default:
                    // do nothing;
            }

            if ($part !== '') {
                $where .= ($where === '') ? "WHERE" : "AND";
                $where .= " (" . $part . ")";
            }
        }

        return $this->getConnection()->prepare(
            sprintf(
                "%s %s",
                $this->getSelectString(),
                $where
            )
        );
    }
}
