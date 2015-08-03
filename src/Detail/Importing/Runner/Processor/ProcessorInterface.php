<?php

namespace Detail\Importing\Runner\Processor;

interface ProcessorInterface
{
    /**
     * @param array $data
     * @param array $options
     * @return void
     */
    public function process(array $data, array $options = array());
}
