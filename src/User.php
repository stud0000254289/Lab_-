<?php

namespace SouleymanSidick\MyProject;

class User {
    public $id;
    public $firstName;
    public $lastName;

    public function __construct($id, $firstName, $lastName) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
