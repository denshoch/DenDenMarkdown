<?php
require_once 'vendor/autoload.php';

class AliasTest extends PHPUnit_Framework_TestCase
{
    public function testAliases()
    {
    	// Boolean
    	$pairs = [
    	    ["hard_wrap", "hardWrap", ],
            ["enhanced_ordered_list", "enhancedOrderedList"],
    	];

    	foreach ($pairs as $pair) {
    		$org = $pair[0];
    		$alias = $pair[1];

    		$parser = new Denshoch\DenDenMarkdown;
    		$this->assertSame($parser->$org, $parser->$alias);

    		$parser = new Denshoch\DenDenMarkdown(array($alias => false));
    		$this->assertFalse($parser->$alias);
    		$this->assertSame($parser->$org, $parser->$alias);

    		$parser->$org = true;
    		$this->assertTrue($parser->$alias);
            $this->assertSame($parser->$org, $parser->$alias);
    	}

        // String
    	$pairs = [
    	    ["fn_id_prefix", "footnoteIdPrefix", "boofoowoo", ""],
    	    ["fn_link_class", "footnoteLinkClass", "boofoowoo", "noteref"],
    	    ["fn_backlink_class", "footnoteBacklinkClass", "boofoowoo", "footnote-backref"],
    	    ["fn_backlink_html", "footnoteBacklinkContent", "boofoowoo", "&#9166;"],
    	    ["table_align_class_tmpl", "tableAlignClassTmpl", "boofoowoo", ""]
    	];

    	foreach ($pairs as $pair) {
    		$org = $pair[0];
    		$alias = $pair[1];
    		$val1 = $pair[2];
    		$val2 = $pair[3];

    		$parser = new Denshoch\DenDenMarkdown;
    		$this->assertSame($parser->$org, $parser->$alias);

    		$parser = new Denshoch\DenDenMarkdown(array($alias => $val1));
    		$this->assertSame($parser->$alias, $val1);
    		$this->assertSame($parser->$org, $parser->$alias);

    		$parser->$org = $val2;
    		$this->assertSame($parser->$alias, $val2);
            $this->assertSame($parser->$org, $parser->$alias);
    	}
    }
}