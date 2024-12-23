<?php

namespace SouleymanSidick\MyProject;

class Like
{
    public string $uuid;
    public string $postUuid;
    public string $userUuid;

    public function __construct(string $uuid, string $postUuid, string $userUuid)
    {
        $this->uuid = $uuid;
        $this->postUuid = $postUuid;
        $this->userUuid = $userUuid;
    }
}
