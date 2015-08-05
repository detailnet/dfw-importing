<?php

namespace Detail\Importing\Runner\Processor;

abstract class ObjectRowProcessor extends ObjectProcessor
{
    /**
     * @param object $data
     * @param array $options
     * @return void
     */
    public function process($data, array $options = array())
    {
        $this->verifyRow($data);
        $this->processRow($data, $options);
    }

    /**
     * @param object $row
     * @param array $options
     * @return void
     */
    abstract protected function processRow($row, array $options = array());
}
