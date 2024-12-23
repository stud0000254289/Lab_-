<?php

namespace SouleymanSidick\MyProject;

use Ramsey\Uuid\Uuid;

class Comment {
    public string $uuid;
    public string $postUuid;
    public string $authorUuid;
    public string $text;

    public function __construct(string $uuid, string $postUuid, string $authorUuid, string $text) {
        if (!Uuid::isValid($uuid)) {
            throw new \Exception("Invalid UUID format");
        }

        $this->uuid = $uuid;
        $this->postUuid = $postUuid;
        $this->authorUuid = $authorUuid;
        $this->text = $text;
    }
}


