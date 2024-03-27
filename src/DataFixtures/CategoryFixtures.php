<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger) {

    }

    public function load(ObjectManager $manager): void
    {
        $Categories = [
            ['category_name' => 'Favorites Movies', 'project_id' => 1],
            ['category_name' => 'Favorites Actors', 'project_id' => 1], 
            ['category_name' => 'Favorites Books', 'project_id' => 2],
            ['category_name' => 'Favorites Games', 'project_id' => 3]
        ];

        foreach ($Categories as $category) {
            $category = (new Category())
                ->setName($category['category_name'])
                ->setSlug($this->slugger->slug($category['category_name']))
                ->setCreatedAt(new \DateTimeImmutable())                
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setProject($this->getReference('project_' . $category['project_id']));
            $manager->persist($category);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class
        ];
    }
}
