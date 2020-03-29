<?php

namespace AntonioKadid\WAPPKitCore\Tests\Programmability;

use AntonioKadid\WAPPKitCore\Programmability\CodeEvaluator;
use PHPUnit\Framework\TestCase;

class CodeEvaluatorTest extends TestCase
{
    public function testEvaluate(): void
    {
        $this->assertFalse(function_exists('myFunctionName'));
        $code = <<<EOT
<?php
function myFunctionName()
{
}
EOT;
        CodeEvaluator::evaluate($code);

        $this->assertTrue(function_exists('myFunctionName'));
    }
}