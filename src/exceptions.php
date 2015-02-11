<?php

namespace Aulinks\MailExtractor;

class RuntimeException extends \RuntimeException {}

class InvalidArgumentException extends \InvalidArgumentException {}

class InvalidStateException extends RuntimeException {}

class IOException extends RuntimeException {}

class PathNotFoundException extends IOException {}

class InvalidPathException extends IOException {}

class TextExtractorException extends InvalidStateException {}
