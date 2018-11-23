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
        $rows = [];
        $statement = $query->execute();

        /** @todo Should configure which fetch method to use (fetch row by row or fetchAll) */
        while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
            $this->processRow($row, $rows);
        }

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
     * Process single row.
     *
     * Possible use case is to lower memory footprint when using data aggregation.
     * After data manipulation the row has to be added in the rows array passed as pointer.
     *
     * @param array $row
     * @param array &$rows
     */
    protected function processRow(array $row, array &$rows)
    {
        $rows[] = $row;
    }

    /**
     * @param array $rows
     * @return array
     */
    protected function processRows(array $rows) /** @todo Should pass $rows as pointer */
    {
        return $rows;
    }
}
