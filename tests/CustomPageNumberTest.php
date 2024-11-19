<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CustomPageNumberTest extends TestCase
{
    protected $parser;
    protected $fixtureDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new Denshoch\DenDenMarkdown( array("customPageNumber" => true) );
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'CustomPageNumber';
    }

    public function testCustomPageNumberClass()
    {
        $source = <<< EOT
[%%36]

## 大見出し ##
EOT;

        $expected = <<< EOT
<div id="pagenum_36" class="page" title="36" epub:type="pagebreak" role="doc-pagebreak">36</div>

<h2>大見出し</h2>
EOT;

        $actual = $this->parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);
        
    }

    public function testCustomPageNumberContent()
    {
        $source = <<< EOT
[%%36]

## 大見出し ##
EOT;

        $expected = <<< EOT
<div id="pagenum_36" class="pagenum" title="36" epub:type="pagebreak" role="doc-pagebreak">第36ページ</div>

<h2>大見出し</h2>
EOT;

        $actual = $this->parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);
        
    }
}