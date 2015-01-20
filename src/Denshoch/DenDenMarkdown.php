<?php
#
# Den-Den Markdown - just a little help for them.
#
# Den-Den Markdown
# Copyright (c) 2013 Hiroshi Takase
# <http://densho.hatenablog.com/>
#
# PHP Markdown Extra
# Copyright (c) 2004-2013 Michel Fortin
# <http://michelf.com/projects/php-markdown/>
#
# Original Markdown
# Copyright (c) 2004-2006 John Gruber
# <http://daringfireball.net/projects/markdown/>
#
namespace Denshoch;

class DenDenMarkdown extends \Michelf\MarkdownExtra
{

    const EPUBMARKDOWN_VERSION = "1.0.0";

    public function __construct()
    {
    #
    # Constructor function. Initialize the parser object.
    #

        $this->escape_chars .= '';


        $this->document_gamut += array(
            );
        $this->block_gamut += array(
            "doBlockTitles" => 11,
            "doDocBreaks"       => 20,
            );
        $this->span_gamut += array(
            "doPageNums"         =>  9,
            "doRubies"           => 50,
            "doTcys"             => 50,
            );

        parent::__construct();
    }

    # Option for adding epub:type attribute.
    public $epubtype = true;

    # Optional class attribute for footnote links and backlinks.
    public $fn_link_class = "noteref";
    public $fn_backlink_class = "";

    # Optional class attribute for optional headers.
    public $optionalheader_class = "bridgehead";

    # Optional class attribute for pagebreaks.
    public $pagebreak_class = "pagenum";

    # Tags that are always treated as block tags:
    protected $block_tags_re = 'address|article|aside|blockquote|body|center|dd|details|dialog|dir|div|dl|dt|figcaption|figure|footer|h[1-6]|header|hgroup|hr|html|legend|listing|menu|nav|ol|p|plaintext|pre|section|summary|style|table|ul|xmp';

    # Tags where markdown="1" default to span mode:
    protected $contain_span_tags_re = 'p|h[1-6]|li|dd|dt|td|th|legend|address';

    # Tags which must not have their contents modified, no matter where
    # they appear:
    protected $clean_tags_re = 'script|math|svg|style';

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
        if($this->epubtype){
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
            $content = $title;
        } else {
            $content = '';
        }
        $title = $this->encodeAttribute($title);

        $attr = "";
        $id = "pagenum_$title";
        $attr .= " id=\"$id\"";
        if ($this->pagebreak_class != "") {
            $class = $this->pagebreak_class;
            $class = $this->encodeAttribute($class);
            $attr .= " class=\"$class\"";
        }
        $attr .= " title=\"$title\"";
        if($this->epubtype) {
            $attr .= " epub:type=\"pagebreak\"";
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
        if ($this->pagebreak_class != "") {
            $class = $this->pagebreak_class;
            $class = $this->encodeAttribute($class);
            $attr .= " class=\"$class\"";
        }
        $attr .= " title=\"$title\"";
        if($this->epubtype) {
            $attr .= " epub:type=\"pagebreak\"";
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
            if($this->epubtype) {
                $text .= " epub:type=\"footnotes\"";
            }
            $text .= ">\n";
            $text .= "<hr". $this->empty_element_suffix ."\n";
            $text .= "<ol>\n\n";


            $attr = "";
            if ($this->fn_backlink_class != "") {
                $class = $this->fn_backlink_class;
                $class = $this->encodeAttribute($class);
                $attr .= " class=\"$class\"";
            }
            if ($this->fn_backlink_title != "") {
                $title = $this->fn_backlink_title;
                $title = $this->encodeAttribute($title);
                $attr .= " title=\"$title\"";
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
                $backlink = "<a href=\"#fnref_$note_id\"$attr>&#9166;</a>";
                for ($ref_num = 2; $ref_num <= $ref_count; ++$ref_num) {
                    $backlink .= " <a href=\"#fnref$ref_num_$note_id\"$attr>&#9166;</a>";
                }
                # Add backlink to last paragraph; create new paragraph if needed.
                if (preg_match('{</p>$}', $footnote)) {
                    $footnote = substr($footnote, 0, -4) . "&#160;$backlink</p>";
                } else {
                    $footnote .= "\n\n<p>$backlink</p>";
                }

                $text .= "<li>\n";
                $text .= "<div id=\"fn_$note_id\" class=\"footnote\"";
                if($this->epubtype){
                    $text .= " epub:type=\"footnote\"";
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
            if ($this->fn_link_class != "") {
                $class = $this->fn_link_class;
                $class = $this->encodeAttribute($class);
                $attr .= " class=\"$class\"";
            }
            if ($this->fn_link_title != "") {
                $title = $this->fn_link_title;
                $title = $this->encodeAttribute($title);
                $attr .= " title=\"$title\"";
            }
            if ($this->epubtype){
                $attr .= " epub:type=\"noteref\"";
            }

            $attr = str_replace("%%", $num, $attr);

            return
                "<a$attr>$num</a>"
                ;
        }

        return "[^".$matches[1]."]";
    }
}
