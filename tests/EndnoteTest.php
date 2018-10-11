<?php
require_once 'vendor/autoload.php';

class EndnoteTest extends PHPUnit_Framework_TestCase
{
    public function testEndnote()
    {
    	$parser = new Denshoch\DenDenMarkdown( array("ddmdEndnotes" => true ) );

	    $source = <<< EOT
これは後注付き[リンク][~1]の段落です。

[~1]: そして、これが後注です。
EOT;

        $expected = <<< EOT
<p>これは後注付き<a id="enref:1" href="#en:1" class="enref" epub:type="noteref" role="doc-noteref">リンク</a>の段落です。</p>

<div class="endnotes" epub:type="endnotes" role="doc-endnotes">
<hr />

<div id="en:1" class="endnote" epub:type="endnote" role="doc-endnote">
<p>そして、これが後注です。&#160;<a href="#enref:1" role="doc-backlink">&#9166;</a></p>
</div>

</div>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }

    public function testMultipleEndnotes()
    {
    	$parser = new Denshoch\DenDenMarkdown( array("ddmdEndnotes" => true ) );

	    $source = <<< EOT
これは後注付き[リンク1][~1]の段落です。

これも後注付き[リンク2][~2]の段落です。

[~1]: そして、これが後注です。

[~2]: そして、これも後注です。
EOT;

        $expected = <<< EOT
<p>これは後注付き<a id="enref:1" href="#en:1" class="enref" epub:type="noteref" role="doc-noteref">リンク1</a>の段落です。</p>

<p>これも後注付き<a id="enref:2" href="#en:2" class="enref" epub:type="noteref" role="doc-noteref">リンク2</a>の段落です。</p>

<div class="endnotes" epub:type="endnotes" role="doc-endnotes">
<hr />

<div id="en:1" class="endnote" epub:type="endnote" role="doc-endnote">
<p>そして、これが後注です。&#160;<a href="#enref:1" role="doc-backlink">&#9166;</a></p>
</div>

<div id="en:2" class="endnote" epub:type="endnote" role="doc-endnote">
<p>そして、これも後注です。&#160;<a href="#enref:2" role="doc-backlink">&#9166;</a></p>
</div>

</div>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }

    public function testMultipleEndnoteRefs()
    {
    	$parser = new Denshoch\DenDenMarkdown( array("ddmdEndnotes" => true ) );

	    $source = <<< EOT
参照[リンク1][~1]。参照[リンク2][~1]。

[~1]: 後注
EOT;

        $expected = <<< EOT
<p>参照<a id="enref:1" href="#en:1" class="enref" epub:type="noteref" role="doc-noteref">リンク1</a>。参照<a id="enref2:1" href="#en:1" class="enref" epub:type="noteref" role="doc-noteref">リンク2</a>。</p>

<div class="endnotes" epub:type="endnotes" role="doc-endnotes">
<hr />

<div id="en:1" class="endnote" epub:type="endnote" role="doc-endnote">
<p>後注&#160;<a href="#enref:1" role="doc-backlink">&#9166;</a> <a href="#enref2:1" role="doc-backlink">&#9166;</a></p>
</div>

</div>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }

    public function testEndnoteBlock()
    {
    	$parser = new Denshoch\DenDenMarkdown( array("ddmdEndnotes" => true ) );

	    $source = <<< EOT
Paragraph[link][~1].

[~1]:
    ## Heading

    Paragraph.

    - list
    - list
    - list
EOT;

        $expected = <<< EOT
<p>Paragraph<a id="enref:1" href="#en:1" class="enref" epub:type="noteref" role="doc-noteref">link</a>.</p>

<div class="endnotes" epub:type="endnotes" role="doc-endnotes">
<hr />

<div id="en:1" class="endnote" epub:type="endnote" role="doc-endnote">
<h2>Heading</h2>

<p>Paragraph.</p>

<ul>
<li>list</li>
<li>list</li>
<li>list</li>
</ul>

<p><a href="#enref:1" role="doc-backlink">&#9166;</a></p>
</div>

</div>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }

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
}