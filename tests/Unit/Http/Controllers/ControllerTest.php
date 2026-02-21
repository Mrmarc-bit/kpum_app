<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\Controller;
use ReflectionClass;

class ControllerTest extends TestCase
{
    public function testControllerClassExists(): void
    {
        $this->assertTrue(class_exists(Controller::class));
    }

    public function testControllerClassIsAbstract(): void
    {
        $reflection = new ReflectionClass(Controller::class);
        $this->assertTrue($reflection->isAbstract());
    }

    public function testControllerClassHasNoPublicMethods(): void
    {
        $reflection = new ReflectionClass(Controller::class);
        $this->assertEmpty($reflection->getMethods(ReflectionMethod::IS_PUBLIC));
    }

    public function testControllerClassHasNoProperties(): void
    {
        $reflection = new ReflectionClass(Controller::class);
        $this->assertEmpty($reflection->getProperties());
    }

    public function testControllerClassIsNotFinal(): void
    {
        $reflection = new ReflectionClass(Controller::class);
        $this->assertFalse($reflection->isFinal());
    }
}