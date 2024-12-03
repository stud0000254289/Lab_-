<?php

namespace SouleymanSidick\MyProject;

class User {
    public string $uuid;
    public string $name;
    public string $email;

    public function __construct(string $uuid, string $name, string $email) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->email = $email;
    }
}



