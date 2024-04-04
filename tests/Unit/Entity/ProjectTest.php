<?php

namespace App\Tests\Unit\Enity;

use App\Entity\Project;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

class ProjectTest extends TestCase
{
    public function testCreateNewProject(): void
    {
        $now = new DateTimeImmutable();
        $project = (new Project())
            ->setName('test project')
            ->setSlug('test-project')
            ->setupdatedAt($now)
            ->setcreatedAt($now);

        self::assertSame('test project', $project->getName());
        self::assertSame('test-project', $project->getSlug());
        self::assertSame($now, $project->getupdatedAt());
        self::assertSame($now, $project->getCreatedAt());
    }

}