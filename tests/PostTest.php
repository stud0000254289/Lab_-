<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use SouleymanSidick\MyProject\Post;

class PostTest extends TestCase {
    public function testPostProperties(): void {
        $post = new Post('uuid', 'author-uuid', 'Test Title', 'Test Content');
        $this->assertEquals('uuid', $post->uuid);
        $this->assertEquals('author-uuid', $post->authorUuid);
        $this->assertEquals('Test Title', $post->title);
        $this->assertEquals('Test Content', $post->content);
    }
}