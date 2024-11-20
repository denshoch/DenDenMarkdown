<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DDmdTableTest extends TestCase
{
    protected $parser;
    protected $fixtureDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new Denshoch\DenDenMarkdown(array("ddmdTable" => true));
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'DDmdTable';
    }

    public function test01()
    {
        $this->assertTransformation('01');
    }

    /**
     * @group t2
     */
    public function test02()
    {
        $this->assertTransformation('02');
    }

    public function testTableAlignClassTmpl()
    {
        $this->parser->tableAlignClassTmpl = "text-%%";
        $this->assertTransformation('testTableAlignClassTmpl');
    }

    public function testCustomWrapperClass01()
    {
        $this->parser = new Denshoch\DenDenMarkdown(
            array(
                "ddmdTable" => true,
                "ddmdTableWrapperClass" => "ddmdTable"
            )
        );
        $this->assertTransformation('testCustomWrapperClass01');
    }

    public function testCustomWrapperClass02()
    {
        $this->parser = new Denshoch\DenDenMarkdown(
            array(
                "ddmdTable" => true,
                "ddmdTableWrapperClass" => ""
            )
        );
        $this->assertTransformation('testCustomWrapperClass02');
    }

    protected function assertTransformation($fixtureName)
    {
        $sourceFile = $fixtureName . '.md';
        $transformedFile = $fixtureName . '.html';
        $this->assertTransformedFile($transformedFile, $sourceFile);
    }

    protected function assertTransformedFile($transformedFile, $sourceFile)
    {
        $expected = $this->fixture($transformedFile);
        $actual = $this->parser->transform($this->fixture($sourceFile));
        //$this->assertSame(rtrim($expected), $actual);

        // XML形式で比較するために整形
        $expectedXml = sprintf('<?xml version="1.0"?><root>%s</root>', $expected);
        $actualXml = sprintf('<?xml version="1.0"?><root>%s</root>', $actual);

        $this->assertXmlStringEqualsXmlString($expectedXml, $actualXml);
    }

    protected function fixture($fileName)
    {
        $filePath = $this->fixtureDir . DIRECTORY_SEPARATOR . $fileName;
        return file_get_contents($filePath);
    }
}
