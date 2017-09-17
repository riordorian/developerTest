<?php
namespace Soba\D7dull;

defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Entity\DataManager;
/*use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator;*/
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule('main')) {
    throw new \Bitrix\Main\Exception("main OR main module is't installed.");
}
if (!Loader::includeModule('iblock')) {
    throw new \Bitrix\Main\Exception("Infoblock OR main module is't installed.");
}

Loader::includeModule('main');

class ExampleTable extends DataManager
{
    public static function getTableName()
    {
        return 'd7dull_example';
    }

    public function test(){
        echo "Hello World!!!!!!";
    }

    public static function test1(){
        echo "Hello World.....................";
    }

    public function GetListCache( $cache_id, $cache_dir = "/news", $cache_time = 3600, $arSelect = array("*"), $arFilter, $arOrder){
        $cache_id = md5(serialize($cache_id));
        $obCache = \Bitrix\Main\Data\Cache::createInstance();
        if( $obCache->InitCache($cache_time, $cache_id, $cache_dir) )// Если кэш валиден
        {
            $arResult = $obCache->GetVars();// Извлечение переменных из кэша
        }
        elseif( $obCache->StartDataCache()  )// Если кэш невалиден
        {
            /*Тяжелые вычисления*/
            $res = \CIBlockElement::GetList($arOrder, $arFilter, false, Array("nPageSize"=>50), $arSelect);
            while($ob = $res->GetNextElement())
            {
                $arFields = $ob->GetFields();
                $arResult[] = $arFields;
            }
            $obCache->EndDataCache($arResult);// Сохраняем переменные в кэш.
        }

        return $arResult;
    }
}
