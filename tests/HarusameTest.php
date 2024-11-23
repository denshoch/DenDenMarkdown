<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class HarusameTest extends TestCase
{
	protected $parser;
	protected $fixtureDir;

	protected function setUp(): void
	{
		parent::setUp();
    	$options = array(
            "autoTcyDigit" => 2,
            "autoTextOrientation" => true
    	);
        $this->parser = new Denshoch\DenDenMarkdown($options);
	}

	
    public function testInvalidXML()
    {
        $source = "<abc>aaa</efg>";
        $excpected = "<p><abc>aaa</efg></p>";

        $this->is_same($source, $excpected);
        $allMessages = $this->parser->messageStore->getMessages();

        $this->assertEquals($allMessages[0]['code'], 'E_HARUSAME');
    }

    public function testTcy2()
    {
        $source =    '12ああああ34ああ457あああ89';
        $excpected = '<p><span class="tcy">12</span>ああああ<span class="tcy">34</span>ああ457あああ<span class="tcy">89</span></p>';
        $this->is_same($source, $excpected);

        $source =    '!!ああああ!!!ああ!?あああ??';
        $excpected = '<p><span class="tcy">!!</span>ああああ!!!ああ<span class="tcy">!?</span>あああ<span class="tcy">??</span></p>';
        $this->is_same($source, $excpected);
    }

    public function testTcyCheckParent()
    {
        $source =    '<span class="tcy">12</span>ああああ<span class="tcy">34</span>ああ457あああ<span class="tcy">89</span>';
        $excpected = '<p><span class="tcy">12</span>ああああ<span class="tcy">34</span>ああ457あああ<span class="tcy">89</span></p>';
        $this->is_same($source, $excpected);

        $source =    '<span class="tcy">!!</span>ああああ!!!ああ<span class="tcy">!?</span>あああ<span class="tcy">??</span>';
        $excpected = '<p><span class="tcy">!!</span>ああああ!!!ああ<span class="tcy">!?</span>あああ<span class="tcy">??</span></p>';
        $this->is_same($source, $excpected);
    }

    public function testTcy3()
    {
    	$this->parser->tcyDigit = 3;
        $source =    '12ああああ34ああ457あああ89';
        $excpected = '<p><span class="tcy">12</span>ああああ<span class="tcy">34</span>ああ<span class="tcy">457</span>あああ<span class="tcy">89</span></p>';
        $this->is_same($source, $excpected);

        $source =    '!!ああああ!!!ああ!?あああ??';
        $excpected = '<p><span class="tcy">!!</span>ああああ!!!ああ<span class="tcy">!?</span>あああ<span class="tcy">??</span></p>';
        $this->is_same($source, $excpected);
    }

    public function testTcyFalse()
    {
    	$this->parser->autoTcy = false;
        $source =    '12ああああ34ああ457あああ89';
        $excpected = '<p>' . $source . '</p>';
        $this->is_same($source, $excpected);

        $source =    '!!ああああ!!!ああ!?あああ??';
        $excpected = '<p>' . $source . '</p>';
        $this->is_same($source, $excpected);
    }

    public function testOrientation()
    {
        $source =   '÷∴≠≦≧∧∨＜＞‐－';
        $excpected = '<p><span class="sideways">÷</span><span class="sideways">∴</span><span class="sideways">≠</span><span class="sideways">≦</span><span class="sideways">≧</span><span class="sideways">∧</span><span class="sideways">∨</span><span class="sideways">＜</span><span class="sideways">＞</span><span class="sideways">‐</span><span class="sideways">－</span></p>';
        $this->is_same($source, $excpected);

        $source = 'ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρςστυφχψωАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя¨ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩⅪⅰⅱⅲⅳⅴⅵⅶⅷⅸⅹⅺⅻ♀♂∀∃∠⊥⌒∂∇√∽∝∫∬∞①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑮⑯⑰⑱⑲⑳㉑㉒㉓㉔㉕㉖㉗㉘㉙㉚㉛㉜㉝㉞㉟㊱㊲㊳㊴㊵㊶㊷㊸㊹㊺㊻㊼㊽㊾㊿❶❷❸❹❺❻❼❽❾❿⓫⓬⓭⓮⓯⓰⓱⓲⓳⓴⓵⓶⓷⓸⓹⓺⓻⓼⓽⓾▱▲△▼▽☀☁☂☃★☆☎☖☗♠♡♢♣♤♥♦♧♨♩♪♫♬♭♮♯✓〒〠¶†‡‼⁇⁈⁉№℡㏍＃＄％＆＊＠￥¢£§°‰′″℃㎎㎏㎝㎞㎡㏄Å〳〴〵〻〼ゟヿ⅓⅔⅕⇒⇔';
        $excpected = '<p><span class="upright">Α</span><span class="upright">Β</span><span class="upright">Γ</span><span class="upright">Δ</span><span class="upright">Ε</span><span class="upright">Ζ</span><span class="upright">Η</span><span class="upright">Θ</span><span class="upright">Ι</span><span class="upright">Κ</span><span class="upright">Λ</span><span class="upright">Μ</span><span class="upright">Ν</span><span class="upright">Ξ</span><span class="upright">Ο</span><span class="upright">Π</span><span class="upright">Ρ</span><span class="upright">Σ</span><span class="upright">Τ</span><span class="upright">Υ</span><span class="upright">Φ</span><span class="upright">Χ</span><span class="upright">Ψ</span><span class="upright">Ω</span><span class="upright">α</span><span class="upright">β</span><span class="upright">γ</span><span class="upright">δ</span><span class="upright">ε</span><span class="upright">ζ</span><span class="upright">η</span><span class="upright">θ</span><span class="upright">ι</span><span class="upright">κ</span><span class="upright">λ</span><span class="upright">μ</span><span class="upright">ν</span><span class="upright">ξ</span><span class="upright">ο</span><span class="upright">π</span><span class="upright">ρ</span><span class="upright">ς</span><span class="upright">σ</span><span class="upright">τ</span><span class="upright">υ</span><span class="upright">φ</span><span class="upright">χ</span><span class="upright">ψ</span><span class="upright">ω</span><span class="upright">А</span><span class="upright">Б</span><span class="upright">В</span><span class="upright">Г</span><span class="upright">Д</span><span class="upright">Е</span><span class="upright">Ё</span><span class="upright">Ж</span><span class="upright">З</span><span class="upright">И</span><span class="upright">Й</span><span class="upright">К</span><span class="upright">Л</span><span class="upright">М</span><span class="upright">Н</span><span class="upright">О</span><span class="upright">П</span><span class="upright">Р</span><span class="upright">С</span><span class="upright">Т</span><span class="upright">У</span><span class="upright">Ф</span><span class="upright">Х</span><span class="upright">Ц</span><span class="upright">Ч</span><span class="upright">Ш</span><span class="upright">Щ</span><span class="upright">Ъ</span><span class="upright">Ы</span><span class="upright">Ь</span><span class="upright">Э</span><span class="upright">Ю</span><span class="upright">Я</span><span class="upright">а</span><span class="upright">б</span><span class="upright">в</span><span class="upright">г</span><span class="upright">д</span><span class="upright">е</span><span class="upright">ё</span><span class="upright">ж</span><span class="upright">з</span><span class="upright">и</span><span class="upright">й</span><span class="upright">к</span><span class="upright">л</span><span class="upright">м</span><span class="upright">н</span><span class="upright">о</span><span class="upright">п</span><span class="upright">р</span><span class="upright">с</span><span class="upright">т</span><span class="upright">у</span><span class="upright">ф</span><span class="upright">х</span><span class="upright">ц</span><span class="upright">ч</span><span class="upright">ш</span><span class="upright">щ</span><span class="upright">ъ</span><span class="upright">ы</span><span class="upright">ь</span><span class="upright">э</span><span class="upright">ю</span><span class="upright">я</span><span class="upright">¨</span><span class="upright">Ⅰ</span><span class="upright">Ⅱ</span><span class="upright">Ⅲ</span><span class="upright">Ⅳ</span><span class="upright">Ⅴ</span><span class="upright">Ⅵ</span><span class="upright">Ⅶ</span><span class="upright">Ⅷ</span><span class="upright">Ⅸ</span><span class="upright">Ⅹ</span><span class="upright">Ⅺ</span><span class="upright">ⅰ</span><span class="upright">ⅱ</span><span class="upright">ⅲ</span><span class="upright">ⅳ</span><span class="upright">ⅴ</span><span class="upright">ⅵ</span><span class="upright">ⅶ</span><span class="upright">ⅷ</span><span class="upright">ⅸ</span><span class="upright">ⅹ</span><span class="upright">ⅺ</span><span class="upright">ⅻ</span><span class="upright">♀</span><span class="upright">♂</span><span class="upright">∀</span><span class="upright">∃</span><span class="upright">∠</span><span class="upright">⊥</span><span class="upright">⌒</span><span class="upright">∂</span><span class="upright">∇</span><span class="upright">√</span><span class="upright">∽</span><span class="upright">∝</span><span class="upright">∫</span><span class="upright">∬</span><span class="upright">∞</span><span class="upright">①</span><span class="upright">②</span><span class="upright">③</span><span class="upright">④</span><span class="upright">⑤</span><span class="upright">⑥</span><span class="upright">⑦</span><span class="upright">⑧</span><span class="upright">⑨</span><span class="upright">⑩</span><span class="upright">⑪</span><span class="upright">⑫</span><span class="upright">⑬</span><span class="upright">⑭</span><span class="upright">⑮</span><span class="upright">⑯</span><span class="upright">⑰</span><span class="upright">⑱</span><span class="upright">⑲</span><span class="upright">⑳</span><span class="upright">㉑</span><span class="upright">㉒</span><span class="upright">㉓</span><span class="upright">㉔</span><span class="upright">㉕</span><span class="upright">㉖</span><span class="upright">㉗</span><span class="upright">㉘</span><span class="upright">㉙</span><span class="upright">㉚</span><span class="upright">㉛</span><span class="upright">㉜</span><span class="upright">㉝</span><span class="upright">㉞</span><span class="upright">㉟</span><span class="upright">㊱</span><span class="upright">㊲</span><span class="upright">㊳</span><span class="upright">㊴</span><span class="upright">㊵</span><span class="upright">㊶</span><span class="upright">㊷</span><span class="upright">㊸</span><span class="upright">㊹</span><span class="upright">㊺</span><span class="upright">㊻</span><span class="upright">㊼</span><span class="upright">㊽</span><span class="upright">㊾</span><span class="upright">㊿</span><span class="upright">❶</span><span class="upright">❷</span><span class="upright">❸</span><span class="upright">❹</span><span class="upright">❺</span><span class="upright">❻</span><span class="upright">❼</span><span class="upright">❽</span><span class="upright">❾</span><span class="upright">❿</span><span class="upright">⓫</span><span class="upright">⓬</span><span class="upright">⓭</span><span class="upright">⓮</span><span class="upright">⓯</span><span class="upright">⓰</span><span class="upright">⓱</span><span class="upright">⓲</span><span class="upright">⓳</span><span class="upright">⓴</span><span class="upright">⓵</span><span class="upright">⓶</span><span class="upright">⓷</span><span class="upright">⓸</span><span class="upright">⓹</span><span class="upright">⓺</span><span class="upright">⓻</span><span class="upright">⓼</span><span class="upright">⓽</span><span class="upright">⓾</span><span class="upright">▱</span><span class="upright">▲</span><span class="upright">△</span><span class="upright">▼</span><span class="upright">▽</span><span class="upright">☀</span><span class="upright">☁</span><span class="upright">☂</span><span class="upright">☃</span><span class="upright">★</span><span class="upright">☆</span><span class="upright">☎</span><span class="upright">☖</span><span class="upright">☗</span><span class="upright">♠</span><span class="upright">♡</span><span class="upright">♢</span><span class="upright">♣</span><span class="upright">♤</span><span class="upright">♥</span><span class="upright">♦</span><span class="upright">♧</span><span class="upright">♨</span><span class="upright">♩</span><span class="upright">♪</span><span class="upright">♫</span><span class="upright">♬</span><span class="upright">♭</span><span class="upright">♮</span><span class="upright">♯</span><span class="upright">✓</span><span class="upright">〒</span><span class="upright">〠</span><span class="upright">¶</span><span class="upright">†</span><span class="upright">‡</span><span class="upright">‼</span><span class="upright">⁇</span><span class="upright">⁈</span><span class="upright">⁉</span><span class="upright">№</span><span class="upright">℡</span><span class="upright">㏍</span><span class="upright">＃</span><span class="upright">＄</span><span class="upright">％</span><span class="upright">＆</span><span class="upright">＊</span><span class="upright">＠</span><span class="upright">￥</span><span class="upright">¢</span><span class="upright">£</span><span class="upright">§</span><span class="upright">°</span><span class="upright">‰</span><span class="upright">′</span><span class="upright">″</span><span class="upright">℃</span><span class="upright">㎎</span><span class="upright">㎏</span><span class="upright">㎝</span><span class="upright">㎞</span><span class="upright">㎡</span><span class="upright">㏄</span><span class="upright">Å</span><span class="upright">〳</span><span class="upright">〴</span><span class="upright">〵</span><span class="upright">〻</span><span class="upright">〼</span><span class="upright">ゟ</span><span class="upright">ヿ</span><span class="upright">⅓</span><span class="upright">⅔</span><span class="upright">⅕</span><span class="upright">⇒</span><span class="upright">⇔</span></p>';
        $this->is_same($source, $excpected);
    }

    public function testOrientationCheckParent()
    {
        $source =   '<span class="sideways">÷</span><span class="sideways">∴</span><span class="sideways">≠</span><span class="sideways">≦</span><span class="sideways">≧</span><span class="sideways">∧</span><span class="sideways">∨</span><span class="sideways">＜</span><span class="sideways">＞</span><span class="sideways">‐</span><span class="sideways">－</span>';
        $excpected = '<p>' . $source . '</p>';
        $this->is_same($source, $excpected);

        $source = '<span class="upright">Α</span><span class="upright">Β</span><span class="upright">Γ</span><span class="upright">Δ</span><span class="upright">Ε</span><span class="upright">Ζ</span><span class="upright">Η</span><span class="upright">Θ</span><span class="upright">Ι</span><span class="upright">Κ</span><span class="upright">Λ</span><span class="upright">Μ</span><span class="upright">Ν</span><span class="upright">Ξ</span><span class="upright">Ο</span><span class="upright">Π</span><span class="upright">Ρ</span><span class="upright">Σ</span><span class="upright">Τ</span><span class="upright">Υ</span><span class="upright">Φ</span><span class="upright">Χ</span><span class="upright">Ψ</span><span class="upright">Ω</span><span class="upright">α</span><span class="upright">β</span><span class="upright">γ</span><span class="upright">δ</span><span class="upright">ε</span><span class="upright">ζ</span><span class="upright">η</span><span class="upright">θ</span><span class="upright">ι</span><span class="upright">κ</span><span class="upright">λ</span><span class="upright">μ</span><span class="upright">ν</span><span class="upright">ξ</span><span class="upright">ο</span><span class="upright">π</span><span class="upright">ρ</span><span class="upright">ς</span><span class="upright">σ</span><span class="upright">τ</span><span class="upright">υ</span><span class="upright">φ</span><span class="upright">χ</span><span class="upright">ψ</span><span class="upright">ω</span><span class="upright">А</span><span class="upright">Б</span><span class="upright">В</span><span class="upright">Г</span><span class="upright">Д</span><span class="upright">Е</span><span class="upright">Ё</span><span class="upright">Ж</span><span class="upright">З</span><span class="upright">И</span><span class="upright">Й</span><span class="upright">К</span><span class="upright">Л</span><span class="upright">М</span><span class="upright">Н</span><span class="upright">О</span><span class="upright">П</span><span class="upright">Р</span><span class="upright">С</span><span class="upright">Т</span><span class="upright">У</span><span class="upright">Ф</span><span class="upright">Х</span><span class="upright">Ц</span><span class="upright">Ч</span><span class="upright">Ш</span><span class="upright">Щ</span><span class="upright">Ъ</span><span class="upright">Ы</span><span class="upright">Ь</span><span class="upright">Э</span><span class="upright">Ю</span><span class="upright">Я</span><span class="upright">а</span><span class="upright">б</span><span class="upright">в</span><span class="upright">г</span><span class="upright">д</span><span class="upright">е</span><span class="upright">ё</span><span class="upright">ж</span><span class="upright">з</span><span class="upright">и</span><span class="upright">й</span><span class="upright">к</span><span class="upright">л</span><span class="upright">м</span><span class="upright">н</span><span class="upright">о</span><span class="upright">п</span><span class="upright">р</span><span class="upright">с</span><span class="upright">т</span><span class="upright">у</span><span class="upright">ф</span><span class="upright">х</span><span class="upright">ц</span><span class="upright">ч</span><span class="upright">ш</span><span class="upright">щ</span><span class="upright">ъ</span><span class="upright">ы</span><span class="upright">ь</span><span class="upright">э</span><span class="upright">ю</span><span class="upright">я</span><span class="upright">¨</span><span class="upright">Ⅰ</span><span class="upright">Ⅱ</span><span class="upright">Ⅲ</span><span class="upright">Ⅳ</span><span class="upright">Ⅴ</span><span class="upright">Ⅵ</span><span class="upright">Ⅶ</span><span class="upright">Ⅷ</span><span class="upright">Ⅸ</span><span class="upright">Ⅹ</span><span class="upright">Ⅺ</span><span class="upright">ⅰ</span><span class="upright">ⅱ</span><span class="upright">ⅲ</span><span class="upright">ⅳ</span><span class="upright">ⅴ</span><span class="upright">ⅵ</span><span class="upright">ⅶ</span><span class="upright">ⅷ</span><span class="upright">ⅸ</span><span class="upright">ⅹ</span><span class="upright">ⅺ</span><span class="upright">ⅻ</span><span class="upright">♀</span><span class="upright">♂</span><span class="upright">∀</span><span class="upright">∃</span><span class="upright">∠</span><span class="upright">⊥</span><span class="upright">⌒</span><span class="upright">∂</span><span class="upright">∇</span><span class="upright">√</span><span class="upright">∽</span><span class="upright">∝</span><span class="upright">∫</span><span class="upright">∬</span><span class="upright">∞</span><span class="upright">①</span><span class="upright">②</span><span class="upright">③</span><span class="upright">④</span><span class="upright">⑤</span><span class="upright">⑥</span><span class="upright">⑦</span><span class="upright">⑧</span><span class="upright">⑨</span><span class="upright">⑩</span><span class="upright">⑪</span><span class="upright">⑫</span><span class="upright">⑬</span><span class="upright">⑭</span><span class="upright">⑮</span><span class="upright">⑯</span><span class="upright">⑰</span><span class="upright">⑱</span><span class="upright">⑲</span><span class="upright">⑳</span><span class="upright">㉑</span><span class="upright">㉒</span><span class="upright">㉓</span><span class="upright">㉔</span><span class="upright">㉕</span><span class="upright">㉖</span><span class="upright">㉗</span><span class="upright">㉘</span><span class="upright">㉙</span><span class="upright">㉚</span><span class="upright">㉛</span><span class="upright">㉜</span><span class="upright">㉝</span><span class="upright">㉞</span><span class="upright">㉟</span><span class="upright">㊱</span><span class="upright">㊲</span><span class="upright">㊳</span><span class="upright">㊴</span><span class="upright">㊵</span><span class="upright">㊶</span><span class="upright">㊷</span><span class="upright">㊸</span><span class="upright">㊹</span><span class="upright">㊺</span><span class="upright">㊻</span><span class="upright">㊼</span><span class="upright">㊽</span><span class="upright">㊾</span><span class="upright">㊿</span><span class="upright">❶</span><span class="upright">❷</span><span class="upright">❸</span><span class="upright">❹</span><span class="upright">❺</span><span class="upright">❻</span><span class="upright">❼</span><span class="upright">❽</span><span class="upright">❾</span><span class="upright">❿</span><span class="upright">⓫</span><span class="upright">⓬</span><span class="upright">⓭</span><span class="upright">⓮</span><span class="upright">⓯</span><span class="upright">⓰</span><span class="upright">⓱</span><span class="upright">⓲</span><span class="upright">⓳</span><span class="upright">⓴</span><span class="upright">⓵</span><span class="upright">⓶</span><span class="upright">⓷</span><span class="upright">⓸</span><span class="upright">⓹</span><span class="upright">⓺</span><span class="upright">⓻</span><span class="upright">⓼</span><span class="upright">⓽</span><span class="upright">⓾</span><span class="upright">▱</span><span class="upright">▲</span><span class="upright">△</span><span class="upright">▼</span><span class="upright">▽</span><span class="upright">☀</span><span class="upright">☁</span><span class="upright">☂</span><span class="upright">☃</span><span class="upright">★</span><span class="upright">☆</span><span class="upright">☎</span><span class="upright">☖</span><span class="upright">☗</span><span class="upright">♠</span><span class="upright">♡</span><span class="upright">♢</span><span class="upright">♣</span><span class="upright">♤</span><span class="upright">♥</span><span class="upright">♦</span><span class="upright">♧</span><span class="upright">♨</span><span class="upright">♩</span><span class="upright">♪</span><span class="upright">♫</span><span class="upright">♬</span><span class="upright">♭</span><span class="upright">♮</span><span class="upright">♯</span><span class="upright">✓</span><span class="upright">〒</span><span class="upright">〠</span><span class="upright">¶</span><span class="upright">†</span><span class="upright">‡</span><span class="upright">‼</span><span class="upright">⁇</span><span class="upright">⁈</span><span class="upright">⁉</span><span class="upright">№</span><span class="upright">℡</span><span class="upright">㏍</span><span class="upright">＃</span><span class="upright">＄</span><span class="upright">％</span><span class="upright">＆</span><span class="upright">＊</span><span class="upright">＠</span><span class="upright">￥</span><span class="upright">¢</span><span class="upright">£</span><span class="upright">§</span><span class="upright">°</span><span class="upright">‰</span><span class="upright">′</span><span class="upright">″</span><span class="upright">℃</span><span class="upright">㎎</span><span class="upright">㎏</span><span class="upright">㎝</span><span class="upright">㎞</span><span class="upright">㎡</span><span class="upright">㏄</span><span class="upright">Å</span><span class="upright">〳</span><span class="upright">〴</span><span class="upright">〵</span><span class="upright">〻</span><span class="upright">〼</span><span class="upright">ゟ</span><span class="upright">ヿ</span><span class="upright">⅓</span><span class="upright">⅔</span><span class="upright">⅕</span><span class="upright">⇒</span><span class="upright">⇔</span>';
        $excpected = '<p>' . $source . '</p>';
        $this->is_same($source, $excpected);
    }

    public function testOrientationFalse()
    {
        $this->parser->autoTextOrientation = false;

        $source =   '÷∴≠≦≧∧∨＜＞‐－';
        $excpected = '<p>' . $source . '</p>';
        $this->is_same($source, $excpected);

        $source = 'ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρςστυφχψωАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя¨ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩⅪⅰⅱⅲⅳⅴⅵⅶⅷⅸⅹⅺⅻ♀♂∀∃∠⊥⌒∂∇√∽∝∫∬∞①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑮⑯⑰⑱⑲⑳㉑㉒㉓㉔㉕㉖㉗㉘㉙㉚㉛㉜㉝㉞㉟㊱㊲㊳㊴㊵㊶㊷㊸㊹㊺㊻㊼㊽㊾㊿❶❷❸❹❺❻❼❽❾❿⓫⓬⓭⓮⓯⓰⓱⓲⓳⓴⓵⓶⓷⓸⓹⓺⓻⓼⓽⓾▱▲△▼▽☀☁☂☃★☆☎☖☗♠♡♢♣♤♥♦♧♨♩♪♫♬♭♮♯✓〒〠¶†‡‼⁇⁈⁉№℡㏍＃＄％＆＊＠￥¢£§°‰′″℃㎎㎏㎝㎞㎡㏄Å〳〴〵〻〼ゟヿ⅓⅔⅕⇒⇔';
        $excpected = '<p>' . $source . '</p>';
        $this->is_same($source, $excpected);
    }


    public function testHTML()
    {
        $source =    '<html><head><title>12ああああ34ああ457あああ89</title></head><body>12ああああ34ああ457あああ89</body></html>';
        $excpected = '<html><head><title>12ああああ34ああ457あああ89</title></head><body><span class="tcy">12</span>ああああ<span class="tcy">34</span>ああ457あああ<span class="tcy">89</span></body></html>';
        $this->is_same($source, $excpected);

        $source =   '<html><head><title>÷∴≠≦≧∧∨＜＞‐－</title></head><body>÷∴≠≦≧∧∨＜＞‐－</body></html>';
        $excpected = '<html><head><title>÷∴≠≦≧∧∨＜＞‐－</title></head><body><span class="sideways">÷</span><span class="sideways">∴</span><span class="sideways">≠</span><span class="sideways">≦</span><span class="sideways">≧</span><span class="sideways">∧</span><span class="sideways">∨</span><span class="sideways">＜</span><span class="sideways">＞</span><span class="sideways">‐</span><span class="sideways">－</span></body></html>';
        $this->is_same($source, $excpected);
    }

    public function testMathml(){
        $source = '<figure class="mathml" id="illust_index_4"><math xmlns="http://www.w3.org/1998/Math/MathML" display="block"><semantics><mrow><msub><msup><mi>W</mi><mo>′</mo></msup><mn>1</mn></msub><mo>−</mo><mfrac><mn>1</mn><mn>2</mn></mfrac><msub><mi>n</mi><mn>1</mn></msub><mo stretchy="false">(</mo><msub><mi>n</mi><mn>1</mn></msub><mo>+</mo><mn>1</mn><mo stretchy="false">)</mo><mo>=</mo><mn>38</mn><mo>−</mo><mfrac><mn>1</mn><mn>2</mn></mfrac><mo stretchy="false">(</mo><mn>4</mn><mo stretchy="false">)</mo><mo stretchy="false">(</mo><mn>4</mn><mo>+</mo><mn>1</mn><mo stretchy="false">)</mo><mo>=</mo><mn>38</mn><mo>−</mo><mn>10</mn><mo>=</mo><mn>28</mn></mrow><annotation encoding="TeX">{W\'}_1 - \frac{1}{2}n_1(n_1+1)= 38 - \frac{1}{2}(4)(4+1)=38-10=28</annotation></semantics></math></figure>';
        $excpected = $source;
        $this->is_same($source, $excpected);

        $source = '<figure class="mathml"><figcaption>数式: (4.<span class="tcy">10</span>)</figcaption><math xmlns="http://www.w3.org/1998/Math/MathML" display="block"><semantics><mrow><mi>Z</mi><mo>=</mo><mfrac><mrow><mi>X</mi><mo>−</mo><mi>E</mi><mo stretchy="false">(</mo><mi>X</mi><mo stretchy="false">)</mo></mrow><mrow><mrow><mi>V</mi><mi>a</mi><mi>r</mi></mrow><mo stretchy="false">(</mo><mi>X</mi><mo stretchy="false">)</mo></mrow></mfrac></mrow><annotation encoding="TeX">Z=\frac{X-E(X)}{{Var}(X)}</annotation></semantics></math></figure>';
        $excpected = $source;
        $this->is_same($source, $excpected);
    }

    private function is_same($source, $excpected)
    {
        $actual = $this->parser->transform($source);
        $actual = preg_replace('/\n/', '', $actual);
        $this->assertSame($excpected, $actual);
    }
}