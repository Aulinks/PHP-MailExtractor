<?php

/**
 * Test: Aulinks\MailExtractor\MailExtractor
 *
 * @testCase AulinksTests\MailExtractor\MailExtractorTest
 * @author David Kudera
 */

namespace AulinksTests\MailExtractor;

use Aulinks\MailExtractor\MailExtractor;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class MailExtractorTest extends TestCase
{


	/** @var \Aulinks\MailExtractor\MailExtractor */
	private $extractor;


	public function setUp()
	{
		$this->extractor = new MailExtractor;
	}


	public function tearDown()
	{
		$this->extractor = null;
	}


	public function testExtractMails_noMails()
	{
		$mails = $this->extractor->extractMails('');

		Assert::null($mails);
	}


	public function testExtractMails_duplicates()
	{
		$mails = $this->extractor->extractMails("test@test.com test@test.com");

		Assert::count(1, $mails);
		Assert::contains('test@test.com', $mails);
	}


	public function testExtractMailsFromFile_pdf()
	{
		$mails = $this->extractor->extractMailsFromFile(__DIR__. '/files/simple.pdf');

		Assert::count(2, $mails);
		Assert::contains('test@test.com', $mails);
		Assert::contains('lorem@ipsum.com', $mails);
	}


	public function testExtractMailsFromFile_txt()
	{
		$mails = $this->extractor->extractMailsFromFile(__DIR__. '/files/simple.txt');

		Assert::count(2, $mails);
		Assert::contains('test@test.com', $mails);
		Assert::contains('lorem@ipsum.com', $mails);
	}


	public function testExtractMailsFromFile_doc()
	{
		$mails = $this->extractor->extractMailsFromFile(__DIR__. '/files/simple.doc');

		Assert::count(10, $mails);
		Assert::contains('tellus@arcu.co.uk', $mails);
	}


	public function testExtractMailsFromFile_docx()
	{
		$mails = $this->extractor->extractMailsFromFile(__DIR__. '/files/simple.docx');

		Assert::count(2, $mails);
		Assert::contains('test@test.com', $mails);
		Assert::contains('lorem@ipsum.com', $mails);
	}


	public function testExtractMailsInDirectory()
	{
		$mails = $this->extractor->extractMailsInDirectory(__DIR__. '/files');

		Assert::count(13, $mails);
		Assert::contains('test@test.com', $mails);
		Assert::contains('lorem@ipsum.com', $mails);
		Assert::contains('ipsum@lorem.com', $mails);
		Assert::contains('tellus@arcu.co.uk', $mails);
	}


	public function testExtractMailsInDirectory_recursive()
	{
		$mails = $this->extractor->extractMailsInDirectory(__DIR__. '/files', true);

		Assert::count(14, $mails);
		Assert::contains('test@test.com', $mails);
		Assert::contains('lorem@ipsum.com', $mails);
		Assert::contains('ipsum@lorem.com', $mails);
		Assert::contains('info@aulinks.cz', $mails);
		Assert::contains('tellus@arcu.co.uk', $mails);
	}

}


run(new MailExtractorTest);