<?php

namespace AntonioKadid\WAPPKitCore\Tests\Programmability;

use AntonioKadid\WAPPKitCore\Programmability\Component;
use PHPUnit\Framework\TestCase;

final class ComponentTest extends TestCase
{
    public function testClassName(): void
    {
        $code = <<<EOT
<?php
namespace AntonioKadid\Test;
class TestClass
{
}
EOT;
        $component = new Component('TheName', $code);
        $this->assertEquals('\\AntonioKadid\\Test\\TestClass', $component->getClassName());

        $code = <<<EOT
<?php
class TestClass
{
}
EOT;

        $component = new Component('TheName', $code);
        $this->assertEquals('\\TestClass', $component->getClassName());

        $code = <<<EOT
<?php
namespace AntonioKadid\Test;
EOT;

        $component = new Component('TheName', $code);
        $this->assertNull($component->getClassName());
    }
}