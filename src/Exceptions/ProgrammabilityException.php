<?php

namespace AntonioKadid\WAPPKitCore\Programmability\Exceptions;

use AntonioKadid\WAPPKitCore\Programmability\ExecutionContext;
use Exception;
use Throwable;

/**
 * Class ProgrammabilityException
 *
 * @package AntonioKadid\WAPPKitCore\Programmability\Exceptions
 */
class ProgrammabilityException extends Exception
{
    /** @var ExecutionContext */
    private $_context;
    /** @var array */
    private $_parameters;

    /**
     * ProgrammabilityException constructor.
     *
     * @param string                $message
     * @param array                 $parameters
     * @param ExecutionContext|null $context
     * @param Throwable|NULL        $previous
     */
    public function __construct(string $message = '', array $parameters = [], ?ExecutionContext $context = NULL, Throwable $previous = NULL)
    {
        parent::__construct($message, 0, $previous);

        $this->_context = $context;
        $this->_parameters = $parameters;
    }

    /**
     * @return ExecutionContext|null
     */
    public function getContext(): ?ExecutionContext
    {
        return $this->_context;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->_parameters;
    }
}