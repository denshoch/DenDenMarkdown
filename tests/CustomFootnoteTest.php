<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class CustomFootnoteTest extends TestCase
{
    public function testCustomFootnoteLinkClass()
    {
    	$parser = new Denshoch\DenDenMarkdown( array("footnoteLinkClass" => "footnoteref" ) );

        $source = <<< EOT
これは脚注付き[^1]の段落です。

[^1]: そして、これが脚注です。
EOT;

        $expected = <<< EOT
<p>これは脚注付き<a id="fnref_1" href="#fn_1" rel="footnote" class="footnoteref" epub:type="noteref" role="doc-noteref">1</a>の段落です。</p>

<div class="footnotes" epub:type="footnotes">
<hr/>
<ol>

<li>
<div id="fn_1" class="footnote" epub:type="footnote" role="doc-footnote">
<p>そして、これが脚注です。 <a href="#fnref_1" role="doc-backlink">⏎</a></p>
</div>
</li>

</ol>
</div>
EOT;

        $actual = $parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);

    }

    public function testCustomFootnoteLinkContent()
    {
    	  $parser = new Denshoch\DenDenMarkdown( array("footnoteLinkContent" => "注%%番") );

        $source = <<< EOT
これは脚注付き[^1]の段落です。

[^1]: そして、これが脚注です。
EOT;

        $expected = <<< EOT
<p>これは脚注付き<a id="fnref_1" href="#fn_1" rel="footnote" class="noteref" epub:type="noteref" role="doc-noteref">注1番</a>の段落です。</p>

<div class="footnotes" epub:type="footnotes">
<hr/>
<ol>

<li>
<div id="fn_1" class="footnote" epub:type="footnote" role="doc-footnote">
<p>そして、これが脚注です。 <a href="#fnref_1" role="doc-backlink">⏎</a></p>
</div>
</li>

</ol>
</div>
EOT;

        $actual = $parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);

    }

    public function testCustomFootnoteBackLinkClass()
    {
    	  $parser = new Denshoch\DenDenMarkdown( array("footnoteBacklinkClass" => "backlink" ) );

        $source = <<< EOT
これは脚注付き[^1]の段落です。

[^1]: そして、これが脚注です。
EOT;

        $expected = <<< EOT
<p>これは脚注付き<a id="fnref_1" href="#fn_1" rel="footnote" class="noteref" epub:type="noteref" role="doc-noteref">1</a>の段落です。</p>

<div class="footnotes" epub:type="footnotes">
<hr/>
<ol>

<li>
<div id="fn_1" class="footnote" epub:type="footnote" role="doc-footnote">
<p>そして、これが脚注です。 <a href="#fnref_1" class="backlink" role="doc-backlink">⏎</a></p>
</div>
</li>

</ol>
</div>
EOT;

        $actual = $parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);

    }

    public function testCustomFootnoteBackLinkContent()
    {
    	  $parser = new Denshoch\DenDenMarkdown( array("footnoteBacklinkContent" => "もどる" ) );

        $source = <<< EOT
これは脚注付き[^1]の段落です。

[^1]: そして、これが脚注です。
EOT;

        $expected = <<< EOT
<p>これは脚注付き<a id="fnref_1" href="#fn_1" rel="footnote" class="noteref" epub:type="noteref" role="doc-noteref">1</a>の段落です。</p>

<div class="footnotes" epub:type="footnotes">
<hr/>
<ol>

<li>
<div id="fn_1" class="footnote" epub:type="footnote" role="doc-footnote">
<p>そして、これが脚注です。 <a href="#fnref_1" role="doc-backlink">もどる</a></p>
</div>
</li>

</ol>
</div>
EOT;

        $actual = $parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);

          $parser = new Denshoch\DenDenMarkdown( array("footnoteBacklinkContent" => "合印%%にもどる" ) );

        $source = <<< EOT
これは脚注付き[^1]の段落です。

[^1]: そして、これが脚注です。
EOT;

        $expected = <<< EOT
<p>これは脚注付き<a id="fnref_1" href="#fn_1" rel="footnote" class="noteref" epub:type="noteref" role="doc-noteref">1</a>の段落です。</p>

<div class="footnotes" epub:type="footnotes">
<hr/>
<ol>

<li>
<div id="fn_1" class="footnote" epub:type="footnote" role="doc-footnote">
<p>そして、これが脚注です。 <a href="#fnref_1" role="doc-backlink">合印1にもどる</a></p>
</div>
</li>

</ol>
</div>
EOT;

        $actual = $parser->transform($source);
        $this->assertEquals(rtrim($expected), $actual);

    }
}