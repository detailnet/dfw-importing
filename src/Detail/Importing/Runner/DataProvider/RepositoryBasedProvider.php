<?php

namespace Detail\Importing\Runner\DataProvider;

use Detail\Importing\Repository\RepositoryInterface;

class RepositoryBasedProvider implements
    ProviderInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $accessor;

    /**
     * @var array
     */
    protected $arguments;

    /**
     * @param RepositoryInterface $repository
     * @param string $accessor
     * @param array $arguments
     */
    public function __construct(RepositoryInterface $repository, $accessor, array $arguments = array())
    {
        $this->repository = $repository;
        $this->accessor = (string) $accessor;
        $this->arguments = $arguments;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getAccessor()
    {
        return $this->accessor;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        $repository = $this->getRepository();
        $accessor = $this->getAccessor();

        /** @todo Check if accessor can be called */

        $rows = call_user_func_array(array($repository, $accessor), $this->getArguments());

        /** @todo Error handling */

        return $rows;
    }
}
