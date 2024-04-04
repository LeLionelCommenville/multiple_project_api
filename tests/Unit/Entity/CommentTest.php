<?php

namespace App\Tests\Unit\Enity;

use App\Entity\Comment;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class CommentTest extends TestCase
{
    public function testCreateNewComment(): void
    {
        $now = new DateTimeImmutable();
        $category = (new Category())
            ->setName('test category')
            ->setSlug('test-category')
            ->setupdatedAt($now)
            ->setcreatedAt($now);
        
        $comment = (new Comment())
            ->setCommentText('test commentaire un peut long avec des chiffres et des lettres $122345')
            ->setExternalId(1234)
            ->setupdatedAt($now)
            ->setcreatedAt($now)
            ->setCategory($category);

        self::assertSame('test commentaire un peut long avec des chiffres et des lettres $122345', $comment->getCommentText());
        self::assertSame(1234, $comment->getExternalId());
        self::assertSame($now, $category->getupdatedAt());
        self::assertSame($now, $category->getCreatedAt());
        self::assertSame('test category', $comment->getCategory()->getName());
    }
}