<?php
use PHPUnit\Framework\TestCase;
use Neft\Synonyms\Helpers;

class HelpersTest extends TestCase
{
  public function testHelpersTest()
  {
    $this->assertTrue(true);
  }

  // public function testLayoutSwitchRu() {
  // }

  public function testOneIsOne()
  {
    $this->assertEquals(
        "5",
        Helpers::test()
    );
  }
}
