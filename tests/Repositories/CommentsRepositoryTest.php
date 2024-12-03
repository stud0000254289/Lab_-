<?php

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use SouleymanSidick\MyProject\Repositories\CommentsRepository;
use SouleymanSidick\MyProject\Comment;
use Ramsey\Uuid\Uuid;
use PDO;

class CommentsRepositoryTest extends TestCase
{
    private PDO $db;
    private CommentsRepository $repository;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Создаём таблицу "comments"
        $this->db->exec("CREATE TABLE comments (
            uuid TEXT PRIMARY KEY,
            post_uuid TEXT,
            author_uuid TEXT,
            text TEXT
        )");

        $this->repository = new CommentsRepository($this->db);
    }

    public function testSaveComment(): void {
        $comment = new Comment(
            Uuid::uuid4()->toString(),
            'post-uuid',
            'author-uuid',
            'Test Comment'
        );
    
        $this->repository->save($comment);
    
        $stmt = $this->db->query("SELECT * FROM comments");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->assertNotEmpty($result);
        $this->assertEquals('Test Comment', $result['text']); // Проверяем "text"
    }
    
    
    

    public function testFindCommentByUuid(): void {
        $uuid = Uuid::uuid4()->toString();
    
        $this->db->exec("INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (
            '$uuid', 'post-uuid', 'author-uuid', 'Test Comment'
        )");
    
        $comment = $this->repository->get(Uuid::fromString($uuid));
    
        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('Test Comment', $comment->text); // Проверяем "text"
    }
    
    
    

    public function testFindCommentThrowsExceptionIfNotFound(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Comment not found");
    
        $this->repository->get(Uuid::uuid4());
    }
    
}
