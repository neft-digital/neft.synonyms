<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Uri;
use Bitrix\Main\Loader;
use Bitrix\Main\Error;
use Neft\Synonyms\SynonymsTable;
use Bitrix\Main\ORM\Query\Query;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
  die();
}

Loc::loadMessages(__FILE__);

class SynonymsEditComponent extends CBitrixComponent
{
  protected $errors;

  protected function printErrors()
  {
    foreach ($this->errors as $error) {
      ShowError($error);
      $this->arResult['ERRORS'] = 1;
    }
  }

  protected function prepareResult()
  {
    $request = Context::getCurrent()->getRequest();
    $this->arResult['SUBMIT_FORM_URL'] = Context::getCurrent()->getRequest()->getRequestUri();
    $this->arResult['IS_SAVED'] = $this->request->get('IS_SAVED') == 'Y';

    if (!$request->get('ID')) {
      $this->arResult['ID'] = 0;
    } else {
      $this->arResult['ID'] = trim($request->get('ID'));
    }

    if ($this->arParams['SET_TITLE'] == 'Y') {
      $GLOBALS['APPLICATION']->SetTitle(
          $this->arResult['ID'] > 0
            ?
            "Редактировать ключевое слово"
            :
            "Добавить ключевое слово"
      );
    }



    if ($this->request->isPost() && check_bitrix_sessid()) { // Если пришли данные из формы
      $data = array(
        'WORD' => trim($request->get('WORD')),
        'SYNONYMS' => trim($request->get('SYNONYMS')),
        'ACTIVE' => $request->get('ACTIVE') === 'Y' ? 'Y' : 'N',
      );
      if ($this->arResult['ID'] == 0) {
        SynonymsTable::add($data);
      } else {
        SynonymsTable::update($this->arResult['ID'], $data);
      }
    }

    $data = SynonymsTable::getRowById($this->arResult['ID']);
    $this->arResult['WORD'] = $data['WORD'];
    $this->arResult['SYNONYMS'] = $data['SYNONYMS'];
    $this->arResult['ACTIVE'] = $data['ACTIVE'];


    return true;
  }

  public function executeComponent()
  {
    $this->errors = new ErrorCollection();

    if (!Loader::includeModule('neft.synonyms')) {
      $this->errors->setError(new Error('Module `neft.synonyms` is not installed.'));
      $this->printErrors();
      return;
    }

    if (!$this->prepareResult()) {
      $this->printErrors();
      return;
    }

    $this->printErrors();
    $this->includeComponentTemplate();
  }
}
