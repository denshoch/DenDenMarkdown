<?php
require_once 'vendor/autoload.php';

class DDmdEndnoteTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->parser = new Denshoch\DenDenMarkdown( array("ddmdEndnotes" => true, "targetEpubCheckVersion" => "") );
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'DDmdEndnote';
    }

    public function test01()
    {
        $this->assertTransformation('01');
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
        $this->assertSame($expected, $actual);
    }

    protected function fixture($fileName)
    {
        $filePath = $this->fixtureDir . DIRECTORY_SEPARATOR . $fileName;
        return file_get_contents($filePath);
    }

/*
    public function testEndnoteProperties()
    {
        $parser = new Denshoch\DenDenMarkdown( 
            array(
                "ddmdEndnotes" => true,
                "endnotesHeadingContent" => "後注",
                "endnotesHeadingTag" => "h2",
                "endnoteLinkClass" => "endnote_link_class",
                "endnoteLinkTitle" => "endnote_link_title",
                "endnoteClass" => "endnote_class",
                "endnoteBacklinkClass" => "endnote_backlink_class",
                "endnoteBacklinkContent" => "endnoteBacklinkContent"
            ) );

        $source = <<< EOT
これは後注付き[リンク][~1]の段落です。

[~1]: そして、これが後注です。
EOT;

        $expected = <<< EOT
<p>これは後注付き<a id="enref:1" href="#en:1" class="endnote_link_class" title="endnote_link_title" epub:type="noteref" role="doc-noteref">リンク</a>の段落です。</p>

<div class="endnotes" epub:type="endnotes" role="doc-endnotes">
<hr />

<h2>後注</h2>

<div id="en:1" class="endnote_class" epub:type="endnote" role="doc-endnote">
<p>そして、これが後注です。&#160;<a href="#enref:1" class="endnote_backlink_class" role="doc-backlink">endnoteBacklinkContent</a></p>
</div>

</div>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }
    */
}