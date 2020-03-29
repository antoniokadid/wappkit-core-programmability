<?php

namespace AntonioKadid\WAPPKitCore\Programmability;

/**
 * Interface IComponentImplementation
 *
 * @package AntonioKadid\WAPPKitCore\Programmability
 */
interface IComponentImplementation
{
    /**
     * @param array                 $parameters
     * @param ExecutionContext|NULL $context
     */
    function setData(array $parameters = [], ExecutionContext $context = NULL): void;

    /**
     * @return string
     */
    function generate(): string;
}