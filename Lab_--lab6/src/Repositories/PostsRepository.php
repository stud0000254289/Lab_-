<?php

namespace SouleymanSidick\MyProject\Repositories;

use SouleymanSidick\MyProject\Article;
use Ramsey\Uuid\UuidInterface;
use PDO;

class PostsRepository implements PostsRepositoryInterface {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function get(UuidInterface $uuid): ?Article {
        $stmt = $this->db->prepare("SELECT * FROM posts WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid->toString()]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    

        if (!$data) {
            throw new \Exception("Article not found");
        }
    
        return new Article($data['uuid'], $data['author_uuid'], $data['title'], $data['text']);
    }
    
    


    public function save(Article $article): void {
        $stmt = $this->db->prepare("INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text)");
        $stmt->execute([
            ':uuid' => $article->uuid,
            ':author_uuid' => $article->authorUuid,
            ':title' => $article->title,
            ':text' => $article->text
        ]);
    }
}
