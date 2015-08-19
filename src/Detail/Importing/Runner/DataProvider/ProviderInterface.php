<?php

namespace Detail\Importing\Runner\DataProvider;

interface ProviderInterface
{
    /**
     * @param array $arguments
     * @return array
     */
    public function getRows(array $arguments = null);
}
