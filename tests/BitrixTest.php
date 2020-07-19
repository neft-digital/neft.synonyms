<?php
use PHPUnit\Framework\TestCase;
use Neft\Synonyms\Helpers;

class BitrixTest extends TestCase
{
  public function testBitrixTest()
  {
    $this->assertTrue(true);
  }

  public function testIfModulesInstalled()
  {
    $this->assertTrue(Bitrix\Main\ModuleManager::IsModuleInstalled("iblock"), 'Модуль "iblock" не установлен.');
    $this->assertTrue(Bitrix\Main\ModuleManager::IsModuleInstalled("catalog"), 'Модуль "catalog" не установлен.');
  }
}
