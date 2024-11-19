<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HarusameTest extends TestCase
{
	protected $parser;
	protected $fixtureDir;

	protected function setUp(): void
	{
		parent::setUp();
		$this->parser = new Denshoch\DenDenMarkdown( array("harusame" => true) );
		$this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'Harusame';
	}

	// 既存のコード...
}