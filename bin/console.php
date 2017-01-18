<?php
/*
 * Command Line Interface for CI
 */

require_once 'vendor/autoload.php';

// load CI instance and helper class for ci on console command
$CI = require_once 'bin/ci_instance.php';
require_once __DIR__."/CIConsoleHelper.php";

/* load and inject data through helper set access from command class with 
* $this->getHelper('ci')->getInstance(); for ci instance
*/
$helperSet = null;
$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
	'db' 	=> new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($CI->doctrine->em->getConnection()),
	'em' 	=> new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($CI->doctrine->em),
	'dialog'=> new \Symfony\Component\Console\Helper\DialogHelper(),
	'ci' 	=> new CIConsoleHelper($CI)
));

$cli = new Symfony\Component\Console\Application('CMS Command Line Interface', \Doctrine\ORM\Version::VERSION);
$helperSet = ($helperSet) ?: new \Symfony\Component\Console\Helper\HelperSet();

$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);

$applicationCommands = array();
foreach (scandir(APPPATH.'commands') as $c) {
	if( $c != '.' && $c != '..'){
		$commandClass = '\commands\\'. str_replace(EXT, '', $c);
		$applicationCommands[] = new $commandClass();
	}
}

$cli->addCommands( $applicationCommands );
$cli->addCommands(array(
		// Migrations Commands
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
		new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
));
// Add orm console commands
\Doctrine\ORM\Tools\Console\ConsoleRunner::addCommands($cli);

$cli->run();
