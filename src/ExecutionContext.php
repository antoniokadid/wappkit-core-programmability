<?php

namespace AntonioKadid\WAPPKitCore\Programmability;

use AntonioKadid\WAPPKitCore\Collections\Map;

/**
 * Class ExecutionContext
 *
 * @package AntonioKadid\WAPPKitCore\Programmability
 */
class ExecutionContext extends Map
{
    /** @var ExecutionContext */
    private $_parentContext = NULL;

    /**
     * ExecutionContext constructor.
     *
     * @param array                 $parameters
     * @param ExecutionContext|null $parentContext
     */
    public function __construct(array $parameters, ?ExecutionContext $parentContext = NULL)
    {
        parent::__construct($parameters);

        $this->_parentContext = $parentContext;
    }

    /**
     * @return ExecutionContext|null
     */
    public function getParentContext(): ?ExecutionContext
    {
        return $this->_parentContext;
    }
}