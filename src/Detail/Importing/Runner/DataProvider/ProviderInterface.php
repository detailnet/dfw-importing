<?php

namespace Detail\Importing\Runner\DataProvider;

interface ProviderInterface
{
    /**
     * @return array
     */
    public function getRows();
}
