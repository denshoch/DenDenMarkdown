<?php
namespace Denshoch;

#
# DenDenMarkdown
#
# DenDenMarkdown
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

    const DENDENMARKDOWN_VERSION = "1.4.6";

    /**
     * Alias of $hard_wrap
     *
     * @var boolean
     */
    public $hardWrap = true;

    /**
     * Alias of $enhanced_ordered_list
     *
     * @var bool
     */
    public $enhancedOrderedList = true;

    # Option for adding epub:type attribute.
    public $epubType = true;

    # Option for adding DPUB WAI-ARIA role attribute.
    public $dpubRole = true;

    # Option fot support Aozora Bunko ruby syntax.
    public $aozoraRuby = false;

    # Optional class attributes for footnote links and backlinks.
    public $footnoteIdPrefix = "";
    public $footnoteLinkClass = "noteref";

    public $footnoteLinkContent = "%%";
    public $footnoteBacklinkClass = "";

    public $footnoteBacklinkContent = "&#9166;";

    # Optional class attributes for optional headers.
    public $optionalheader_class = "bridgehead";

    # Optional class attributes for pagebreaks.
    public $pageNumberClass = "pagenum";
    public $pageNumberContent = "%%";

    # Optional class attributes for Harusame.
    public $autoTcy = false;
    public $tcyDigit = 2;
    public $autoTextOrientation = false;

    # Extra variables for ruby annotations
    public $rubyParenthesisOpen = "";
    public $rubyParenthesisClose = "";
    protected $rpOpen;
    protected $rpClose;

    # Extra variables for custom table markup
    public $ddmdTable = false;
    public $ddmdTableWrapperClass = "tbl_wrp";

    # Extra variables for endnotes
    public $ddmdEndnotes = false;
    public $endnotesHeadingContent = "";
    public $endnotesHeadingTag = "p";
    public $endnoteIdPrefix = '';
    public $endnoteLinkClass = "enref";
    public $endnoteLinkTitle = "";
    public $endnoteClass = "endnote";
    public $endnoteBacklinkClass = "";
    public $endnoteBacklinkContent = "&#9166;";
    protected $endnotes = array();
    protected $endnotes_ordered = array();
    protected $endnotes_ref_count = array();
    protected $endnotes_numbers = array();
    protected $endnote_counter = 1;

    # Extra variables for tables
    public $tableAlignClassTmpl = "";

    # pcre.backtrack_limit
    protected $backtrack_limit = 2000000;

    public $targetEpubCheckVersion = "4.2.0";

    # config
    protected $config = array();

    /**
     * __construct
     *
     * Constructor function. Initialize the parser object.
     */
    public function __construct(array $options = null)
    {
        /*
        $backtrack_limit = ini_get('pcre.backtrack_limit');

        if ( (integer) $backtrack_limit <= 1000000) {
            error_log("pcre.backtrack_limit' is low, increase to {$this->backtrack_limit}" );
            $this->setBacktrakLimit((string) $this->backtrack_limit);
        }
        */

        /* alias */
        #$this->table_align_class_tmpl &= $this->tableAlignClassTmpl;
        #$this->tableAlignClassTmpl &= $this->table_align_class_tmpl;
        $this->setAlias();

        $this->escape_chars .= '';

        $this->document_gamut += array(
            "stripEndnotes" => 16,
            "appendEndnotes"    => 51,
            );

        $this->block_gamut += array(
            "doBlockTitles"     => 11,
            "doDocBreaks"       => 20,
            "doBlockPageNums"   => 70,
            );

        $this->span_gamut += array(
            "doEndnotes"         => 5,
            "doPageNums"         =>  9,
            "doRubies"           => 50,
            "doTcys"             => 50,
            );

        parent::__construct();

        if (!is_null($options)) {

            $options = $this->updateOptions($options);
            $this->config = array_replace_recursive($this->config, $options);

            $intProps = [
                "autoTcyDigit",
                "tcyDigit",
            ];

            foreach ($intProps as $prop) {
                if (array_key_exists($prop, $options)) {
                    if (is_int($options[$prop])) {
                        $this->$prop = $options[$prop];
                    } else {
                        trigger_error("${prop} must be integer.");
                    }
                }
            }

            $boolProps = [
                "aozoraRuby",
                "autoTcy",
                "autoTextOrientation",
                "epubType",
                "dpubRole",
                "ddmdTable",
                "ddmdEndnotes",
                "enhancedOrderedList",
                "hardWrap",
            ];

            foreach ($boolProps as $prop) {
                if (array_key_exists($prop, $options)) {
                    if (is_bool($options[$prop])) {
                        $this->$prop = $options[$prop];
                    } else {
                        trigger_error("${prop} must be boolean.");
                    }
                }
            }

            $stringProps = [
                "rubyParenthesisOpen",
                "rubyParenthesisClose",
                "ddmdTableWrapperClass",
                "footnoteIdPrefix",
                "footnoteLinkClass",
                "footnoteLinkContent",
                "footnoteBacklinkClass",
                "footnoteBacklinkContent",
                "endnotesHeadingContent",
                "endnotesHeadingTag",
                "endnoteIdPrefix",
                "endnoteLinkClass",
                "endnoteLinkTitle",
                "endnoteClass",
                "endnoteBacklinkClass",
                "endnoteBacklinkContent",
                "pageNumberClass",
                "pageNumberContent",
                "tableAlignClassTmpl",
                "targetEpubCheckVersion"
            ];

            foreach ($stringProps as $prop) {
                if (array_key_exists($prop, $options)) {
                    if (is_string($options[$prop])) {
                        $this->$prop = $options[$prop];
                    } else {
                        trigger_error("${prop} must be string.");
                    }
                }
            }
        }

        if ($this->rubyParenthesisOpen !== "" && $this->rubyParenthesisClose !== "") {
            $this->rubyParenthesisOpen = \htmlspecialchars($this->rubyParenthesisOpen);
            $this->rubyParenthesisClose = \htmlspecialchars($this->rubyParenthesisClose);

            $this->rpOpen = "<rp>{$this->rubyParenthesisOpen}</rp>";
            $this->rpClose = "<rp>{$this->rubyParenthesisClose}</rp>";
        } else {
            $this->rpOpen = "";
            $this->rpClose = "";
        }

        if (empty($this->targetEpubCheckVersion)) {
            $this->endnotesEpubType = "endnotes";
            $this->endnoteEpubType = "endnote";
        } else {
            if (version_compare($this->targetEpubCheckVersion, "4.1.0", '<=')) {
                $this->endnotesEpubType = "rearnotes";
                $this->endnoteEpubType = "rearnote";
            } else {
                $this->endnotesEpubType = "endnotes";
                $this->endnoteEpubType = "endnote";
            }
        }
    }

    protected function setAlias()
    {
        $alias_pairs = [
            ["hard_wrap", "hardWrap"],
            ["enhanced_ordered_list", "enhancedOrderedList"],
            ["fn_id_prefix", "footnoteIdPrefix"],
            ["fn_link_class", "footnoteLinkClass"],
            ["fn_backlink_class", "footnoteBacklinkClass"],
            ["fn_backlink_html", "footnoteBacklinkContent"],
            ["table_align_class_tmpl", "tableAlignClassTmpl"]
        ];

        foreach ($alias_pairs as $pair) {
            $org = $pair[0];
            $alias = $pair[1];
            $this->$org =& $this->$alias;
        }
    }

    public function setBacktrakLimit(int $limit)
    {
        ini_set('pcre.backtrack_limit', $limit);
    }

    # Tags that are always treated as block tags:
    protected $block_tags_re = 'address|article|aside|blockquote|body|center|dd|details|dialog|dir|div|dl|dt|fieldset|figcaption|figure|footer|form|h[1-6]|header|hgroup|hr|html|iframe|legend|listing|menu|nav|ol|p|plaintext|pre|section|style|summary|table|ul|xmp';

    # Tags where markdown="1" default to span mode:
    protected $contain_span_tags_re = 'p|h[1-6]|li|dd|dt|td|th|legend|address';

    # Override transform()
    public function transform($text)
    {
        $text = \Denshoch\Utils::removeCtrlChars($text);

        $text = parent::transform($text);

        $harusame = new \Denshoch\Harusame(
            array(
                "autoTcy" => $this->autoTcy,
                "tcyDigit" => $this->tcyDigit,
                "autoTextOrientation" => $this->autoTextOrientation
            )
        );
        $text_org = $text;

        // for web app
        if(!defined('STDERR')) define('STDERR', fopen('php://stderr', 'wb'));
        try {
            $text = $harusame->transform($text_org);
        } catch (\ErrorException $e) {
            fputs(STDERR, $e->getMessage() . "\n");
            fputs(STDERR, "DenDenMarkdown::transform(): Skip Harusame.\n");
            $text = $text_org;
            unset($text_org);
        }

        try {
            $text = $this->addClass($text);
        } catch (\ErrorException $e) {
            fputs(STDERR, $e->getMessage() . "\n");
            fputs(STDERR, "DenDenMarkdown::transform(): Skip addClass().\n");
        }

        /* Reset Endnotes count */
        $this->endnotes_ref_count = array();
        $this->endnotes_numbers = array();

        return $text;
    }

    protected function doBlockTitles($text)
    {
        # block titles:
        #   .BLOCK TITLE {#title1}
        #
        $text = preg_replace_callback(
            '{
                ^(\.)       # $1 = string of \.
                [ ]*
                (.+?)       # $2 = TITLE text
                [ ]*
                (?:[ ]+ '.$this->id_class_attr_catch_re.' )?     # $3 = id/class attributes
                [ ]*
                \n+
            }xm',
            array(&$this, '_doBlockTitles_callback'),
            $text
        );

        return $text;
    }

    protected function _doBlockTitles_callback($matches)
    {
        $level = strlen($matches[1]);
        $dummy =& $matches[3];

        if ($this->optionalheader_class != "") {
            $dummy .= ".$this->optionalheader_class";
        }
        $attr  = $this->doExtraAttributes("p", $dummy);
        if ($this->epubType) {
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
            $text
        );
    }

    protected function doBlockPageNums($text)
    {

        $pagebreak_block_reg = '/^[ ]{0,3}\[(%)(%?)(.+?)\][ ]*\n+/m';
        $text = preg_replace_callback($pagebreak_block_reg, array(&$this, '_doPageNumsBlock_callback'), $text);
        $this->checkPregReplaceCallback($text);

        return $text;
    }

    protected function doPageNums($text)
    {
        $pagebreak_reg = '/\[(%)(%?)(.+?)\]/m';
        $text = preg_replace_callback($pagebreak_reg, array(&$this, '_doPageNums_callback'), $text);
        $this->checkPregReplaceCallback($text);

        return $text;
    }

    protected function _doPageNumsBlock_callback($matches)
    {
        $title = $matches[3];

        if ("%" == $matches[2]) {
            $content = str_replace("%%", $title, $this->pageNumberContent);
            $content = $this->htmlEscapeWithoutEntityRef($content);
        } else {
            $content = '';
        }
        $title = $this->encodeAttribute($title);

        $attr = "";
        $id = "pagenum_${title}";
        $attr .= " id=\"$id\"";
        if ($this->pageNumberClass != "") {
            $class = $this->encodeAttribute($this->pageNumberClass);
            $attr .= " class=\"$class\"";
        }
        $attr .= " title=\"$title\"";
        if ($this->epubType) {
            $attr .= " epub:type=\"pagebreak\"";
        }

        if ($this->dpubRole) {
            $attr .= " role=\"doc-pagebreak\"";
        }

        $result = "<div$attr>";
        $result .=  $content;
        $result .= "</div>";

        return "\n".$this->hashBlock($result)."\n\n";
    }

    protected function _doPageNums_callback($matches)
    {
        $title = $matches[3];

        if ("%" == $matches[2]) {
            $content = str_replace("%%", $title, $this->pageNumberContent);
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
        if ($this->epubType) {
            $attr .= " epub:type=\"pagebreak\"";
        }

        if ($this->dpubRole) {
            $attr .= " role=\"doc-pagebreak\"";
        }

        $result = "<span$attr>";
        $result .=  $content;
        $result .= "</span>";

        return $this->hashPart($result);
    }

    protected function doAutoLinks($text)
    {
        $text = preg_replace_callback(
            '{<((https?|ftp|dict):[^\'">\s]+)>}i',
            array($this, '_doAutoLinks_url_callback'),
            $text
        );

        # Email addresses: <address@domain.foo>
        $text = preg_replace_callback(
            '{
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
            array($this, '_doAutoLinks_email_callback'),
            $text
        );

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
    protected function mb_str_split($str, $enc, $length = 1)
    {
        if ($length <= 0) {
            return false;
        }
        $result = array();
        for ($i = 0, $idx = 0; $i < mb_strlen($str, $enc); $i += $length) {
            $result[$idx++] = mb_substr($str, $i, $length, $enc);
        }
        return $result;
    }

    protected function doRubies($text)
    {
        if ($this->aozoraRuby) {
            return $this->doRubiesAozora($text);
        }

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
            array($this,'doRubies_Callback'),
            $text
        );

        return $text;
    }

    protected function doRubiesAozora($text)
    {
        $text = preg_replace_callback(
            '/｜([^《。、]+?)《([^\s》]+)》/u',
            array($this,'doRubiesAozora_Callback'),
            $text
        );
        $text = preg_replace_callback(
            '/｜?([\p{Han}仝々〆〇ヶ]+?|\p{Latin}+?)《([^\s》]+)》/u',
            array($this,'doRubiesAozora_Callback'),
            $text
        );
        return $text;
    }

    protected function doRubies_Callback($matches)
    {
        $result = "<ruby>";
        $rbarray = $this->mb_str_split($matches[2], 'UTF-8');
        $rbcount = count($rbarray);
        $rtarray = explode("|", $matches[3]);
        $rtcount = count($rtarray);

        if ($rbcount == $rtcount) {
            for ($i=0, $idx=0; $i < $rbcount; $i++) {
                $result = "${result}${rbarray[$idx]}{$this->rpOpen}<rt>${rtarray[$idx]}</rt>{$this->rpClose}";
                $idx++;
            }

            $result = $result."</ruby>";
        } else {
            $result = "${result}${matches[2]}{$this->rpOpen}<rt>".join('', $rtarray)."</rt>{$this->rpClose}</ruby>";
        }

        return $result;
    }

    protected function doRubiesAozora_Callback($matches)
    {
        $result = "<ruby>";
        $result .= $matches[1];
        $result .= $this->rpOpen;
        $result .= "<rt>";
        $result .= $matches[2];
        $result .= "</rt>";
        $result .= $this->rpClose;
        $result .= "</ruby>";

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
            '<span class="tcy">\2</span>',
            $text
        );

        return $text;
    }

    //custum footnote code
    protected function appendFootnotes($text)
    {
    #
    # Append footnote list to text.
    #
        $text = preg_replace_callback(
            '{F\x1Afn:(.*?)\x1A:}',
            array($this, '_appendFootnotes_callback'),
            $text
        );

        if (!empty($this->footnotes_ordered)) {
            $text .= "\n\n";
            $text .= "<div class=\"footnotes\"";

            if ($this->epubType) {
                $text .= " epub:type=\"footnotes\"";
            }

            $text .= ">\n";
            $text .= "<hr". $this->empty_element_suffix ."\n";
            $text .= "<ol>\n\n";

            $attr = "";

            if ($this->footnoteBacklinkClass != "") {
                $class = $this->encodeAttribute($this->footnoteBacklinkClass);
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
                $footnote = preg_replace_callback(
                    '{F\x1Afn:(.*?)\x1A:}',
                    array($this, '_appendFootnotes_callback'),
                    $footnote
                );
                $this->checkPregReplaceCallback($footnote);

                ++$num;
                $attr = str_replace("%%", $num, $attr);
                $note_id = $this->encodeAttribute($note_id);
                $content = str_replace("%%", $num, $this->footnoteBacklinkContent);
                $content = $this->htmlEscapeWithoutEntityRef($content);

                # Prepare backlink, multiple backlinks if multiple references
                $backlink = "<a href=\"#fnref_${note_id}\"${attr}>${content}</a>";
                for ($ref_num = 2; $ref_num <= $ref_count; ++$ref_num) {
                    $backlink .= " <a href=\"#fnref$ref_num_${note_id}\"${attr}>${content}</a>";
                }
                # Add backlink to last paragraph; create new paragraph if needed.
                if (preg_match('{</p>$}', $footnote)) {
                    $footnote = substr($footnote, 0, -4) . "&#160;${backlink}</p>";
                } else {
                    $footnote .= "\n\n<p>${backlink}</p>";
                }

                $text .= "<li>\n";
                $text .= "<div id=\"fn_$note_id\" class=\"footnote\"";

                if ($this->epubType) {
                    $text .= " epub:type=\"footnote\"";
                }

                if ($this->dpubRole) {
                    $text .= " role=\"doc-footnote\"";
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
                $class = $this->encodeAttribute($this->footnoteLinkClass);
                $attr .= " class=\"${class}\"";
            }

            if ($this->fn_link_title != "") {
                $title = $this->fn_link_title;
                $title = $this->encodeAttribute($title);
                $attr .= " title=\"$title\"";
            }

            if ($this->epubType) {
                $attr .= " epub:type=\"noteref\"";
            }

            if ($this->dpubRole) {
                $attr .= " role=\"doc-noteref\"";
            }

            $attr = str_replace("%%", $num, $attr);
            $content = str_replace("%%", $num, $this->footnoteLinkContent);
            $content = $this->htmlEscapeWithoutEntityRef($content);

            return
                "<a${attr}>${content}</a>"
                ;
        }

        return "[^".$matches[1]."]";
    }

    /* == Endnotes code == */

    /**
     * stripEndnotes
     *
     * Strips link definitions from text, stores the URLs and titles in
     * hash references.
     *
     * @param $input string
     * @return string
     */
    protected function stripEndnotes($text)
    {

        if ($this->ddmdEndnotes === false) {
            return $text;
        }

        $less_than_tab = $this->tab_width - 1;

        # Link defs are in the form: [~id]: url "optional title"
        $text = preg_replace_callback(
            '{
            ^[ ]{0,'.$less_than_tab.'}\[~(.+?)\][ ]?:  # note_id = $1
              [ ]*
              \n?                   # maybe *one* newline
            (                       # text = $2 (no blank lines allowed)
                (?:
                    .+              # actual text
                |
                    \n              # newlines but
                    (?!\[.+?\][ ]?:\s)# negative lookahead for endnote or link definition marker.
                    (?!\n+[ ]{0,3}\S)# ensure line is not blank and followed
                                    # by non-indented content
                )*
            )
            }xm',
            array($this, '_stripEndnotes_callback'),
            $text
        );
        $this->checkPregReplaceCallback($text);

        return $text;
    }

    /**
     * _stripEndnotes_callback
     *
     * @param $matches array
     * @return string
     */
    protected function _stripEndnotes_callback($matches)
    {
        $note_id = $this->endnoteIdPrefix . $matches[1];
        $this->endnotes[$note_id] = $this->outdent($matches[2]);

        return ''; # String that will replace the block
    }

    /**
     * doEndnotes
     *
     * @param $input string
     * @return string
     */
    protected function doEndnotes($text)
    {
        if ($this->ddmdEndnotes === false) {
            return $text;
        }

        $text = preg_replace_callback(
            '{
            (               # wrap whole match in $1
              \[
                ('.$this->nested_brackets_re.')     # link text = $2
              \]

              [ ]?              # one optional space
              (?:\n[ ]*)?       # one optional newline followed by spaces

              \[
                ~(.+?)       # id = $3
              \]

            )
            }xs',
            array($this, '_doEndnotes_reference_callback'),
            $text
        );
        $this->checkPregReplaceCallback($text);

        return $text;
    }

    protected function _doEndnotes_reference_callback($matches)
    {
        $whole_match = $matches[1];
        $link_text = $matches[2];
        $node_id = $matches[3];

        if (!$this->in_anchor) {
            return "E\x1Aen:${node_id}\x1A${link_text}\x1A:";
        }

        return $whole_match;
    }

    /**
     * Append Endnotes to text
     *
     * @param $input string
     * @return string
     */
    protected function appendEndnotes($text)
    {
        if ($this->ddmdEndnotes === false) {
            return $text;
        }

        $text = preg_replace_callback(
            '{E\x1Aen:(.*?)\x1A(.*?)\x1A:}',
            array($this, '_appendEndnotes_callback'),
            $text
        );
        $this->checkPregReplaceCallback($text);

        if (!empty($this->endnotes_ordered)) {
            $text .= "\n\n";
            $text .= "<div class=\"endnotes\"";
            if ($this->epubType) {
                $text .= " epub:type=\"{$this->endnotesEpubType}\"";
            }
            if ($this->dpubRole) {
                $text .=" role=\"doc-endnotes\"";
            }
            $text .= ">\n";
            $text .= "<hr". $this->empty_element_suffix ."\n\n";

            if ($this->endnotesHeadingContent !== "") {
                $content = $this->htmlEscapeWithoutEntityRef($this->endnotesHeadingContent);
                $text .= "<{$this->endnotesHeadingTag}>${content}</{$this->endnotesHeadingTag}>\n\n";
            }

            $attr = "";

            if ($this->endnoteBacklinkClass !== "") {
                $class = $this->encodeAttribute($this->endnoteBacklinkClass);
                $attr .= " class=\"${class}\"";
            }

            if ($this->dpubRole) {
                $attr .= " role=\"doc-backlink\"";
            }

            $num = 0;

            while (!empty($this->endnotes_ordered)) {
                $endnote = reset($this->endnotes_ordered); // get first endnote content
                $note_id = key($this->endnotes_ordered); // get first endnote id
                unset($this->endnotes_ordered[$note_id]); // remove endnote from stack
                $ref_count = $this->endnotes_ref_count[$note_id];
                unset($this->endnotes_ref_count[$note_id]);
                unset($this->endnotes[$note_id]);

                $endnote .= "\n";
                $endnote = $this->runBlockGamut("$endnote\n");
                $endnote = preg_replace_callback(
                    '{E\x1Aen:(.*?)\x1A(.*?)\x1A:}',
                    array($this, '_appendEndnotes_callback'),
                    $endnote
                );

                $attr = str_replace("%%", ++$num, $attr);
                $note_id = $this->encodeAttribute($note_id);

                $content = $this->htmlEscapeWithoutEntityRef($this->endnoteBacklinkContent);

                $backlink = "<a href=\"#enref_$note_id\"$attr>${content}</a>";

                for ($ref_num = 2; $ref_num <= $ref_count; ++$ref_num) {
                    $backlink .= " <a href=\"#enref$ref_num_$note_id\"$attr>${content}</a>";
                }

                # Add backlink to last paragraph; create new paragraph if needed.
                $para_cnt = preg_match_all('{</p>}', $endnote, $matches);
                if (preg_match('{</p>$}', $endnote) && $para_cnt === 1) {
                    $endnote = substr($endnote, 0, -4) . "&#160;${backlink}</p>";
                } else {
                    $endnote .= "\n\n<p>${backlink}</p>";
                }

                $text .= "<div id=\"en_$note_id\"";
                if ($this->endnoteClass !== "") {
                    $class = $this->encodeAttribute($this->endnoteClass);
                    $text .= " class=\"${class}\"";
                }
                if ($this->epubType) {
                    $text .= " epub:type=\"{$this->endnoteEpubType}\"";
                }
                $text .= ">\n";
                $text .= $endnote . "\n";
                $text .= "</div>\n\n";
            }

            $text .= "</div>";
        }
        $this->checkPregReplaceCallback($text);

        return $text;
    }

    /**
     * _appendEndnotes_callback
     * リンク元
     *
     * @param $matches array
     * @return string
     */
    protected function _appendEndnotes_callback($matches)
    {

        $node_id = $this->endnoteIdPrefix . $matches[1];
        $link_text = $matches[2];

        # Create endnote marker only if it has a corresponding endnote *and*
        # the end hasn't been used by another marker.
        if (isset($this->endnotes[$node_id])) {
            $num =& $this->endnotes_numbers[$node_id];

            if (!isset($num)) {
                # Transfer footnote content to the ordered list and give it its
                # number
                $this->endnotes_ordered[$node_id] = $this->endnotes[$node_id];
                $this->endnotes_ref_count[$node_id] = 1;
                $num = $this->endnote_counter++;
                $ref_count_mark = '';
            } else {
                $ref_count_mark = $this->endnotes_ref_count[$node_id] += 1;
            }

            $attr = "";

            if ($this->endnoteLinkClass != "") {
                $class = $this->encodeAttribute($this->endnoteLinkClass);
                $attr .= " class=\"$class\"";
            }

            if ($this->endnoteLinkTitle != "") {
                $title = $this->encodeAttribute($this->endnoteLinkTitle);
                $attr .= " title=\"$title\"";
            }

            if ($this->epubType) {
                $attr .= " epub:type=\"noteref\"";
            }

            if ($this->dpubRole) {
                $attr .= " role=\"doc-noteref\"";
            }

            $attr = str_replace("%%", $num, $attr);
            $node_id = $this->encodeAttribute($node_id);

            return "<a id=\"enref${ref_count_mark}_${node_id}\" href=\"#en_${node_id}\"${attr}>${link_text}</a>";
        }

        return "[${matches[2]}][~${matches[1]}]";
    }

    /**
     * doTables
     *
     * Form HTML tables. If `ddmdTable` is true, parse DDmdTable syntax.
     *
     * @param $text string
     * @return string
     */
    protected function doTables($text)
    {
        if ($this->ddmdTable) {
            return $this->doDDmdTables($text);
        }

        return parent::doTables($text);
    }

    /**
     * doDDmdTables
     *
     * Form HTML tables written in DDmdTable syntax.
     *
     * @param $text string
     * @return string
     */
    protected function doDDmdTables($text)
    {
        $less_than_tab = $this->tab_width - 1;

        /* Find tables with leading pipe */
        $ls_re = "[ ]{0,$less_than_tab}";
        $caption_re = '(?:'.$ls_re.'\|=\.[ ]*(?<caption>[^|]+?)\n)?';
        $thead_re = '(?<thead>(?>'.$ls_re.'[|].*[|].*\n)+?)';
        $ul_re = '(?>(?<underline>'.$ls_re.'[|][ ]*[-:]+[ ]*[|][-| :]*\n))';
        $tbody_re = '(?<tbody>(?>'.$ls_re.'[|].*[|].*\n)+?)';
        $nl_re = '(?=\n|\Z)'; # newlines
        $pattern = '/^'.$caption_re.$thead_re.$ul_re.$tbody_re.$nl_re.'/m';

        $text = preg_replace_callback($pattern, array($this, '_doDDmdTable_leadingpipe_callback'), $text);
        $this->checkPregReplaceCallback($text);

        /* Find tables without leading pipe */
        $thead_re = '(?<thead>(?>'.$ls_re.'\S.*[|].*\n)+?)';
        $ul_re = '(?>(?<underline>'.$ls_re.'[-:]+[ ]*[|][-| :]*\n))';
        $tbody_re = '(?<tbody>(?>'.$ls_re.'.*[|].*\n)+?)';
        $pattern = '/^'.$caption_re.$thead_re.$ls_re.$ul_re.$tbody_re.$nl_re.'/m';

        $text = preg_replace_callback($pattern, array($this, '_doDDmdTable_callback'), $text);
        $this->checkPregReplaceCallback($text);

        return $text;
    }

    /**
     * _doDDmdTable_leadingpipe_callback
     *
     * Form HTML tables written in DDmdTable syntax.
     *
     * @param $matches array
     * @return string
     */
    protected function _doDDmdTable_leadingpipe_callback($matches)
    {
        $matches['thead'] = preg_replace('/^ *[|]/m', '', $matches['thead']);
        $matches['underline'] = preg_replace('/^ *[|]/m', '', $matches['underline']);
        $matches['tbody'] = preg_replace('/^ *[|]/m', '', $matches['tbody']);
    
        return $this->_doDDmdTable_callback($matches);
    }

    /**
     * _doDDmdTable_callback
     *
     * Form HTML tables written in DDmdTable syntax.
     *
     * @param $matches array
     * @return string
     */
    protected function _doDDmdTable_callback($matches)
    {
        $col_count = 0; # col count of first row

        $algn_re = "(?P<algn>(?:\<(?!>)|&lt;&gt;|&gt;|&lt;|(?<!<)\>|\<\>|\=|[()]+(?! )))?";
        $cspn_re = "(?:\\\\(?P<colspan>[0-9]+?))";
        $rspn_re = "(?:\/(?P<rowspan>[0-9]+?))";
        $spn_re = "(?:${cspn_re}|${rspn_re})*?";
        $cattr = "(?P<cattr>_?${algn_re}${spn_re}\. )";

        # Remove any tailing pipes for each line.
        $matches['thead'] = preg_replace('/[|] *$/m', '', $matches['thead']);
        $matches['underline'] = preg_replace('/[|] *$/m', '', $matches['underline']);
        $matches['tbody'] = preg_replace('/[|] *$/m', '', $matches['tbody']);

        # Get col count of first row

        $thead_rows = explode("\n", trim($matches['thead'], "\n"));
        $first_row_cells = preg_split('/ *[|] */', $thead_rows[0]);
        
        foreach ($first_row_cells as $cell) {
            # add ' ' if column text is empty.
            $cell = preg_replace('/\.$/', '. ', $cell);
            if (preg_match("/^${cattr}(?P<cell>.*)/s", $cell, $mtch)) {
                $cspn = (int) $mtch['colspan'];
                if ($cspn > 1) {
                    $col_count += $cspn;
                } else {
                    $col_count += 1;
                }
            } else {
                $col_count += 1;
            }
        }

        # Reading alignement from header underline.
        $col_algn = [];
        $separators = preg_split('/ *[|] */', $matches['underline']);
        foreach ($separators as $n => $s) {
            if (preg_match('/^ *-+: *$/', $s)) {
                $col_algn[$n] = $this->_doDDmdTable_makeAlignAttr('right');
            } elseif (preg_match('/^ *:-+: *$/', $s)) {
                $col_algn[$n] = $this->_doDDmdTable_makeAlignAttr('center');
            } elseif (preg_match('/^ *:-+ *$/', $s)) {
                $col_algn[$n] = $this->_doDDmdTable_makeAlignAttr('left');
            } else {
                $col_algn[$n] = '';
            }
        }

        # Start write table
        $text = "";

        if ($this->ddmdTableWrapperClass !== "") {
            $text .= "<div";
            $text .= " class=\"" . \htmlspecialchars($this->ddmdTableWrapperClass) ."\"";
            $text .=  ">\n";
        }

        $text .= "<table>\n";

        # Write caption
        if (!empty($matches['caption'])) {
            $text .= "<caption>";
            $text .= $this->runSpanGamut(trim($matches['caption']));
            $text .= "</caption>\n";
        }

        # Write thead
        $text .= "<thead>\n";

        foreach ($thead_rows as $row) {
            $text .= "<tr>\n";
    
            $row_cells = preg_split('/ *[|] */', $row);
    
            # Process thead cells
            $c_cnt = 0;
    
            foreach ($row_cells as $cell) {
                $attr = '';
                $algn = '';
                $cell .= ' ';
    
                if (preg_match("/^${cattr}(?P<cell>.*)/s", $cell, $mtch)) {
                    if (!empty($mtch['algn'])) {
                        $algn = $this->_doDDmdTable_makeAlignAttr($mtch['algn']);
                    }

                    if ($cell !== ' ') {
                        if (preg_match("/_/", $mtch['cattr'])) {
                            $attr .= " scope=\"row\"";
                        } else {
                            $attr .= " scope=\"col\"";
                        }
                    }
    
                    $cspn = (int) $mtch['colspan'];
                    if ($cspn > 1) {
                        $attr .= " colspan=\"${cspn}\"";
                        $c_cnt += $cspn;
                    } else {
                        $c_cnt += 1;
                    }
    
                    $rspn = (int) $mtch['rowspan'];
                    if ($rspn > 1) {
                        $attr .= " rowspan=\"${rspn}\"";
                    }
    
                    $cell = $mtch['cell'];
                } else {
                    if (empty($algn)) {
                        if (empty($col_algn[$c_cnt])) {
                            $col_algn[$c_cnt] = '';
                        }
                        $algn = $col_algn[$c_cnt];
                    }
                    $attr .= " scope=\"col\"";
                    $c_cnt += 1;
                }
    
                $text .= "<th${algn}${attr}>";
                $text .= $this->runSpanGamut(trim($cell));
                $text .= "</th>\n";
            }
    
            $text .= "</tr>\n";
        }
        $text .= "</thead>\n";

        # Write tbody
        $tbody_rows = explode("\n", trim($matches['tbody'], "\n"));

        $text .= "<tbody>\n";
    
        foreach ($tbody_rows as $row) {
            $text .= "<tr>\n";
        
            $row_cells = preg_split('/ *[|] */', $row, $col_count);
        
            # Process tbody cells
            $c_cnt = 0;
        
            foreach ($row_cells as $cell) {
                $tag = 'td';
                $attr = '';
                $algn = '';
                $cell .= ' ';
        
                if (preg_match("/^${cattr}(?P<cell>.*)/s", $cell, $mtch)) {
                    if (preg_match("/_/", $mtch['cattr'])) {
                        $tag = 'th';
                        $attr = " scope=\"row\"";
                    }
    
                    if (!empty($mtch['algn'])) {
                        $algn = $this->_doDDmdTable_makeAlignAttr($mtch['algn']);
                    }
    
                    $cspn = (int) $mtch['colspan'];
                    if ($cspn > 1) {
                        $attr .= " colspan=\"${cspn}\"";
                        $c_cnt += $cspn;
                    } else {
                        $c_cnt += 1;
                    }
        
                    $rspn = (int) $mtch['rowspan'];
                    if ($rspn > 1) {
                        $attr .= " rowspan=\"${rspn}\"";
                    }
        
                    $cell = $mtch['cell'];
                } else {
                    if (empty($algn)) {
                        $algn = $col_algn[$c_cnt];
                    }
                    $c_cnt += 1;
                }
        
                $text .= "<${tag}${algn}${attr}>";
                $text .= $this->runSpanGamut(trim($cell));
                $text .= "</${tag}>\n";
            }
            $text .= "</tr>\n";
        }
        $text .= "</tbody>\n";
        $text .= "</table>";

        if ($this->ddmdTableWrapperClass !== "") {
            $text .= "\n</div>";
        }

        return $this->hashBlock($text) . "\n";
    }

    /**
     * _doDDmdTable_makeAlignAttr
     *
     * Form HTML tables written in DDmdTable syntax.
     *
     * @param $matches array
     * @return string
     */
    protected function _doDDmdTable_makeAlignAttr($alignname)
    {
        switch ($alignname) {
            case "<":
                $alignname = "left";
                break;
            case ">":
                $alignname = "right";
                break;
            case "=":
                $alignname = "center";
                break;
        }

        if (empty($this->tableAlignClassTmpl)) {
            return " align=\"$alignname\"";
        }

        $classname = str_replace('%%', $alignname, $this->tableAlignClassTmpl);
        return " class=\"$classname\"";
    }

    protected function checkPregReplaceCallback($text)
    {
        if (is_null($text)) {
            trigger_error("Error occured in preg_replace_callback");
        }
    }

	/**
     * Override the parent method to add support for the `markdown="1"` and `md` attribute.
	 * Parse HTML, calling _HashHTMLBlocks_InMarkdown for block tags.
	 *
	 * *   Calls $hash_method to convert any blocks.
	 * *   Stops when the first opening tag closes.
	 * *   $md_attr indicate if the use of the `markdown="1"` attribute is allowed.
	 *     (it is not inside clean tags)
	 *
	 * Returns an array of that form: ( processed text , remaining text )
	 * @param  string $text
	 * @param  string $hash_method
	 * @param  bool $md_attr Handle `markdown="1"` and `md` attribute
	 * @return array
	 */
	protected function _hashHTMLBlocks_inHTML($text, $hash_method, $md_attr) {
		if ($text === '') return array('', '');

		// Regex to match `markdown` attribute inside of a tag.
		$markdown_attr_re = '
			{
				\s*			# Eat whitespace before the `markdown` attribute
				(?:markdown|md) # matche markdown or md attribute
				\s*=\s*
				(?>
					(["\'])		# $1: quote delimiter
					(.*?)		# $2: attribute value
					\1			# matching delimiter
				|
					([^\s>]*)	# $3: unquoted attribute value
				)
				()				# $4: make $3 always defined (avoid warnings)
			}xs';

		// Regex to match any tag.
		$tag_re = '{
				(					# $2: Capture whole tag.
					</?					# Any opening or closing tag.
						[\w:$]+			# Tag name.
						(?:
							(?=[\s"\'/a-zA-Z0-9])	# Allowed characters after tag name.
							(?>
								".*?"		|	# Double quotes (can contain `>`)
								\'.*?\'   	|	# Single quotes (can contain `>`)
								.+?				# Anything but quotes and `>`.
							)*?
						)?
					>					# End of tag.
				|
					<!--    .*?     -->	# HTML Comment
				|
					<\?.*?\?> | <%.*?%>	# Processing instruction
				|
					<!\[CDATA\[.*?\]\]>	# CData Block
				)
			}xs';

		$original_text = $text;		// Save original text in case of faliure.

		$depth		= 0;	// Current depth inside the tag tree.
		$block_text	= "";	// Temporary text holder for current text.
		$parsed		= "";	// Parsed text that will be returned.
		$base_tag_name_re = '';

		// Get the name of the starting tag.
		// (This pattern makes $base_tag_name_re safe without quoting.)
		if (preg_match('/^<([\w:$]*)\b/', $text, $matches))
			$base_tag_name_re = $matches[1];

		// Loop through every tag until we find the corresponding closing tag.
		do {
			// Split the text using the first $tag_match pattern found.
			// Text before  pattern will be first in the array, text after
			// pattern will be at the end, and between will be any catches made
			// by the pattern.
			$parts = preg_split($tag_re, $text, 2, PREG_SPLIT_DELIM_CAPTURE);

			if (count($parts) < 3) {
				// End of $text reached with unbalenced tag(s).
				// In that case, we return original text unchanged and pass the
				// first character as filtered to prevent an infinite loop in the
				// parent function.
				return array($original_text[0], substr($original_text, 1));
			}

			$block_text .= $parts[0]; // Text before current tag.
			$tag         = $parts[1]; // Tag to handle.
			$text        = $parts[2]; // Remaining text after current tag.

			// Check for: Auto-close tag (like <hr/>)
			//			 Comments and Processing Instructions.
			if (preg_match('{^</?(?:' . $this->auto_close_tags_re . ')\b}', $tag) ||
				$tag[1] === '!' || $tag[1] === '?')
			{
				// Just add the tag to the block as if it was text.
				$block_text .= $tag;
			}
			else {
				// Increase/decrease nested tag count. Only do so if
				// the tag's name match base tag's.
				if (preg_match('{^</?' . $base_tag_name_re . '\b}', $tag)) {
					if ($tag[1] === '/') {
						$depth--;
					} else if ($tag[strlen($tag)-2] !== '/') {
						$depth++;
					}
				}

				// Check for `markdown="1"` attribute and handle it.
				if ($md_attr &&
					preg_match($markdown_attr_re, $tag, $attr_m) &&
					preg_match('/^1|block|span$/', $attr_m[2] . $attr_m[3]))
				{
					// Remove `markdown` attribute from opening tag.
					$tag = preg_replace($markdown_attr_re, '', $tag);

					// Check if text inside this tag must be parsed in span mode.
					$mode = $attr_m[2] . $attr_m[3];
					$span_mode = $mode === 'span' || ($mode !== 'block' &&
						preg_match('{^<(?:' . $this->contain_span_tags_re . ')\b}', $tag));

					// Calculate indent before tag.
					if (preg_match('/(?:^|\n)( *?)(?! ).*?$/', $block_text, $matches)) {
						$strlen = $this->utf8_strlen;
						$indent = $strlen($matches[1], 'UTF-8');
					} else {
						$indent = 0;
					}

					// End preceding block with this tag.
					$block_text .= $tag;
					$parsed .= $this->$hash_method($block_text);

					// Get enclosing tag name for the ParseMarkdown function.
					// (This pattern makes $tag_name_re safe without quoting.)
					preg_match('/^<([\w:$]*)\b/', $tag, $matches);
					$tag_name_re = $matches[1];

					// Parse the content using the HTML-in-Markdown parser.
					list ($block_text, $text)
						= $this->_hashHTMLBlocks_inMarkdown($text, $indent,
							$tag_name_re, $span_mode);

					// Outdent markdown text.
					if ($indent > 0) {
						$block_text = preg_replace("/^[ ]{1,$indent}/m", "",
													$block_text);
					}

					// Append tag content to parsed text.
					if (!$span_mode) {
						$parsed .= "\n\n$block_text\n\n";
					} else {
						$parsed .= (string) $block_text;
					}

					// Start over with a new block.
					$block_text = "";
				}
				else $block_text .= $tag;
			}

		} while ($depth > 0);

		// Hash last block text that wasn't processed inside the loop.
		$parsed .= $this->$hash_method($block_text);

		return array($parsed, $text);
	}


    /**
     * htmlEscapeWithoutEntityRef
     *
     * 実体参照以外に対してhtmlspecialcharsを行う
     *
     * @param $text string
     * @return string
     */
    public function htmlEscapeWithoutEntityRef($text)
    {
        $out = "";
        $reg = '/(&#?[a-z0-9]{2,8};)/i';
        $arr = \preg_split($reg, $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach ($arr as $item) {
            if (\preg_match($reg, $item) === 1) {
                $out .= $item;
            } else {
                $out .= \htmlspecialchars($item);
            }
        }

        return $out;
    }

    /**
     * configure
     *
     * @param array $config
     * @return void
     */
    public function configue(array $config) :void
    {
        $this->config = array_replace_recursive($this->config, $config);
    }

    /**
     * updateOptions
     *
     * 後方互換性のために$optionsに変更を加える。
     *
     * @param $options array
     * @return array
     */
    protected function updateOptions(array $options) :array
    {
        if (array_key_exists('autoTcyDigit', $options))
        {
            if (!is_int($options['autoTcyDigit'])) {
                trigger_error("autoTcyDigit must be integer.");
            }

            if ( $options['autoTcyDigit'] === 0) {
                $options['autoTcy'] = false;
            } else {
                $options['autoTcy'] = true;
                $options['tcyDigit'] = $options['autoTcyDigit'];
            }
        }

        return $options;
    }

    /**
     * addClass
     * 
     * @param string $text
     * @return string
     */
    protected function addClass(string $text) :string
    {
        // DOMDocument::loadXML(): Empty string supplied as input を回避するため
        if ( preg_replace('/\s+/', '', $text) === '') {
            return $text;
        }

        if (!isset($this->config['addClass']) || is_null($this->config['addClass'])) {
            return $text;
        }
        
        return \Denshoch\HtmlModifier::addClassMultiple($text, $this->config['addClass']);
    }
}
