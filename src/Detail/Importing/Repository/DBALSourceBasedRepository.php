<?php

namespace Detail\Importing\Repository;

use PDO;

use Doctrine\DBAL\Query\QueryBuilder;

use Detail\Importing\Exception;
use Detail\Importing\Source;

/**
 * @method Source\DBALSource getSource()
 */
class DBALSourceBasedRepository extends SourceBasedRepository
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @param Source\DBALSource $source
     * @param string $objectName
     * @param string $tableName
     */
    public function __construct(Source\DBALSource $source, $objectName, $tableName = null)
    {
        parent::__construct($source, $objectName);

        if ($tableName !== null) {
            $this->setTableName($tableName);
        }
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $query = $this->createSelectQuery();
        $rows = $this->executeSelectQuery($query);

        return $rows;
    }

    /**
     * @return QueryBuilder
     */
    protected function createSelectQuery()
    {
        $query = $this->getSource()->createQueryBuilder()->select('*');
        $query->from($this->getTableName(true));

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @return array
     */
    protected function executeSelectQuery(QueryBuilder $query)
    {
        $rows = $query->execute()->fetchAll(PDO::FETCH_ASSOC);
        $rows = $this->processRows($rows);

        return $this->denormalizeRows($rows);
    }

    /**
     * @param boolean $prepareForQuery
     * @return string
     */
    protected function getTableName($prepareForQuery = false)
    {
        if ($this->tableName === null) {
            throw new Exception\RuntimeException('Missing table name; it is required for this repository');
        }

        return ($prepareForQuery === true) ? $this->getSource()->prepareTableName($this->tableName) : $this->tableName;
    }

    /**
     * @param string $tableName
     */
    protected function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function processRows(array $rows)
    {
        return $rows;
    }
}
