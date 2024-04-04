<?php

namespace App\Tests\Unit\Enity;

use App\Entity\Category;
use App\Entity\Project;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class categoryTest extends TestCase
{
    public function testCreateNewCategory(): void
    {
        $now = new DateTimeImmutable();
        $project = (new Project())
            ->setName('test project')
            ->setSlug('test-project')
            ->setupdatedAt($now)
            ->setcreatedAt($now);
        
        $category = (new Category())
            ->setName('test category')
            ->setSlug('test-category')
            ->setupdatedAt($now)
            ->setcreatedAt($now)
            ->setProject($project);

        self::assertSame('test category', $category->getName());
        self::assertSame('test-category', $category->getSlug());
        self::assertSame($now, $category->getupdatedAt());
        self::assertSame($now, $category->getCreatedAt());
        self::assertSame('test project', $category->getProject()->getName());
    }
}