<?php

namespace Detail\Importing\Source;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class DBALSource extends BaseSource
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
     * @return array
     */
    public function fetchAll()
    {
        return $this->findBy();
    }

    /**
     * @return Connection
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
    )
    {
        $query = $this->createSelectQuery($criteria);

        // var_dump((string) $query);

        return $query->execute()->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * @param array $criteria
     * @return QueryBuilder
     */
    protected function createSelectQuery(
//        $query = null,
        array $criteria = array()
//        array $orderBy = null,
//        $limit = null,
//        $offset = null
    ) {

        // if (! $query instanceof QueryBuilder)
        $query = $this->getSelectionQuery(
            new QueryBuilder($this->getConnection())
        );

        foreach ($criteria as $condition) {
            switch ($condition['operator']) {
                case 'IN':
                    $values = is_array($condition['value']) ? $condition['value'] : array($condition['value']);

                    foreach ($values as &$value) {
                        $value = $this->getConnection()->quote($value, \PDO::PARAM_STR);
                    }

                    // Should be able to use params .. but does not works ....
//                    $query->andWhere(sprintf("%s IN ( :%s )", $condition['field'], $condition['field']))
//                        ->setValue(':'.$condition['field'], implode(',', $values));

                    $query->andWhere(sprintf("%s IN (%s)", $condition['field'], implode(',', $values)));

                    break;

                default:
                    // do nothing;
            }
        }

        return $query;
    }

    /**
     * @return string
     */
    protected function getDbName()
    {
        return $this->getConnection()->getDatabase();
    }

    /**
     * @return string
     */
    protected function getTableFullName($tableName)
    {
        return sprintf("%s.%s", $this->getDbName(), $tableName);
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    abstract protected function getSelectionQuery(QueryBuilder $query);
}
