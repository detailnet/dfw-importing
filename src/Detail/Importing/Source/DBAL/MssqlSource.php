<?php

namespace Detail\Importing\Source\DBAL;

use Doctrine\DBAL\Driver as DBALDriver;

use Detail\Importing\Source\DBALSource;

abstract class MssqlSource extends DBALSource
{
    /** @var  string  */
    public $schemaName;

    /**
     * @param DBALDriver\Connection $connection
     * @param string $dbName
     * @param string $schemaName
     */
    public function __construct(DBALDriver\Connection $connection, $dbName, $schemaName)
    {
        parent::__construct($connection, $dbName);

        $this->schemaName = $schemaName;
    }


    /**
     * @return string
     */
    abstract protected function getSelectString();

    protected function createSelectQuery(
//        $fields = "*",
        array $criteria = array()
//        array $orderBy = null,
//        $limit = null,
//        $offset = null
    ) {
        /** @todo Very rudimentary for the moment, refactor to get entityAliases, use constants for operators ... */

        $connection = $this->getConnection();
        $where      = '';

        foreach ($criteria as $condition) {
            $part = '';

            switch ($condition['operator']) {
                case 'IN':
                    $values = is_array($condition['value']) ? $condition['value'] : array($condition['value']);

                    foreach ($values as &$value) {
                        $value = $connection->quote($value, \PDO::PARAM_STR);
                    }

                    $part .= sprintf(
                        "%s IN (%s)",
                        $condition['field'],
                        implode(',', $values)
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

        return $connection->prepare(
            sprintf(
                "%s %s",
                $this->getSelectString(),
                $where
            )
        );
    }

    /**
     * @return string
     */
    protected function getTableFullName($tableName)
    {
        return sprintf("%s.%s.%s", $this->getDbName(), $this->getSchemaName(), $tableName);
    }

    /**
     * @return string
     */
    protected function getSchemaName()
    {
        return $this->schemaName;
    }
}
