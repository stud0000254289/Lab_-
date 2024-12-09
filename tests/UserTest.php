<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use SouleymanSidick\MyProject\User;

class UserTest extends TestCase {
    public function testUserProperties(): void {
        $user = new User('uuid', 'John Doe', 'john.doe@example.com');
        $this->assertEquals('uuid', $user->uuid);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john.doe@example.com', $user->email);
    }
}
