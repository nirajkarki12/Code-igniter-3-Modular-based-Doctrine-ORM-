<?php
namespace commands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;

class DbInitCommand extends Command
{
	protected function configure(){
		$this->ignoreValidationErrors();
		
		$this
		->setName('db:init')
		->setDefinition(array(
				new InputArgument('command_name', InputArgument::OPTIONAL, 'The command name', 'help')
		))
		->setDescription('Initializes database for a project')
		;
	}
	
	
	protected function execute(InputInterface $input, OutputInterface $output){
		$output->writeln("Initializing Database...");
		
		$em = $this->getHelper('em')->getEntityManager();
		$connection = $em->getConnection();
		
		$output->writeln("Creating a diff.");
		
		$command = $this->getApplication()->find('migrations:diff');
	
		$returnCode = $command->run($input, $output);
		
		$output->writeln("Diff Created.");
		$output->writeln("Migrating Database to latest version.");
		
		$this->getApplication()->find('migrations:migrate')->run($input, $output);
		$output->writeln("Database migrated to latest version.");
	}
}
