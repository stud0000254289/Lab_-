<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use SouleymanSidick\MyProject\Comment;

class CommentTest extends TestCase {
    public function testCommentProperties(): void {
        $comment = new Comment('uuid', 'post-uuid', 'author-uuid', 'Test Comment');
        $this->assertEquals('uuid', $comment->uuid);
        $this->assertEquals('post-uuid', $comment->postUuid);
        $this->assertEquals('author-uuid', $comment->authorUuid);
        $this->assertEquals('Test Comment', $comment->text);
    }
}
