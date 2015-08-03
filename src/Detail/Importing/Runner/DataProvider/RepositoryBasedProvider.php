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
        $this->setRepository($repository);
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return string
     */
    public function getAccessor()
    {
        return $this->accessor;
    }

    /**
     * @param string $accessor
     */
    public function setAccessor($accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
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
