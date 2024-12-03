<?php

namespace SouleymanSidick\MyProject\Repositories;

use SouleymanSidick\MyProject\Comment;
use Ramsey\Uuid\UuidInterface;
use PDO;

class CommentsRepository implements CommentsRepositoryInterface {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function get(UuidInterface $uuid): ?Comment {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid->toString()]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$data) {
            throw new \Exception("Comment not found");
        }
    
        // Создание объекта Comment с корректными данными из базы
        return new Comment(
            $data['uuid'],
            $data['post_uuid'],
            $data['author_uuid'],
            $data['text'] // Убедитесь, что используется правильное поле из базы
        );
    }
    
    

   

    public function save(Comment $comment): void {
       // Пример сохранения статьи
$stmt = $db->prepare("INSERT INTO posts (uuid, title, text) VALUES (:uuid, :title, :text)");
$stmt->execute([
    ':uuid' => $article->uuid,
    ':title' => $article->title,
    ':text' => $article->text,
]);

// Пример выборки статей
$stmt = $db->query("SELECT title FROM posts");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    echo "Заголовок: " . $post['title'] . "<br>";
}

       
    }
    
    
    
    
}
