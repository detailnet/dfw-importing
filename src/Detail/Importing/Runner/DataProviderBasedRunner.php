<?php

namespace Detail\Importing\Runner;

class DataProviderBasedRunner implements
    RunnerInterface
{
    /**
     * @var DataProvider\ProviderInterface
     */
    protected $dataProvider;

    /**
     * @var array
     */
    protected $rowsetProcessors = array();

    /**
     * @var array
     */
    protected $rowProcessors = array();

    /**
     * @param DataProvider\ProviderInterface $dataProvider
     * @param array $rowsetProcessors
     * @param array $rowProcessors
     */
    public function __construct(
        DataProvider\ProviderInterface $dataProvider,
        array $rowsetProcessors = array(),
        array $rowProcessors = array()
    ) {
        $this->setDataProvider($dataProvider);
        $this->setRowsetProcessors($rowsetProcessors);
        $this->setRowProcessors($rowProcessors);
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
     * @return array
     */
    public function getRowsetProcessors()
    {
        return $this->rowsetProcessors;
    }

    /**
     * @param array $rowsetProcessors
     */
    public function setRowsetProcessors(array $rowsetProcessors)
    {
        $this->rowsetProcessors = $rowsetProcessors;
    }

    /**
     * @return array
     */
    public function getRowProcessors()
    {
        return $this->rowProcessors;
    }

    /**
     * @param array $rowProcessors
     */
    public function setRowProcessors(array $rowProcessors)
    {
        $this->rowProcessors = $rowProcessors;
    }

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
     * @param array|object $row
     */
    protected function processRow(Processor\ProcessorInterface $processor, $row)
    {
        $processor->process($row);
    }
}
