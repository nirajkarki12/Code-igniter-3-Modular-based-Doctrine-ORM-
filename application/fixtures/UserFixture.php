<?php
namespace fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager)
	{

		$user = new \models\User();
		$user->setUsername('superadmin');
		$user->setPassword(password_hash('123456', PASSWORD_BCRYPT));
		$user->setFirstName('Niraj');
		$user->setLastName('Karki');
		$user->setEmail('nirajkarki12@gmail.com');
		$user->setAddress('Baneshwor, Kathmandu');
		$user->setPhone('014205013');
		$user->setMobile('9841620122');
		$user->setIsAdmin(1);
		$user->setLastLogged(null);
		$user->setStatus(1);
		$user->setFirstLogin(0);
		$user->addGroup($this->getReference('admin_group'));

		$manager->persist($user);
		
		$manager->flush();
		echo "\n'{$user->getUsername()}' - User added successfully.\n";
	}

	public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}