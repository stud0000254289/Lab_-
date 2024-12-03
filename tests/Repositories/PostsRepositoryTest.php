<?php

namespace Tests\Repositories;

use PHPUnit\Framework\TestCase;
use SouleymanSidick\MyProject\Repositories\PostsRepository;
use SouleymanSidick\MyProject\Article;
use Ramsey\Uuid\Uuid;
use PDO;

class PostsRepositoryTest extends TestCase
{
    private PDO $db;
    private PostsRepository $repository;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite::memory:');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Создаём таблицу "posts"
        $this->db->exec("CREATE TABLE posts (
            uuid TEXT PRIMARY KEY,
            author_uuid TEXT,
            title TEXT,
            text TEXT
        )");

        $this->repository = new PostsRepository($this->db);
    }

    public function testSaveArticle(): void
    {
        $article = new Article(Uuid::uuid4()->toString(), 'author-uuid', 'Test Title', 'Test Text');
        $this->repository->save($article);

        $stmt = $this->db->query("SELECT * FROM posts");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result);
        $this->assertEquals('Test Title', $result['title']);
    }

    public function testFindArticleByUuid(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $this->db->exec("INSERT INTO posts (uuid, author_uuid, title, text) VALUES (
            '$uuid', 'author-uuid', 'Test Title', 'Test Text'
        )");

        $article = $this->repository->get(Uuid::fromString($uuid));

        $this->assertInstanceOf(Article::class, $article);
        $this->assertEquals('Test Title', $article->title);
    }

    public function testFindArticleThrowsExceptionIfNotFound(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Article not found");
    
        $this->repository->get(Uuid::uuid4());
    }
    
}
