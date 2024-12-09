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

    // Получение комментария по UUID
    public function get(UuidInterface $uuid): ?Comment {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid->toString()]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$data) {
            throw new \Exception("Comment not found");
        }
    
        // Возврат объекта Comment
        return new Comment(
            $data['uuid'],
            $data['post_uuid'],
            $data['author_uuid'],
            $data['text']
        );
    }

    // Сохранение комментария
    public function save(Comment $comment): void {
        $stmt = $this->db->prepare("
            INSERT INTO comments (uuid, post_uuid, author_uuid, text) 
            VALUES (:uuid, :post_uuid, :author_uuid, :text)
        ");
        $stmt->execute([
            ':uuid' => $comment->uuid,
            ':post_uuid' => $comment->postUuid,
            ':author_uuid' => $comment->authorUuid,
            ':text' => $comment->text
        ]);
    }
    
    
    


    // Получение всех комментариев к статье
    public function getAllByPost(UuidInterface $postUuid): array {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE post_uuid = :post_uuid");
        $stmt->execute([':post_uuid' => $postUuid->toString()]);
        $commentsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $comments = [];
        foreach ($commentsData as $data) {
            $comments[] = new Comment(
                $data['uuid'],
                $data['post_uuid'],
                $data['author_uuid'],
                $data['text']
            );
        }

        return $comments;
    }
}

