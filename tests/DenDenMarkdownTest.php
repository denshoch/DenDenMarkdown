<?php
require_once 'vendor/autoload.php';

class DenDenMarkdownTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->parser = new Denshoch\DenDenMarkdown;
        $this->fixtureDir = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures';
    }

    public function testAbbr()
    {
        $this->assertTransformation('abbr');
    }

    public function testParagraph()
    {
        $this->assertTransformation('paragraph');
    }

    public function testBrInParagraph()
    {
        $this->assertTransformation('br-in-paragraph');
    }

    public function testIndent()
    {
        $this->assertTransformation('indent');
    }

    public function testHeadingsAtx()
    {
        $this->assertTransformation('headings-atx');
    }

    public function testHeadingsSetext()
    {
        $this->assertTransformation('headings-setext');
    }

    public function testBlockTitles()
    {
        $this->assertTransformation('block-titles');
    }

    public function testHeadingsSetextNumHyphens()
    {
        $this->assertTransformedFile('headings-setext.html', 'headings-setext-hyphens.md');
    }

    public function testBlockQuote()
    {
        $this->assertTransformation('blockquote');
    }

    public function testBlockQuoteNest()
    {
        $this->assertTransformation('blockquote-nest');
    }

    public function testUlAsterisk()
    {
        $this->assertTransformedFile('ul.html', 'ul-asterisk.md');
    }

    public function testUlPlus()
    {
        $this->assertTransformedFile('ul.html', 'ul-plus.md');
    }

    public function testUlHyphen()
    {
        $this->assertTransformedFile('ul.html', 'ul-hyphen.md');
    }

    public function testOl()
    {
        $this->assertTransformation('ol');
    }

    public function testOlOutOfOrder()
    {
        $this->assertTransformedFile('ol.html', 'ol-out-of-order.md');
    }

    public function testOlEscape()
    {
        $this->assertTransformation('ol-escape');
    }

    public function testPharagraphInList()
    {
        $this->assertTransformation('paragraph-in-list');
    }

    public function testMultiParagraphsInLi()
    {
        $this->assertTransformation('multi-paragraphs-in-li');
    }

    public function testBlockquoteInList()
    {
        $this->assertTransformation('blockquote-in-list');
    }

    public function testCodeInList()
    {
        $this->assertTransformation('code-in-list');
    }

    public function testCodeBlock()
    {
        $this->assertTransformation('code-block');
    }

    public function testCodeInline()
    {
        $this->assertTransformation('code-inline');
    }

    public function testHr()
    {
        $this->assertTransformation('hr');
    }

    public function testDocBreak()
    {
        $this->assertTransformation('docbreak');
    }

    public function testPageNumber()
    {
        $this->assertTransformation('page-number');
    }

    public function testPageBreakInline()
    {
        $this->assertTransformation('page-break-inline');
    }

    public function testPageNumberText()
    {
        $this->assertTransformation('page-number-text');
    }

    public function testInlineLink()
    {
        $this->assertTransformation('inline-link');
    }

    public function testInlineLinkTitle()
    {
        $this->assertTransformation('inline-link-title');
    }

    public function testReferenceLink()
    {
        $this->assertTransformation('reference-link');
    }

    public function testReferenceLinkTitle()
    {
        $this->assertTransformation('reference-link-title');
    }

    public function testReferenceLinkNoId()
    {
        $this->assertTransformation('reference-link-no-id');
    }

    public function testAutolink()
    {
        $expected = $this->fixture('autolink.html');
        $source = $this->fixture('autolink.md');
        $actual = $this->parser->transform($source);
        $this->assertSame(html_entity_decode($expected), html_entity_decode($actual));
    }

    public function testAutolinkTwitter()
    {
        $this->assertTransformation('autolink-twitter');
    }

    public function testEmphasis()
    {
        $this->assertTransformation('emphasis');
    }

    public function testEmphasisUnderscore()
    {
        $this->assertTransformedFile('emphasis.html', 'emphasis-underscore.md');
    }

    public function testUnderscoreInQuotation()
    {
        $this->assertTransformation('underscore-in-quotation');
    }

    public function testGroupRuby()
    {
        $this->assertTransformation('group-ruby');
    }

    public function testMonoRuby()
    {
        $this->assertTransformation('mono-ruby');
    }

    public function testBraceFollowingVerticalLineNotTransformedToRuby()
    {
        $source = 'これは段落です。foo{|bar| bar.buz} これは段落です。';
        $transformed = $this->parser->transform($source);
        $this->assertNotRegExp('/<ruby>/', $transformed);
    }

    public function testEscapeRuby()
    {
        $source = 'これは段落です。\{Info\|Warning\} これは段落です。';
        $transformed = $this->parser->transform($source);
        $this->assertNotRegExp('/<ruby>/', $transformed);
    }

    public function testEscapeRubyVerticalLine()
    {
        $source = 'これは段落です。{Info\|Warning} これは段落です。
';
        $transformed = $this->parser->transform($source);
        $this->assertNotRegExp('/<ruby>/', $transformed);
    }

    public function testEscapeRubyBackQuotes()
    {
        $source = 'これは段落です。`{Info|Warning}` これは段落です。';
        $transformed = $this->parser->transform($source);
        $this->assertNotRegExp('/<ruby>/', $transformed);
    }

    public function testTateChuYoko()
    {
        $this->assertTransformation('tate-chu-yoko');
    }

    public function testFootnote()
    {
        $this->assertTransformation('footnote');
    }

    public function testMultiParagraphsInFootnote()
    {
        $this->assertTransformation('multi-paragraphs-in-footnote');
    }

    public function testMultiParagraphsInFootnoteLineBreakAtFirst()
    {
        $this->assertTransformedFile('multi-paragraphs-in-footnote.html', 'multi-paragraphs-in-footnote-line-break-at-first.md');
    }

    public function testFootnoteCannotReferenceAnotherPage()
    {
        $this->assertTransformation('footnote-cannot-reference-another-page');
    }

    public function testImage()
    {
        $this->assertTransformation('image');
    }

    public function testImageTitle()
    {
        $this->assertTransformation('image-title');
    }

    public function testImageNoAlt()
    {
        $this->assertTransformation('image-no-alt');
    }

    public function testImageNoAltTitle()
    {
        $this->assertTransformation('image-no-alt-title');
    }

    public function testImageReferenceLink()
    {
        $this->assertTransformation('image-reference-link');
    }

    public function testImageReferenceLinkTitle()
    {
        $this->assertTransformation('image-reference-link-title');
    }

    public function testDl()
    {
        $this->assertTransformation('dl');
    }

    public function testMultiDdInDl()
    {
        $this->assertTransformation('multi-dd-in-dl');
    }

    public function testParagraphInDd()
    {
        $this->assertTransformation('paragraph-in-dd');
    }

    public function testBlockquoteListCodeInDd()
    {
        $this->assertTransformation('blockquote-list-code-in-dd');
    }

    public function testTable()
    {
        $this->assertTransformation('table');
    }

    public function testHtmlTag()
    {
        $this->assertTransformation('html-tag');
    }

    public function testNotTransformedInHtmlBlock()
    {
        $this->assertTransformation('not-transformed-in-html-block');
    }

    public function testNotTransformedInMath()
    {
        $this->assertTransformation('not-transformed-in-math');
    }

    public function testNotTransformedInScript()
    {
        $this->assertTransformation('not-transformed-in-script');
    }

    public function testNotTransformedInStyle()
    {
        $this->assertTransformation('not-transformed-in-style');
    }

    public function testNotTransformedInSvg()
    {
        $this->assertTransformation('not-transformed-in-svg');
    }

    public function testMarkdownInHtmlBlock()
    {
        $this->assertTransformation('markdown-in-html-block');
    }

    public function testBlockTagAddress()
    {
        $this->assertTransformation('block-tag-address');
    }

    public function testBlockTagArticle()
    {
        $this->assertTransformation('block-tag-article');
    }

    public function testBlockTagAside()
    {
        $this->assertTransformation('block-tag-aside');
    }

    public function testBlockTagBlockquote()
    {
        $this->assertTransformation('block-tag-blockquote');
    }

    public function testBlockTagBody()
    {
        $this->assertTransformation('block-tag-body');
    }

    public function testBlockTagCenter()
    {
        $this->assertTransformation('block-tag-center');
    }

    public function testBlockTagDd()
    {
        $this->assertTransformation('block-tag-dd');
    }

    public function testBlockTagDetails()
    {
        $this->assertTransformation('block-tag-details');
    }

    public function testBlockTagDialog()
    {
        $this->assertTransformation('block-tag-dialog');
    }

    public function testBlockTagDir()
    {
        $this->assertTransformation('block-tag-dir');
    }

    public function testBlockTagDiv()
    {
        $this->assertTransformation('block-tag-div');
    }

    public function testBlockTagDl()
    {
        $this->assertTransformation('block-tag-dl');
    }

    public function testBlockTagDt()
    {
        $this->assertTransformation('block-tag-dt');
    }

    public function testBlockTagFigcaption()
    {
        $this->assertTransformation('block-tag-figcaption');
    }

    public function testBlockTagFigure()
    {
        $this->assertTransformation('block-tag-figure');
    }

    public function testBlockTagFooter()
    {
        $this->assertTransformation('block-tag-footer');
    }

    public function testBlockTagHn()
    {
        $this->assertTransformation('block-tag-hn');
    }

    public function testBlockTagHeader()
    {
        $this->assertTransformation('block-tag-header');
    }

    public function testBlockTagHgroup()
    {
        $this->assertTransformation('block-tag-hgroup');
    }

    public function testBlockTagHr()
    {
        $this->assertTransformation('block-tag-hr');
    }

    public function testBlockTagHtml()
    {
        $this->assertTransformation('block-tag-html');
    }

    public function testBlockTagLegend()
    {
        $this->assertTransformation('block-tag-legend');
    }

    public function testBlockTagListing()
    {
        $this->assertTransformation('block-tag-listing');
    }

    public function testBlockLTagMenu()
    {
        $this->assertTransformation('block-tag-menu');
    }

    public function testBlockLTagNav()
    {
        $this->assertTransformation('block-tag-nav');
    }

    public function testBlockTagOl()
    {
        $this->assertTransformation('block-tag-ol');
    }

    public function testBlockTagP()
    {
        $this->assertTransformation('block-tag-p');
    }

    public function testBlockTagPlaintext()
    {
        $this->assertTransformation('block-tag-plaintext');
    }

    public function testBlockTagPre()
    {
        $this->assertTransformation('block-tag-pre');
    }

    public function testBlockTagSection()
    {
        $this->assertTransformation('block-tag-section');
    }

    public function testBlockTagSummary()
    {
        $this->assertTransformation('block-tag-summary');
    }

    public function testBlockTagStyle()
    {
        $this->assertTransformation('block-tag-style');
    }

    public function testBlockTagTable()
    {
        $this->assertTransformation('block-tag-table');
    }

    public function testBlockTagUl()
    {
        $this->assertTransformation('block-tag-ul');
    }

    public function testBlockTagXmp()
    {
        $this->assertTransformation('block-tag-xmp');
    }

    public function testPhrasingContentTagAbbr()
    {
        $this->assertTransformation('phrasing_content_tag_abbr');
    }

    public function testPhrasingContentTagaAea()
    {
        $this->assertTransformation('phrasing_content_tag_area');
    }

    public function testPhrasingContentTagAudio()
    {
        $this->assertTransformation('phrasing_content_tag_audio');
    }

    public function testPhrasingContentTagB()
    {
        $this->assertTransformation('phrasing_content_tag_b');
    }

    public function testPhrasingContentTagBdi()
    {
        $this->assertTransformation('phrasing_content_tag_bdi');
    }

    public function testPhrasingContentTagBdo()
    {
        $this->assertTransformation('phrasing_content_tag_bdo');
    }

    public function testPhrasingContentTagBr()
    {
        $this->assertTransformation('phrasing_content_tag_br');
    }

    public function testPhrasingContentTagButton()
    {
        $this->assertTransformation('phrasing_content_tag_button');
    }

    public function testPhrasingContentTagCanvas()
    {
        $this->assertTransformation('phrasing_content_tag_canvas');
    }

    public function testPhrasingContentTagCite()
    {
        $this->assertTransformation('phrasing_content_tag_cite');
    }

    public function testPhrasingContentTagCode()
    {
        $this->assertTransformation('phrasing_content_tag_code');
    }

    public function testPhrasingContentTagCommand()
    {
        $this->assertTransformation('phrasing_content_tag_command');
    }

    public function testPhrasingContentTagDatalist()
    {
        $this->assertTransformation('phrasing_content_tag_datalist');
    }

    public function testPhrasingContentTagDel()
    {
        $this->assertTransformation('phrasing_content_tag_del');
    }

    public function testPhrasingContentTagDfn()
    {
        $this->assertTransformation('phrasing_content_tag_dfn');
    }

    public function testPhrasingContentTagEm()
    {
        $this->assertTransformation('phrasing_content_tag_em');
    }

    public function testPhrasingContentTagEmbed()
    {
        $this->assertTransformation('phrasing_content_tag_embed');
    }

    public function testPhrasingContentTagI()
    {
        $this->assertTransformation('phrasing_content_tag_i');
    }

    public function testPhrasingContentTagIframe()
    {
        $this->assertTransformation('phrasing_content_tag_iframe');
    }

    public function testPhrasingContentTagImg()
    {
        $this->assertTransformation('phrasing_content_tag_img');
    }

    public function testPhrasingContentTagInput()
    {
        $this->assertTransformation('phrasing_content_tag_input');
    }

    public function testPhrasingContentTagIns()
    {
        $this->assertTransformation('phrasing_content_tag_ins');
    }

    public function testPhrasingContentTagKbd()
    {
        $this->assertTransformation('phrasing_content_tag_kbd');
    }

    public function testPhrasingContentTagKeygen()
    {
        $this->assertTransformation('phrasing_content_tag_keygen');
    }

    public function testPhrasingContentTagLabel()
    {
        $this->assertTransformation('phrasing_content_tag_label');
    }

    public function testPhrasingContentTagMap()
    {
        $this->assertTransformation('phrasing_content_tag_map');
    }

    public function testPhrasingContentTagMark()
    {
        $this->assertTransformation('phrasing_content_tag_mark');
    }

    public function testPhrasingContentTagMath()
    {
        $this->assertTransformation('phrasing_content_tag_math');
    }

    public function testPhrasingContentTagMeter()
    {
        $this->assertTransformation('phrasing_content_tag_meter');
    }

    public function testPhrasingContentTagNoscript()
    {
        $this->assertTransformation('phrasing_content_tag_noscript');
    }

    public function testPhrasingContentTagObject()
    {
        $this->assertTransformation('phrasing_content_tag_object');
    }

    public function testPhrasingContentTagOutput()
    {
        $this->assertTransformation('phrasing_content_tag_output');
    }

    public function testPhrasingContentTagProgress()
    {
        $this->assertTransformation('phrasing_content_tag_progress');
    }

    public function testPhrasingContentTagQ()
    {
        $this->assertTransformation('phrasing_content_tag_q');
    }

    public function testPhrasingContentTagRuby()
    {
        $this->assertTransformation('phrasing_content_tag_ruby');
    }

    public function testPhrasingContentTagS()
    {
        $this->assertTransformation('phrasing_content_tag_s');
    }

    public function testPhrasingContentTagSamp()
    {
        $this->assertTransformation('phrasing_content_tag_samp');
    }

    public function testPhrasingContentTagScript()
    {
        $this->assertTransformation('phrasing_content_tag_script');
    }

    public function testPhrasingContentTagSelect()
    {
        $this->assertTransformation('phrasing_content_tag_select');
    }

    public function testPhrasingContentTagSmall()
    {
        $this->assertTransformation('phrasing_content_tag_small');
    }

    public function testPhrasingContentTagSpan()
    {
        $this->assertTransformation('phrasing_content_tag_span');
    }

    public function testPhrasingContentTagStrong()
    {
        $this->assertTransformation('phrasing_content_tag_strong');
    }

    public function testPhrasingContentTagSub()
    {
        $this->assertTransformation('phrasing_content_tag_sub');
    }

    public function testPhrasingContentTagSup()
    {
        $this->assertTransformation('phrasing_content_tag_sup');
    }

    public function testPhrasingContentTagSvg()
    {
        $this->assertTransformation('phrasing_content_tag_svg');
    }

    public function testPhrasingContentTagTextarea()
    {
        $this->assertTransformation('phrasing_content_tag_textarea');
    }

    public function testPhrasingContentTagTime()
    {
        $this->assertTransformation('phrasing_content_tag_time');
    }

    public function testPhrasingContentTagU()
    {
        $this->assertTransformation('phrasing_content_tag_u');
    }

    public function testPhrasingContentTagVar()
    {
        $this->assertTransformation('phrasing_content_tag_var');
    }

    public function testPhrasingContentTagVideo()
    {
        $this->assertTransformation('phrasing_content_tag_video');
    }

    public function testPhrasingContentTagWbr()
    {
        $this->assertTransformation('phrasing_content_tag_wbr');
    }

    public function testFencedCodeBlock()
    {
        $this->assertTransformation('fenced-code-block');
    }

    public function testFencedCodeBlockGfm()
    {
      $this->assertTransformation('fenced-code-block-gfm');
    }

    public function testFencedCodeBlockGfmWithClass()
    {
      $this->assertTransformation('fenced-code-block-gfm-with-class');
    }

    public function testWithIdAttributeHn()
    {
        $this->assertTransformation('with-id-attribute-hn');
    }

    public function testWithIdAttributeA()
    {
        $this->assertTransformation('with-id-attribute-a');
    }

    public function testWithIdAttributeARefLink()
    {
        $this->assertTransformation('with-id-attribute-a-ref-link');
    }

    public function testWithIdAttributeImg()
    {
        $this->assertTransformation('with-id-attribute-img');
    }

    public function testWithIdAttributeImgRefLink()
    {
        $this->assertTransformation('with-id-attribute-img-ref-link');
    }

    public function testWithIdAttributeFencedCodeBlock()
    {
        $this->assertTransformation('with-id-attribute-fenced-code-block');
    }

    public function testWithClassAttributeHn()
    {
        $this->assertTransformation('with-class-attribute-hn');
    }

    public function testWithClassAttributeA()
    {
        $this->assertTransformation('with-class-attribute-a');
    }

    public function testWithClassAttributeARefLink()
    {
        $this->assertTransformation('with-class-attribute-a-ref-link');
    }

    public function testWithClassAttributeImg()
    {
        $this->assertTransformation('with-class-attribute-img');
    }

    public function testWithClassAttributeImgRefLink()
    {
        $this->assertTransformation('with-class-attribute-img-ref-link');
    }

    public function testWithClassAttributeFencedCodeBlock()
    {
        $this->assertTransformation('with-class-attribute-fenced-code-block');
    }

    public function testWithMultiAttributesHn()
    {
        $this->assertTransformation('with-multi-attributes-hn');
    }

    public function testWithMultiAttributesA()
    {
        $this->assertTransformation('with-multi-attributes-a');
    }

    public function testWithMultiAttributesARefLink()
    {
        $this->assertTransformation('with-multi-attributes-a-ref-link');
    }

    public function testWithMultiAttributesImg()
    {
        $this->assertTransformation('with-multi-attributes-img');
    }

    public function testWithMultiAttributesImgRefLink()
    {
        $this->assertTransformation('with-multi-attributes-img-ref-link');
    }

    public function testWithMultiAttributesFencedCodeBlock()
    {
        $this->assertTransformation('with-multi-attributes-fenced-code-block');
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
}
