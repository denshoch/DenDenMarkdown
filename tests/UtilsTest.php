<?php

require_once 'vendor/autoload.php';

class UtilsTest extends PHPUnit_Framework_TestCase
{
    public function testTranform()
    {
        $source = "[text]&#x0;&#x1;&#x2;&#x3;&#x4;&#x5;&#x6;&#x7;&#x8;&#x9;&#xb;&#xc;&#xe;&#xf;&#x10;&#x11;&#x12;&#x13;&#x14;&#x15;&#x16;&#x17;&#x18;&#x19;&#x1a;&#x1b;&#x1c;&#x1d;&#x1e;&#x1f;&#x7f;[/text]";
        $source = mb_convert_encoding($source, 'UTF-8', 'HTML-ENTITIES');
        $excpected = "[text][/text]";
        $actual = Denshoch\Utils::removeCtrlChars($source);
        $this->assertSame($excpected, $actual);
    }

    public function testMarkdown()
    {
    	$parser = new Denshoch\DenDenMarkdown;
    	$source = "Lorem&#x0;&#x1;&#x2;&#x3;&#x4;&#x5;&#x6;&#x7;&#x8;&#x9;&#xb;&#xc;&#xe;&#xf;&#x10;&#x11;&#x12;&#x13;&#x14;&#x15;&#x16;&#x17;&#x18;&#x19;&#x1a;&#x1b;&#x1c;&#x1d;&#x1e;&#x1f;&#x7f;ipsum";
    	$source = mb_convert_encoding($source, 'UTF-8', 'HTML-ENTITIES');
    	$excpected = "<p>Lorem  ipsum</p>\n";
    	$actual = $parser->transform($source);
    	$this->assertEquals($excpected, $actual);
    }

    public function testremoveCtrlChars()
    {
        $parser = new Denshoch\DenDenMarkdown;
        $source = "あ あ"; # あ&#x2028;あ
        $excpected = "<p>ああ</p>\n";
        $actual = $parser->transform($source);
        $this->assertEquals($excpected, $actual);
    }
}