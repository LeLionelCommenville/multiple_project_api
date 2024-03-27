<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProjectFixtures extends Fixture
{
    public function __construct(private readonly SluggerInterface $slugger) {

    }
    public function load(ObjectManager $manager): void
    {
        $projects_names = [
            'Movies list',
            'Books list',
            'Games List'
        ];
        foreach($projects_names as $id => $project_name) {
            $project = (new Project())
                ->setName($project_name)
                ->setSlug($this->slugger->slug($project_name))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
            $this->addReference('project_'.$id, $project);
            $manager->persist($project);
        }
        $manager->flush();
    }
}
