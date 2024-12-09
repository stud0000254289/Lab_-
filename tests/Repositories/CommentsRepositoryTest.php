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
        // Подключение к базе данных SQLite в памяти
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Создание таблицы "comments"
        $this->db->exec("CREATE TABLE comments (
            uuid TEXT PRIMARY KEY,
            post_uuid TEXT,
            author_uuid TEXT,
            text TEXT
        )");

        $this->repository = new CommentsRepository($this->db);
    }

    public function testSaveComment(): void
    {
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
        $this->assertEquals($comment->uuid, $result['uuid']);
        $this->assertEquals($comment->postUuid, $result['post_uuid']);
        $this->assertEquals($comment->authorUuid, $result['author_uuid']);
        $this->assertEquals($comment->text, $result['text']);
    }

    public function testFindCommentByUuid(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $this->db->exec("INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (
            '$uuid', 'post-uuid', 'author-uuid', 'Test Comment'
        )");

        $comment = $this->repository->get(Uuid::fromString($uuid));

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals($uuid, $comment->uuid);
        $this->assertEquals('post-uuid', $comment->postUuid);
        $this->assertEquals('author-uuid', $comment->authorUuid);
        $this->assertEquals('Test Comment', $comment->text);
    }
    public function testInvalidUuidFormat(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid UUID format");
    
        // Пытаемся создать комментарий с некорректным UUID
        $comment = new Comment('invalid-uuid', 'post-uuid', 'author-uuid', 'Test');
        $this->repository->save($comment);
    }
    
    public function testFindCommentThrowsExceptionIfNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Comment not found");

        $this->repository->get(Uuid::uuid4());
    }

    public function testGetAllCommentsByPost(): void
    {
        $postUuid = Uuid::uuid4()->toString();

        $this->db->exec("INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (
            '" . Uuid::uuid4()->toString() . "', '$postUuid', 'author-uuid-1', 'Comment 1'
        )");
        $this->db->exec("INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (
            '" . Uuid::uuid4()->toString() . "', '$postUuid', 'author-uuid-2', 'Comment 2'
        )");

        $comments = $this->repository->getAllByPost(Uuid::fromString($postUuid));

        $this->assertCount(2, $comments);
        $this->assertEquals('Comment 1', $comments[0]->text);
        $this->assertEquals('Comment 2', $comments[1]->text);
    }
}
