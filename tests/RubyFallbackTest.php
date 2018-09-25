<?php
require_once 'vendor/autoload.php';

class RubyFallbackTest extends PHPUnit_Framework_TestCase
{
    public function testCustomPageNumberClass()
    {
        $parser = new Denshoch\DenDenMarkdown( array("rubyFallback" => true ) );

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
}