<?php

namespace Detail\Importing\Runner;

interface RunnerInterface
{
    /**
     * @param array $options
     * @return void
     */
    public function run(array $options = array());
}
