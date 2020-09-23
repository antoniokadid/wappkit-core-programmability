<?php

namespace AntonioKadid\WAPPKitCore\Programmability;

/**
 * Interface IExecutionContextProvider
 *
 * @package AntonioKadid\WAPPKitCore\Programmability
 */
interface IExecutionContextProvider
{
    function asExecutionContext(): ExecutionContext;
}