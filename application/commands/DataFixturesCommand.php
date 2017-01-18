<?php

namespace commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use InvalidArgumentException;

/**
 * Load data fixtures from modules.
 */
class DataFixturesCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:fixtures:load')
            ->setDescription('Load data fixtures to your database.')
            ->addOption('fixtures', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory or file to load data fixtures from.')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getHelper('em')->getEntityManager();

        if ($input->isInteractive() && !$input->getOption('append')) {
            $dialog = $this->getHelperSet()->get('dialog');
            if (!$dialog->askConfirmation($output, '<question>Careful, database will be purged. Do you want to continue Y/N ?</question>', false)) {
                return;
            }
        }

        $dirOrFile = $input->getOption('fixtures');
        if ($dirOrFile) {
            $paths = is_array($dirOrFile) ? $dirOrFile : array($dirOrFile);
        } else {
            $paths = array();
            $paths[] = APPPATH.'fixtures';
        }

        $loader = new Loader();
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            }
        }
        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
            );
        }

        $purger = new ORMPurger($em);
        // $purger->setPurgeMode($input->getOption('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($em, $purger);
        $executor->setLogger(function($message) use ($output) {
            $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        
        $executor->execute($fixtures, $input->getOption('append'));
    }
}
