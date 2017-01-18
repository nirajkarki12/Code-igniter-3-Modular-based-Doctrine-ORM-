<?php
namespace fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixture extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{
		$group = new \models\Group();
		$group->setName('Admin');
		$group->setDescription('admin group');
		$group->setIsAdmin(1);
		$group->setStatus(1);

		$manager->persist($group);
		$this->addReference('admin_group', $group);

		$manager->flush();
		echo "\n'{$group->getName()}' - Group added successfully.\n";
	}

	public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}