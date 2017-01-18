<?php
namespace fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class OptionsFixture extends AbstractFixture implements OrderedFixtureInterface
{
	public function load(ObjectManager $manager){

		$yaml = new \Symfony\Component\Yaml\Parser();

		$options = array();
		$config = $yaml->parse(file_get_contents(__DIR__.'/options_config.yml'));
		if(array_key_exists('options', $config)){
			$options = $config['options'];
		}else{ return; }

		if( is_array($options) &&  !empty($options) ){
			foreach ($options as $name => $value) {
				$opt = new \models\Common\Options();
				$opt->setName($name);
				$opt->setValue($value);
				$opt->setAutoload(1);

				$manager->persist($opt);
			}
		}
		
		$manager->flush();
		echo "\nInitial Options added successfully.\n";
	}

	public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}