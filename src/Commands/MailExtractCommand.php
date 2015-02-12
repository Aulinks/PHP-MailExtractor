<?php

namespace Aulinks\MailExtractor\Commands;

use Aulinks\MailExtractor\InvalidPathException;
use Aulinks\MailExtractor\MailExtractor;
use Aulinks\MailExtractor\PathNotFoundException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 * @author David Kudera
 */
class MailExtractCommand extends Command
{


	public function configure()
	{
		$this
			->setName('mail-extract')
			->setDescription('Extract all email addresses from files')
			->addArgument('path', InputArgument::REQUIRED, 'Path to file or directory with email addresses')
			->addOption('recursive', null, InputOption::VALUE_NONE, 'Search directory recursively?');
	}


	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int
	 */
	public function execute(InputInterface $input, OutputInterface $output)
	{
		$_path = $input->getArgument('path');
		if (($path = realpath($_path)) === false) {		// just to be sure
			$path = getcwd(). DIRECTORY_SEPARATOR. $_path;
			if (($path = realpath($path)) === false) {
				throw new PathNotFoundException('Could not find '. $_path. ' path.');
			}
		}

		$extractor = new MailExtractor;

		if (is_file($path)) {
			$mails = $extractor->extractMailsFromFile($path);
		} elseif (is_dir($path)) {
			$recursive = (bool) $input->getOption('recursive');
			$mails = $extractor->extractMailsFromDirectory($path, $recursive);
		} else {
			throw new InvalidPathException('Could not process '. $path. ' path.');
		}

		if (($count = count($mails)) === 0) {
			$output->writeln('<comment>Could not find any email address in ' . $path . '</comment>');
		} elseif ($count === 1) {
			$output->writeln('<info>Found one email address in '. $path. '</info>:');
		} else {
			$output->writeln('<info>Found '. $count. ' email addresses in '. $path. '</info>:');
		}

		foreach ($mails as $mail) {
			$output->writeln($mail);
		}

		return 0;
	}

}
