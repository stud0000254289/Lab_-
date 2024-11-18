<?php

namespace SouleymanSidick\MyProject;

use Ramsey\Uuid\Uuid;

class Article {
    public string $uuid;
    public string $authorUuid;
    public string $title;
    public string $text;

    public function __construct(string $authorUuid, string $title, string $text) {
        $this->uuid = Uuid::uuid4()->toString();
        $this->authorUuid = $authorUuid;
        $this->title = $title;
        $this->text = $text;
    }
}

