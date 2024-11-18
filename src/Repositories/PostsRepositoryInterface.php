<?php

namespace SouleymanSidick\MyProject\Repositories;

use SouleymanSidick\MyProject\Article;
use Ramsey\Uuid\UuidInterface;

interface PostsRepositoryInterface {
    public function get(UuidInterface $uuid): ?Article;
    public function save(Article $article): void;
}

