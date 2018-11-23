<?php

namespace Detail\Importing\Runner\Processor;

use Detail\Importing\Exception;

abstract class ObjectRowsetProcessor extends ObjectProcessor
{
    /**
     * @param array $data
     * @param array $options
     * @return void
     */
    public function process($data, array $options = [])
    {
        if (!is_array($data)) {
            throw new Exception\InvalidArgumentException(
                'Invalid rowset; must be an array'
            );
        }

        foreach ($data as $row) {
            $this->verifyRow($row);
        }

        $this->processRowset($data, $options);
    }

    /**
     * @param array $rows
     * @param array $options
     * @return void
     */
    abstract protected function processRowset($rows, array $options = []);
}
