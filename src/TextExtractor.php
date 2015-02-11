<?php

namespace Aulinks\MailExtractor;

use Carrooi\PdfExtractor\PdfExtractor;

/**
 *
 * @author David Kudera
 */
class TextExtractor
{


	/** @var \Carrooi\PdfExtractor\PdfExtractor */
	private $pdfExtractor;

	/** @var array */
	private $mimes = [
		'pdf' => 'application/pdf',
		'txt' => 'text/plain',
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
			case 'application/pdf': return $this->getPdfExtractor()->extractText($file); break;
			case 'text/plain': return file_get_contents($file); break;
		}

		throw new TextExtractorException('Could not extract text from file '. $file. '.');
	}

}
