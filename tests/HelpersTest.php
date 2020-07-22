<?php
use PHPUnit\Framework\TestCase;
use Neft\Synonyms\Helpers;

class HelpersTest extends TestCase
{
  public function testHelpersTest()
  {
    $this->assertTrue(true);
  }

  /**
   * @covers Neft\Synonyms\Helpers::layoutSwitchRu
   */
  public function testLayoutSwitchRu()
  {
    $this->assertEquals(array_map('Neft\Synonyms\Helpers::layoutSwitchRu', array(
      "qwertyuiop[]",
      "asdfghjkl;'",
      "zxcvbnm,.",
      "c]tim to` 'nb[ vzurb[ ahfywepcrb[ ,ekjr? lf dsgtq ;t xf.",
      "'q? ;kj,! Ult nep& Ghzxm .ys[ c]`vobw d irfa"
    )), array(
      "йцукенгшщзхъ",
      "фывапролджэ",
      "ячсмитьбю",
      "съешь ещё этих мягких французских булок, да выпей же чаю",
      "эй, жлоб! Где туз? Прячь юных съёмщиц в шкаф"
    ));
  }

  /**
   * @covers Neft\Synonyms\Helpers::layoutSwitchEn
   */
  public function testLayoutSwitchEn()
  {
    $this->assertEquals(array_map('Neft\Synonyms\Helpers::layoutSwitchEn', array(
      "йцукенгшщз",
      "фывапролд",
      "ячсмить",
      "еру йгшсл икщцт ащч огьзы щмук еру дфян вщп",
      "офслвфцы дщму ьн ишп ызрштч ща йгфкея"
    )), array(
      "qwertyuiop",
      "asdfghjkl",
      "zxcvbnm",
      "the quick brown fox jumps over the lazy dog",
      "jackdaws love my big sphinx of quartz"
    ));
  }

  /**
   * @covers Neft\Synonyms\Helpers::isCyrillic
   */
  public function testIsCyrillic()
  {
    foreach (array(
      "йцукенгшщзхъ",
      "фывапролджэ",
      "ячсмитьбю",
      "фывапролдж ,.!()\/",
      "съешь еще 15 булок",
      "выпей-ка 2 стаканчика! или слабо? (нет)"
    ) as $cyrillic) {
      $this->assertTrue(
          Neft\Synonyms\Helpers::isCyrillic($cyrillic),
          "Не прошло проверку по варианту: " . $cyrillic
      );
    }
    foreach (array(
      "провеrochka",
      "qwerty",
      "",
    ) as $not_cyrillic) {
      $this->assertFalse(
          Neft\Synonyms\Helpers::isCyrillic($not_cyrillic),
          "Не прошло проверку по варианту: " . $not_cyrillic
      );
    }
  }

  /**
   * @covers Neft\Synonyms\Helpers::isLatin
   */
  public function testIsLatin()
  {
    foreach (array(
      "qwertyuiop",
      "asdfghjkl",
      "zxcvbnm",
      "get 2 bottles of beer",
      "qwerty ,.!()\/",
      "hey dude, give me 5 dollars quickly! are you stupid? (no)"
    ) as $latin) {
      $this->assertTrue(
          Neft\Synonyms\Helpers::isLatin($latin),
          "Не прошло проверку по варианту: " . $latin
      );
    }
    foreach (array(
      "провеrochka",
      "йцукенгшщзхъ",
      "",
    ) as $not_latin) {
      $this->assertFalse(
          Neft\Synonyms\Helpers::isLatin($not_latin),
          "Не прошло проверку по варианту: " . $not_latin
      );
    }
  }

  /**
   * @covers Neft\Synonyms\Helpers::layoutSwitch
   */
  public function testLayoutSwitch()
  {
    $this->assertEquals(array_map('Neft\Synonyms\Helpers::layoutSwitch', array(
      "qwertyuiop[]",
      "asdfghjkl;'",
      "ячсмить",
      "c]tim to` 'nb[ vzurb[ ahfywepcrb[ ,ekjr? lf dsgtq ;t xf.",
      "еру йгшсл икщцт ащч огьзы щмук еру дфян вщп"
    )), array(
      "йцукенгшщзхъ",
      "фывапролджэ",
      "zxcvbnm",
      "съешь ещё этих мягких французских булок, да выпей же чаю",
      "the quick brown fox jumps over the lazy dog"
    ));
  }

  /**
   * @covers Neft\Synonyms\Helpers::translify
   */
  public function testTranslify()
  {
    $this->assertEquals(array_map('Neft\Synonyms\Helpers::translify', array(
      "logokam",
      "medialuks",
      "проленд",
      "юла"
    )), array(
      "логокам",
      "медиалукс",
      "prolend",
      "yula"
    ));
  }
}
