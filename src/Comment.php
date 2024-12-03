<?php

namespace SouleymanSidick\MyProject;

use Ramsey\Uuid\Uuid;

class Comment {
    public string $uuid;
    public string $postUuid;
    public string $authorUuid;
    public string $text;

    public function __construct(string $postUuid, string $authorUuid, string $text) {
        $this->uuid = Uuid::uuid4()->toString();
        $this->postUuid = $postUuid;
        $this->authorUuid = $authorUuid;
        $this->text = $text;
    }
}

