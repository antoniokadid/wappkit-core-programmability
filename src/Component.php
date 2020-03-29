<?php

namespace AntonioKadid\WAPPKitCore\Programmability;

/**
 * Class Component
 *
 * @package AntonioKadid\WAPPKitCore\Programmability
 */
class Component
{
    /** @var string */
    private $_name;
    /** @var string */
    private $_content;
    /** @var string|null */
    private $_className;
    /** @var array */
    private $_parameters;
    /** @var ExecutionContext|NULL */
    private $_context;

    /**
     * Component constructor.
     *
     * @param string                $name
     * @param array                 $parameters
     * @param string                $content
     * @param ExecutionContext|NULL $context
     */
    public function __construct(string $name, string $content = '', array $parameters = [], ExecutionContext $context = NULL)
    {
        $this->_name = $name;
        $this->_content = $content;
        $this->_className = $this->generateClassName();
        $this->_parameters = $parameters;
        $this->_context = $context;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->_content;
    }

    /**
     * @return string|null
     */
    public function getClassName(): ?string
    {
        return $this->_className;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->_parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->_parameters = $parameters;
    }

    /**
     * @return ExecutionContext|NULL
     */
    public function getContext(): ?ExecutionContext
    {
        return $this->_context;
    }

    /**
     * @param ExecutionContext|NULL $context
     */
    public function setContext(?ExecutionContext $context): void
    {
        $this->_context = $context;
    }

    /**
     * @return string|null
     */
    private function generateClassName(): ?string
    {
        $namespace = $this->extractNamespace();
        $className = $this->extractClassName();

        if ($className == NULL)
            return NULL;

        if ($namespace == NULL)
            return "\\{$className}";

        return "\\{$namespace}\\{$className}";
    }

    /**
     * @return string|null
     */
    private function extractNamespace(): ?string
    {
        if (empty($this->_content))
            return NULL;

        if (!preg_match('/\s*namespace\s+([^\s;]*);/i', $this->_content, $matches))
            return NULL;

        return $matches[1];
    }

    /**
     * @return string|null
     */
    private function extractClassName(): ?string
    {
        if (empty($this->_content))
            return NULL;

        if (!preg_match('/\s*class\s+([^\s\{]*)/i', $this->_content, $matches))
            return NULL;

        return $matches[1];
    }
}