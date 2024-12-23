<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UUIDTest extends TestCase {
    public function testGenerateUuid(): void {
        $uuid = Uuid::uuid4()->toString();
        $this->assertTrue(Uuid::isValid($uuid));
    }
}