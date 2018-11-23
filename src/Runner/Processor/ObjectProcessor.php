<?php

namespace Detail\Importing\Runner\Processor;

use Detail\Importing\Exception;

abstract class ObjectProcessor implements
    ProcessorInterface
{
    /**
     * @var string
     */
    protected $objectName;

    /**
     * @param string $objectName Full class name expected for each row
     */
    public function __construct($objectName = null)
    {
        if ($objectName !== null) {
            $this->setObjectName($objectName);
        }
    }

    /**
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     * @param string $objectName
     */
    public function setObjectName($objectName)
    {
        $this->objectName = $objectName;
    }

    /**
     * @param object $object
     */
    protected function verifyRow($object)
    {
        $objectName = $this->getObjectName();

        if ($objectName === null) {
            throw new Exception\RuntimeException(
                'Missing object name; please set one using setObjectName() before calling process()'
            );
        }

        if (!$object instanceof $objectName) {
            throw new Exception\InvalidArgumentException(
                sprintf('Invalid object; must be a %s object', $objectName)
            );
        }
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
