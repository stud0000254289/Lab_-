<?php

namespace SouleymanSidick\MyProject\Repositories;

use PDO;
use SouleymanSidick\MyProject\Like;

class LikesRepository implements LikesRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function save(Like $like): void
    {
        $stmt = $db->prepare("INSERT INTO likes (uuid, post_uuid, user_uuid) VALUES (:uuid, :post_uuid, :user_uuid)");
$stmt->execute([
    ':uuid' => $like->uuid,
    ':post_uuid' => $like->postUuid,
    ':user_uuid' => $like->userUuid,
]);

    }

    public function getByPostUuid(string $postUuid): array
    {
        $stmt = $this->db->prepare("SELECT * FROM likes WHERE post_uuid = :post_uuid");
        $stmt->execute([':post_uuid' => $postUuid]);

        $likes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $likes[] = new Like($row['uuid'], $row['post_uuid'], $row['user_uuid']);
        }

        return $likes;
    }
}
