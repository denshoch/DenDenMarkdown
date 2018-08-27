<?php
namespace Denshoch;
#
# DenDenMarkdown - just a little help for them.
#
# DenDen Markdown
# Copyright (c) 2013-2017 Densho Channel
# <https://denshochan.com/>
#
# PHP Markdown Extra
# Copyright (c) 2004-2013 Michel Fortin
# <http://michelf.com/projects/php-markdown/>
#
# Original Markdown
# Copyright (c) 2004-2006 John Gruber
# <http://daringfireball.net/projects/markdown/>
#

class DenDenMarkdown extends \Michelf\MarkdownExtra
{

    const DENDENMARKDOWN_VERSION = "1.2.8";

    # Option for adding epub:type attribute.
    public $epubType = true;

    # Option for adding DPUB WAI-ARIA role attribute.
    public $dpubRole = true;

    # Optional class attributes for footnote links and backlinks.
    public $footnoteLinkClass = "noteref";
    public $footnoteLinkContentPre = "";
    public $footnoteLinkContentPost = "";
    public $footnoteBacklinkClass = "";
    public $footnoteBacklinkContent = "&#9166;";

    # Optional class attributes for optional headers.
    public $optionalheader_class = "bridgehead";

    # Optional class attributes for pagebreaks.
    public $pageNumberClass = "pagenum";
    public $pageNumberContentPre = "";
    public $pageNumberContentPost = "";

    # Optional class attributes for Harusame.
    public $autoTcy = false;
    public $tcyDigit = 2;
    public $autoTextOrientation = false;

    # Optional class attributes for ruby
    public $rubyFallback = false;

    public function __construct(array $options = null)
    {
    #
    # Constructor function. Initialize the parser object.
    #

        $this->escape_chars .= '';

        $this->document_gamut += array(
            );
        $this->block_gamut += array(
            "doBlockTitles"     => 11,
            "doDocBreaks"       => 20,
            );
        $this->span_gamut += array(
            "doPageNums"         =>  9,
            "doRubies"           => 50,
            "doTcys"             => 50,
            );

        parent::__construct();

        // Harusame options
        if (false === is_null($options)){

            if(array_key_exists("autoTcy", $options)){
                if (is_bool($options["autoTcy"])) {
                    $this->autoTcy = $options["autoTcy"];
                } else {
                    trigger_error("autoTcy should be boolean.");
                }
            }

            if(array_key_exists("tcyDigit", $options)){
                if (is_int($options["tcyDigit"])) {
                    if ($options["tcyDigit"] < 2) {
                        trigger_error("tcyDigit should be 2 or greater.", E_USER_ERROR);
                    } else {
                        $this->tcyDigit = $options["tcyDigit"];
                    }
                } else {
                    trigger_error("tcyDigit should be int.");
                }
            }

            if(array_key_exists("autoTextOrientation", $options)){
                if (is_bool($options["autoTextOrientation"])) {
                    $this->autoTextOrientation = $options["autoTextOrientation"];
                } else {
                    trigger_error("autoTextOrientation should be boolean.");
                }
            }

            if(array_key_exists("epubType", $options)){
                if (is_bool($options["epubType"])) {
                    $this->epubType = $options["epubType"];
                } else {
                    trigger_error("epubType should be boolean.");
                }
            }

            if(array_key_exists("dpubRole", $options)){
                if (is_bool($options["dpubRole"])) {
                    $this->dpubRole = $options["dpubRole"];
                } else {
                    trigger_error("dpubRole should be boolean.");
                }
            }

            if(array_key_exists("footnoteLinkClass", $options)){
                if (is_string($options["footnoteLinkClass"])) {
                    $this->footnoteLinkClass = $options["footnoteLinkClass"];
                } else {
                    trigger_error("footnoteLinkClass should be string.");
                }
            }

            if(array_key_exists("footnoteLinkContentPre", $options)){
                if (is_string($options["footnoteLinkContentPre"])) {
                    $this->footnoteLinkContentPre = $options["footnoteLinkContentPre"];
                } else {
                    trigger_error("footnoteLinkContentPre should be string.");
                }
            }

            if(array_key_exists("footnoteLinkContentPost", $options)){
                if (is_string($options["footnoteLinkContentPost"])) {
                    $this->footnoteLinkContentPost = $options["footnoteLinkContentPost"];
                } else {
                    trigger_error("footnoteLinkContentPost should be string.");
                }
            }

            if(array_key_exists("footnoteLinkClass", $options)){
                if (is_string($options["footnoteLinkClass"])) {
                    $this->footnoteLinkClass = $options["footnoteLinkClass"];
                } else {
                    trigger_error("footnoteLinkClass should be string.");
                }
            }

            if(array_key_exists("footnoteBacklinkClass", $options)){
                if (is_string($options["footnoteBacklinkClass"])) {
                    $this->footnoteBacklinkClass = $options["footnoteBacklinkClass"];
                } else {
                    trigger_error("footnoteBacklinkClass should be string.");
                }
            }

            if(array_key_exists("footnoteBacklinkContent", $options)){
                if (is_string($options["footnoteBacklinkContent"])) {
                    $this->footnoteBacklinkContent = $options["footnoteBacklinkContent"];
                } else {
                    trigger_error("footnoteBacklinkContent should be string.");
                }
            }

            if(array_key_exists("pageNumberClass", $options)){
                if (is_string($options["pageNumberClass"])) {
                    $this->pageNumberClass = $options["pageNumberClass"];
                } else {
                    trigger_error("pageNumberClass should be string.");
                }
            }

            if(array_key_exists("pageNumberContentPre", $options)){
                if (is_string($options["pageNumberContentPre"])) {
                    $this->pageNumberContentPre = $options["pageNumberContentPre"];
                } else {
                    trigger_error("pageNumberContentPre should be string.");
                }
            }

            if(array_key_exists("pageNumberContentPost", $options)){
                if (is_string($options["pageNumberContentPost"])) {
                    $this->pageNumberContentPost = $options["pageNumberContentPost"];
                } else {
                    trigger_error("pageNumberContentPost should be string.");
                }
            }
        }
    }

    # Tags that are always treated as block tags:
    protected $block_tags_re = 'address|article|aside|blockquote|body|center|dd|details|dialog|dir|div|dl|dt|figcaption|figure|footer|h[1-6]|header|hgroup|hr|html|legend|listing|menu|nav|ol|p|plaintext|pre|section|summary|style|table|ul|xmp';

    # Tags where markdown="1" default to span mode:
    protected $contain_span_tags_re = 'p|h[1-6]|li|dd|dt|td|th|legend|address';

    # Override transform()
    public function transform($text)
    {
        $text = parent::transform($text);

        $text = \Denshoch\Utils::removeCtrlChars($text); // Remove control chars

        $harusame = new \Denshoch\Harusame(
            array(
                "autoTcy" => $this->autoTcy,
                "tcyDigit" => $this->tcyDigit,
                "autoTextOrientation" => $this->autoTextOrientation
            )
        );
        $text = $harusame->transform($text);

        return $text;
    }

    protected function doBlockTitles($text)
    {
        # block titles:
        #   .BLOCK TITLE {#title1}
        #
        $text = preg_replace_callback('{
                ^(\.)       # $1 = string of \.
                [ ]*
                (.+?)       # $2 = TITLE text
                [ ]*
                (?:[ ]+ '.$this->id_class_attr_catch_re.' )?     # $3 = id/class attributes
                [ ]*
                \n+
            }xm',
            array(&$this, '_doBlockTitles_callback'), $text);

        return $text;
    }
    protected function _doBlockTitles_callback($matches) {
        $level = strlen($matches[1]);
        $dummy =& $matches[3];

        if($this->optionalheader_class != ""){
            $dummy .= ".$this->optionalheader_class";
        }
        $attr  = $this->doExtraAttributes("p", $dummy);
        if($this->epubType){
            $attr  .= " epub:type=\"bridgehead\"";
        }
        $block = "<p$attr><b>".$this->runSpanGamut($matches[2])."</b></p>";
        return "\n" . $this->hashBlock($block) . "\n\n";
    }

    protected function doDocBreaks($text)
    {
        return preg_replace(
            '{
                ^[ ]{0,3}       # Leading space
                (=)             # $1: First marker
                (?>             # Repeated marker group
                    [ ]{0,2}    # Zero, one, or two spaces.
                    \1          # Marker character
                ){2,}           # Group repeated at least twice
                [ ]*            # Tailing spaces
                $               # End of line.
            }mx',
            "\n".$this->hashBlock("<hr class=\"docbreak\"$this->empty_element_suffix")."\n",
            $text);
    }

    # GFM Hard Break
    protected function doHardBreaks($text)
    {
        # Do hard breaks:
        return preg_replace_callback('/ {0,}\n/',
            array($this, '_doHardBreaks_callback'), $text);
    }

    protected function doPageNums($text)
    {
        $pagebreak_block_reg = '/^[ ]{0,3}\[(%)(%?)(.+?)\][ ]*/m';
        $text = preg_replace_callback($pagebreak_block_reg, array(&$this, '_doPageNumsBlock_callback'), $text);

        $pagebreak_reg = '/\[(%)(%?)(.+?)\]/m';
        $text = preg_replace_callback($pagebreak_reg, array(&$this, '_doPageNums_callback'), $text);

        return $text;
    }

    protected function _doPageNumsBlock_callback($matches)
    {
        $title = $matches[3];

        if ("%" == $matches[2]) {
            $content = $this->pageNumberContentPre . $title . $this->pageNumberContentPost;
        } else {
            $content = '';
        }
        $title = $this->encodeAttribute($title);

        $attr = "";
        $id = "pagenum_${title}";
        $attr .= " id=\"$id\"";
        if ($this->pageNumberClass != "") {
            $class = $this->pageNumberClass;
            $class = $this->encodeAttribute($class);
            $attr .= " class=\"$class\"";
        }
        $attr .= " title=\"$title\"";
        if($this->epubType) {
            $attr .= " epub:type=\"pagebreak\"";
        }

        if($this->dpubRole) {
            $attr .= " role=\"doc-pagebreak\"";
        }

        $result = "<div$attr>";
        $result .=  $content;
        $result .= "</div>";

        return $this->hashBlock($result);
    }

    protected function _doPageNums_callback($matches)
    {
        $title = $matches[3];

        if ("%" == $matches[2]) {
            $content = $title;
        } else {
            $content = '';
        }
        $title = $this->encodeAttribute($title);

        $attr = "";
        $id = "pagenum_$title";
        $attr .= " id=\"$id\"";
        if ($this->pageNumberClass != "") {
            $class = $this->pageNumberClass;
            $class = $this->encodeAttribute($class);
            $attr .= " class=\"$class\"";
        }
        $attr .= " title=\"$title\"";
        if($this->epubType) {
            $attr .= " epub:type=\"pagebreak\"";
        }

        if($this->dpubRole) {
            $attr .= " role=\"doc-pagebreak\"";
        }

        $result = "<span$attr>";
        $result .=  $content;
        $result .= "</span>";

        return $this->hashPart($result);
    }

    protected function doAutoLinks($text)
    {
        $text = preg_replace_callback('{<((https?|ftp|dict):[^\'">\s]+)>}i',
            array($this, '_doAutoLinks_url_callback'), $text);

        # Email addresses: <address@domain.foo>
        $text = preg_replace_callback('{
            <
            (?:mailto:)?
            (
                (?:
                    [-!#$%&\'*+/=?^_`.{|}~\w\x80-\xFF]+
                |
                    ".*?"
                )
                \@
                (?:
                    [-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
                |
                    \[[\d.a-fA-F:]+\]    # IPv4 & IPv6
                )
            )
            >
            }xi',
            array($this, '_doAutoLinks_email_callback'), $text);

        # Twitter account: <@twitter>
        $text = preg_replace_callback("/(?:<)(?<![0-9a-zA-Z'\"#@=:;])@([0-9a-zA-Z_]{1,15})(?:>)/u", array($this, '_doAutoLinks_twitter_callback'), $text);

        return $text;
    }

    protected function _doAutoLinks_twitter_callback($matches)
    {
        $account = $matches[1];
        $link = "<a href=\"https://twitter.com/$account\">@$account</a>";
        return $this->hashPart($link);
    }

    //split multibyte chars
    protected function mb_str_split($str, $enc, $length=1)
    {
        if ($length <= 0) {
            return false;
        }
        $result = array();
        for ($i = 0, $idx = 0;$i < mb_strlen($str, $enc);$i += $length) {
            $result[$idx++] = mb_substr($str, $i, $length, $enc);
        }
        return $result;
    }

    protected function doRubies($text)
    {
        $text = preg_replace_callback(
            '{
                ( (?<!\{) \{ )        # $1: Marker (not preceded by two /)
                (?=\S)                # Not followed by whitespace
                (?!\1)                #   or two others marker chars.
                (                     # $2: Base Text
                    (?>
                        [^|]+?        # Anthing not |.
                    )+?
                )
                \|
                (                     # $3: Ruby text

                    [^\}]+?
                )
                \}                    # End mark not preceded by whitespace.
            }sx',
            array($this,'doRubies_Callback'), $text);

        return $text;
    }

    protected function doRubies_Callback($matches)
    {
        $result = "<ruby>";
        $rbarray = $this->mb_str_split($matches[2], 'UTF-8');
        $rbcount = count($rbarray);
        $rtarray = explode("|", $matches[3]);
        $rtcount = count($rtarray);
        if ( $rbcount == $rtcount) {
            for ($i=0, $idx=0; $i < $rbcount; $i++) {
                $result = $result.$rbarray[$idx]."<rt>".$rtarray[$idx]."</rt>";
                $idx++;
            }
            $result = $result."</ruby>";
        } else {
            $result = $result.$matches[2]."<rt>".join('', $rtarray)."</rt></ruby>";
        }

        return $result;
    }

    protected function doTcys($text)
    {
        $text = preg_replace(
            '{
                ( (?<!\^) \^ )          # $1: Marker (not preceded by two /)
                (?=\S)                  # Not followed by whitespace
                (?!\1)                  #   or two others marker chars.
                (                       # $2: Content
                    (?>
                        [^\^]+?         #
                    |
                                        # Balence any regular / emphasis inside.
                        \/ (?=\S) (?! \^) (.+?) (?<=\S) \^
                    )+?
                )
                (?<=\S) \^              # End mark not preceded by whitespace.
            }sx',
            '<span class="tcy">\2</span>', $text);

        return $text;
    }

    //custum footnote code
    protected function appendFootnotes($text)
    {
    #
    # Append footnote list to text.
    #
        $text = preg_replace_callback('{F\x1Afn:(.*?)\x1A:}',
            array($this, '_appendFootnotes_callback'), $text);

        if (!empty($this->footnotes_ordered)) {
            $text .= "\n\n";
            $text .= "<div class=\"footnotes\"";

            if($this->epubType) {
                $text .= " epub:type=\"footnotes\"";
            }

            if($this->dpubRole) {
                $text .= " role=\"doc-endnotes\"";
            }

            $text .= ">\n";
            $text .= "<hr". $this->empty_element_suffix ."\n";
            $text .= "<ol>\n\n";


            $attr = "";

            if ($this->footnoteBacklinkClass != "") {
                $class = $this->footnoteBacklinkClass;
                $class = $this->encodeAttribute($class);
                $attr .= " class=\"$class\"";
            }

            if ($this->fn_backlink_title != "") {
                $title = $this->fn_backlink_title;
                $title = $this->encodeAttribute($title);
                $attr .= " title=\"$title\"";
            }

            if ($this->dpubRole) {
                $attr .= " role=\"doc-backlink\"";
            }

            $num = 0;

            while (!empty($this->footnotes_ordered)) {
                $footnote = reset($this->footnotes_ordered);
                $note_id = key($this->footnotes_ordered);
                unset($this->footnotes_ordered[$note_id]);
                $ref_count = $this->footnotes_ref_count[$note_id];
                unset($this->footnotes_ref_count[$note_id]);
                unset($this->footnotes[$note_id]);

                $footnote .= "\n"; # Need to append newline before parsing.
                $footnote = $this->runBlockGamut("$footnote\n");
                $footnote = preg_replace_callback('{F\x1Afn:(.*?)\x1A:}',
                    array($this, '_appendFootnotes_callback'), $footnote);

                $attr = str_replace("%%", ++$num, $attr);
                $note_id = $this->encodeAttribute($note_id);

                # Prepare backlink, multiple backlinks if multiple references
                $backlink = "<a href=\"#fnref_$note_id\"$attr>$this->footnoteBacklinkContent</a>";
                for ($ref_num = 2; $ref_num <= $ref_count; ++$ref_num) {
                    $backlink .= " <a href=\"#fnref$ref_num_$note_id\"$attr>$this->footnoteBacklinkContent</a>";
                }
                # Add backlink to last paragraph; create new paragraph if needed.
                if (preg_match('{</p>$}', $footnote)) {
                    $footnote = substr($footnote, 0, -4) . "&#160;$backlink</p>";
                } else {
                    $footnote .= "\n\n<p>$backlink</p>";
                }

                $text .= "<li>\n";
                $text .= "<div id=\"fn_$note_id\" class=\"footnote\"";

                if($this->epubType){
                    $text .= " epub:type=\"footnote\"";
                }

                if($this->dpubRole){
                    $text .= " role=\"doc-endnote\"";
                }

                $text .= ">\n";
                $text .= $footnote . "\n";
                $text .= "</div>\n";
                $text .= "</li>\n\n";

            }

            $text .= "</ol>\n";
            $text .= "</div>\n";
        }
        return $text;
    }

    protected function _appendFootnotes_callback($matches)
    {
        $node_id = $this->fn_id_prefix . $matches[1];

        # Create footnote marker only if it has a corresponding footnote *and*
        # the footnote hasn't been used by another marker.
        if (isset($this->footnotes[$node_id])) {
            $num =& $this->footnotes_numbers[$node_id];
            if (!isset($num)) {
                # Transfer footnote content to the ordered list and give it its
                # number
                $this->footnotes_ordered[$node_id] = $this->footnotes[$node_id];
                $this->footnotes_ref_count[$node_id] = 1;
                $num = $this->footnote_counter++;
                $ref_count_mark = '';
            } else {
                $ref_count_mark = $this->footnotes_ref_count[$node_id] += 1;
            }

            $attr = "";
            $node_id = $this->encodeAttribute($node_id);
            $attr .= " id=\"fnref_$node_id\"";
            $attr .= " href=\"#fn_$node_id\"";
            $attr .= " rel=\"footnote\"";
            if ($this->footnoteLinkClass != "") {
                $class = $this->footnoteLinkClass;
                $class = $this->encodeAttribute($class);
                $attr .= " class=\"$class\"";
            }

            if ($this->fn_link_title != "") {
                $title = $this->fn_link_title;
                $title = $this->encodeAttribute($title);
                $attr .= " title=\"$title\"";
            }

            if ($this->epubType){
                $attr .= " epub:type=\"noteref\"";
            }

            if ($this->dpubRole){
                $attr .= " role=\"doc-noteref\"";
            }

            $attr = str_replace("%%", $num, $attr);

            return
                "<a$attr>$this->footnoteLinkContentPre${num}$this->footnoteLinkContentPost</a>"
                ;
        }

        return "[^".$matches[1]."]";
    }
}
