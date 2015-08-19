<?php

namespace Detail\Importing\Runner;

class DataProviderBasedRunner implements
    RunnerInterface
{
    const RUN_OPTION_PROVIDER_ARGUMENTS = 'provider_arguments';
    const RUN_OPTION_PROCESSOR_OPTIONS = 'processor_options';

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
     * @return Processor\ProcessorInterface[]
     */
    public function getRowsetProcessors()
    {
        return $this->rowsetProcessors;
    }

    /**
     * @param Processor\ProcessorInterface[] $rowsetProcessors
     */
    public function setRowsetProcessors(array $rowsetProcessors)
    {
        $this->rowsetProcessors = $rowsetProcessors;
    }

    /**
     * @return Processor\ProcessorInterface[]
     */
    public function getRowProcessors()
    {
        return $this->rowProcessors;
    }

    /**
     * @param Processor\ProcessorInterface[] $rowProcessors
     */
    public function setRowProcessors(array $rowProcessors)
    {
        $this->rowProcessors = $rowProcessors;
    }

    /**
     * @return Processor\ProcessorInterface[]
     */
    public function getProcessors()
    {
        return array_merge($this->getRowsetProcessors(), $this->getRowProcessors());
    }

    /**
     * @param array $options
     */
    public function run(array $options = array())
    {
        $rows = $this->getDataProvider()->getRows(
            $this->getOption($options, self::RUN_OPTION_PROVIDER_ARGUMENTS)
        );

        $processorOptions = $this->getOption($options, self::RUN_OPTION_PROCESSOR_OPTIONS, array());

        foreach ($this->getRowsetProcessors() as $processor) {
            $this->processRowset($processor, $rows, $processorOptions);
        }

        foreach ($this->getRowProcessors() as $processor) {
            foreach ($rows as $row) {
                $this->processRow($processor, $row, $processorOptions);
            }
        }
    }

    /**
     * @param Processor\ProcessorInterface $processor
     * @param array $rows
     * @param array $options
     */
    protected function processRowset(Processor\ProcessorInterface $processor, array $rows, array $options = array())
    {
        $processor->process($rows, $options);
    }

    /**
     * @param Processor\ProcessorInterface $processor
     * @param array|object $row
     * @param array $options
     */
    protected function processRow(Processor\ProcessorInterface $processor, $row, array $options = array())
    {
        $processor->process($row, $options);
    }

    /**
     * @param array $options
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    protected function getOption(array $options, $name, $default = null)
    {
        return array_key_exists($name, $options) ? $options[$name] : $default;
    }
}
