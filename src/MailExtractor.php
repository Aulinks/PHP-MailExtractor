<?php

namespace Aulinks\MailExtractor;

use Nette\Utils\Finder;

/**
 *
 * @author David Kudera
 */
class MailExtractor
{


	/** @var \Aulinks\MailExtractor\TextExtractor */
	private $textExtractor;

	/** @var array */
	private $supportedFileTypes = ['pdf', 'txt', 'doc'];


	/** @var string */
	private $pattern = "((?:\"(?:[ !\\x23-\\x5B\\x5D-\\x7E]*|\\\\[ -~])+\"|[-a-z0-9!#$%&'*+/=?^_`{|}~]+(?:\\.[-a-z0-9!#$%&'*+/=?^_`{|}~]+)*)@(?:[0-9a-z\x80-\xFF](?:[-0-9a-z\x80-\xFF]{0,61}[0-9a-z\x80-\xFF])?\\.)+[a-z\x80-\xFF](?:[-0-9a-z\x80-\xFF]{0,17}[a-z\x80-\xFF])?)i";


	/**
	 * @return array
	 */
	public function getSupportedFileTypes()
	{
		return $this->supportedFileTypes;
	}


	/**
	 * @return \Aulinks\MailExtractor\TextExtractor
	 */
	private function getTextExtractor()
	{
		if (!$this->textExtractor) {
			$this->textExtractor = new TextExtractor;
		}

		return $this->textExtractor;
	}


	/**
	 * @param string $text
	 * @return array
	 */
	public function extractMails($text)
	{
		if (preg_match_all($this->pattern, $text, $matches) !== false && !empty($matches[0])) {
			return array_unique($matches[0]);
		}

		return null;
	}


	/**
	 * @param string $file
	 * @return array
	 */
	public function extractMailsFromFile($file)
	{
		$text = $this->getTextExtractor()->extractText($file);
		return $this->extractMails($text);
	}


	/**
	 * @param string $directory
	 * @param bool $recursive
	 * @return array
	 */
	public function extractMailsInDirectory($directory, $recursive = false)
	{
		$mask = array_map(function($ext) { return "*.$ext"; }, $this->getSupportedFileTypes());

		$finder = Finder::findFiles($mask);

		if ($recursive) {
			$finder->from($directory);
		} else {
			$finder->in($directory);
		}

		$mails = [];
		foreach ($finder as $file => $info) {
			if (($found = $this->extractMailsFromFile($file)) !== null) {
				$mails = array_merge($mails, $found);
			}
		}

		return array_unique($mails);
	}

}
