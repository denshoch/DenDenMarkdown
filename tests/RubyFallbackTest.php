<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class RubyFallbackTest extends TestCase
{
    public function testRubyFallback()
    {
        $parser = new Denshoch\DenDenMarkdown( array( "rubyParenthesisOpen" => "(", "rubyParenthesisClose" => ")" ) );

        $source = "{電子書籍|でんししょせき}";

        $expected = <<< EOT
<p><ruby>電子書籍<rp>(</rp><rt>でんししょせき</rt><rp>)</rp></ruby></p>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);

        $source = "{電子書籍|でん|し|しょ|せき}";

        $expected = <<< EOT
<p><ruby>電<rp>(</rp><rt>でん</rt><rp>)</rp>子<rp>(</rp><rt>し</rt><rp>)</rp>書<rp>(</rp><rt>しょ</rt><rp>)</rp>籍<rp>(</rp><rt>せき</rt><rp>)</rp></ruby></p>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }

    public function testRubyParenthesisEscape()
    {
        $parser = new Denshoch\DenDenMarkdown( array( "rubyParenthesisOpen" => "<h1>&", "rubyParenthesisClose" => "&</h1>" ) );
        $source = "{電子書籍|でんししょせき}";

        $expected = <<< EOT
<p><ruby>電子書籍<rp>&lt;h1&gt;&amp;</rp><rt>でんししょせき</rt><rp>&amp;&lt;/h1&gt;</rp></ruby></p>

EOT;

        $actual = $parser->transform($source);
        $this->assertEquals($expected, $actual);
    }
}