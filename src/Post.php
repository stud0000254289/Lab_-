<?php
namespace SouleymanSidick\MyProject;

class Post {
    public string $uuid;
    public string $authorUuid;
    public string $title;
    public string $content;

    public function __construct(string $uuid, string $authorUuid, string $title, string $content) {
        $this->uuid = $uuid;
        $this->authorUuid = $authorUuid;
        $this->title = $title;
        $this->content = $content;
    }
}
