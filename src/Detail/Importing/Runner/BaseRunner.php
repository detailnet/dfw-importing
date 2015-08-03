<?php

namespace Detail\Importing\Runner;

abstract class BaseRunner implements
    RunnerInterface
{
    /**
     * @var DataProvider\ProviderInterface
     */
    protected $dataProvider;

    /**
     * @param DataProvider\ProviderInterface $dataProvider
     */
    public function __construct(DataProvider\ProviderInterface $dataProvider)
    {
        $this->setDataProvider($dataProvider);
    }

    /**
     * @return DataProvider\ProviderInterface
     */
    public function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @param DataProvider\ProviderInterface $dataProvider
     */
    public function setDataProvider(DataProvider\ProviderInterface $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @return Processor\ProcessorInterface[]
     */
    abstract public function getRowsetProcessors();

    /**
     * @return Processor\ProcessorInterface[]
     */
    abstract public function getRowProcessors();

    /**
     * @param array $options
     */
    public function run(array $options = array())
    {
        $rows = $this->getDataProvider()->getRows();

        foreach ($this->getRowsetProcessors() as $processor) {
            $this->processRowset($processor, $rows);
        }

        foreach ($this->getRowProcessors() as $processor) {
            foreach ($rows as $row) {
                $this->processRow($processor, $row);
            }
        }
    }

    /**
     * @param Processor\ProcessorInterface $processor
     * @param array $rows
     */
    protected function processRowset(Processor\ProcessorInterface $processor, array $rows)
    {
        $processor->process($rows);
    }

    /**
     * @param Processor\ProcessorInterface $processor
     * @param array $row
     */
    protected function processRow(Processor\ProcessorInterface $processor, array $row)
    {
        $processor->process($row);
    }
}
