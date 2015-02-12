# Aulinks/MailExtractor

[![Build Status](https://img.shields.io/travis/Aulinks/PHP-MailExtractor.svg?style=flat-square)](https://travis-ci.org/Aulinks/PHP-MailExtractor)

Mail extractor from raw text, pdf, doc, docx and txt files.

Depends on pdftotext and antiword.

## Installation

```
$ composer require aulinks/mail-extractor
$ composer update
```

## Usage

```php
use Aulinks\MailExtractor\MailExtractor;

$extractor = new MailExtractor;
```

**Extract mails from text**:

```php
$mails = $extractor->extractMails('Contact me on lorem@ipsum.com');
```

**Extract mails from file**:

```php
$mails = $extractor->extractMailsFromFile('/path/to/file.pdf');
```

**Extract mails from directory**:

```php
$mails = $extractor->extractMailsFromDirectory('/path/to/directory');
```

## Changelog

* 1.0.0
	+ Initial version
