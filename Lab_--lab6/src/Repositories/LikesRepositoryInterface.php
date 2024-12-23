<?php

namespace SouleymanSidick\MyProject\Repositories;

use SouleymanSidick\MyProject\Like;

interface LikesRepositoryInterface
{
    public function save(Like $like): void;

    public function getByPostUuid(string $postUuid): array;
}
