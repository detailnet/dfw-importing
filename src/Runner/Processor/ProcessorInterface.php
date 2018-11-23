<?php

namespace Detail\Importing\Runner\Processor;

interface ProcessorInterface
{
    /**
     * @param array|object $data
     * @param array $options
     * @return void
     */
    public function process($data, array $options = []);
}
