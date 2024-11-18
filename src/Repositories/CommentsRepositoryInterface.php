<?php

namespace SouleymanSidick\MyProject\Repositories;

use SouleymanSidick\MyProject\Comment;
use Ramsey\Uuid\UuidInterface;

interface CommentsRepositoryInterface {
    public function get(UuidInterface $uuid): ?Comment;
    public function save(Comment $comment): void;
}
