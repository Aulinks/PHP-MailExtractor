<?php

/**
 * Test: Aulinks\MailExtractor\TextExtractor
 *
 * @testCase AulinksTests\MailExtractor\TextExtractorTest
 * @author David Kudera
 */

namespace AulinksTests\MailExtractor;

use Aulinks\MailExtractor\TextExtractor;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

/**
 *
 * @author David Kudera
 */
class TextExtractorTest extends TestCase
{


	/** @var \Aulinks\MailExtractor\TextExtractor */
	private $extractor;


	public function setUp()
	{
		$this->extractor = new TextExtractor;
	}


	public function tearDown()
	{
		$this->extractor = null;
	}


	public function testExtractText_unknownFileType()
	{
		Assert::exception(function() {
			$this->extractor->extractText(__DIR__. '/files/invalid.gif');
		}, 'Aulinks\MailExtractor\TextExtractorException', 'Could not extract text from file '. __DIR__. '/files/invalid.gif.');
	}


	public function testExtractText_txt()
	{
		$content = $this->extractor->extractText(__DIR__. '/files/simple.txt');

		Assert::contains('test@test.com', $content);
	}


	public function testExtractText_pdf()
	{
		$content = $this->extractor->extractText(__DIR__. '/files/simple.pdf');

		Assert::contains('test@test.com', $content);
	}

}


run(new TextExtractorTest);