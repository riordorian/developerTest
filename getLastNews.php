<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/.." ) ;
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define('CHK_EVENT', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php" ) ;

@set_time_limit(0);
@ignore_user_abort(true);
CModule::IncludeModule("iblock");
$arRes = CIBlockRSS::GetNewsEx("lenta.ru", 80, "/rss/", "ID=demo&LANG=ru&TYPE=news&LIMIT=5");
$arRes = CIBlockRSS::FormatArray($arRes);
?>
<b><?echo $arRes["title"] ?></b><br>

<table cellpadding="2" cellspacing="0" border="0" width="80%">
  <?for ($i = 0; $i < count($arRes["item"]); $i++):?>
  <?if($i<=4):?>
  <tr>
    <td width="100%">
      <font class="date">
        <?echo $arRes["item"][$i]["pubDate"]?>
      </font>&nbsp;|&nbsp;
      <?if (strlen($arRes["item"][$i]["link"])>0):?>
        <a href="<?echo $arRes["item"][$i]["link"]?>" class="zag">
      <?endif;?>
      <?echo $arRes["item"][$i]["title"]?>
      <?if (strlen($arRes["item"][$i]["link"])>0):?>
        </a>
      <?endif;?>
      <br>
    </td>
  </tr>
  <tr>
    <td valign="top" width="100%">
      <?if (strlen($arRes["item"][$i]["enclosure"]["url"])>0):?>
        <table cellpadding="0" cellspacing="0" border="0" align="left">
          <tr>
            <td valign="top">
              <img src="<?echo $arRes["item"][$i]["enclosure"]["url"] ?>" width="<?echo $arRes["item"][$i]["enclosure"]["width"] ?>" height="<?echo $arRes["item"][$i]["enclosure"]["height"]?>" border="0">
            </td>
          </tr>
        </table>
      <?endif;?>
      <font class="text">
        <?echo $arRes["item"][$i]["description"];?>
        <br>
      </font>
    </td>
  </tr>
  <?else:
		continue;
		?>
<?endif;?>
  <?endfor;?>
  
</table>
