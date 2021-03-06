<?php

namespace Aulinks\MailExtractor;

use Carrooi\DocExtractor\DocExtractor;
use Carrooi\DocxExtractor\DocxExtractor;
use Carrooi\PdfExtractor\PdfExtractor;

/**
 *
 * @author David Kudera
 */
class TextExtractor
{


	/** @var \Carrooi\PdfExtractor\PdfExtractor */
	private $pdfExtractor;

	/** @var \Carrooi\DocExtractor\DocExtractor */
	private $docExtractor;

	/** @var \Carrooi\DocxExtractor\DocxExtractor */
	private $docxExtractor;

	/** @var array */
	private $mimes = [
		'pdf' => 'application/pdf',
		'txt' => 'text/plain',
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	];


	/**
	 * @return \Carrooi\PdfExtractor\PdfExtractor
	 */
	private function getPdfExtractor()
	{
		if (!$this->pdfExtractor) {
			$this->pdfExtractor = new PdfExtractor;
		}

		return $this->pdfExtractor;
	}


	/**
	 * @return \Carrooi\DocExtractor\DocExtractor
	 */
	private function getDocExtractor()
	{
		if (!$this->docExtractor) {
			$this->docExtractor = new DocExtractor;
		}

		return $this->docExtractor;
	}


	/**
	 * @return \Carrooi\DocxExtractor\DocxExtractor
	 */
	private function getDocxExtractor()
	{
		if (!$this->docxExtractor) {
			$this->docxExtractor = new DocxExtractor;
		}

		return $this->docxExtractor;
	}


	/**
	 * @param string $file
	 * @return string
	 */
	public function extractText($file)
	{
		$type = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $file);

		if ($type === 'text/plain') {			// mime types can be mistaken
			$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

			if (!isset($this->mimes[$ext])) {
				throw new TextExtractorException('Could not extract text from file '. $file. '.');
			}

			$type = $this->mimes[$ext];
		}

		switch ($type) {
			case 'application/pdf':
				return $this->getPdfExtractor()->extractText($file);
				break;
			case 'application/msword':
				return $this->getDocExtractor()->extractText($file);
				break;
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
				return $this->getDocxExtractor()->extractText($file);
				break;
			case 'text/plain':
				return file_get_contents($file);
				break;
		}

		throw new TextExtractorException('Could not extract text from file '. $file. '.');
	}

}
