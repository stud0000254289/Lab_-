<?php

namespace SouleymanSidick\MyProject;

use Ramsey\Uuid\Uuid;

class User {
    public string $uuid;
    public string $username;
    public string $firstName;
    public string $lastName;

    public function __construct(string $username, string $firstName, string $lastName) {
        $this->uuid = Uuid::uuid4()->toString();
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}

