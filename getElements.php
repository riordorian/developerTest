<?php
class getElements
{
    function GetList($arOrder=array("SORT"=>"ASC"), $arFilter=array(), $arSelectFields=array())
    {
        $arIblockElementFields = array(
            "ID" => "BE.ID",
            "TIMESTAMP_X" => "BE.TIMESTAMP_X",
            "TIMESTAMP_X_UNIX" => 'UNIX_TIMESTAMP(BE.TIMESTAMP_X)',
            "MODIFIED_BY" => "BE.MODIFIED_BY",
            "DATE_CREATE" => "BE.DATE_CREATE",
            "DATE_CREATE_UNIX" => 'UNIX_TIMESTAMP(BE.DATE_CREATE)',
            "CREATED_BY" => "BE.CREATED_BY",
            "IBLOCK_ID" => "BE.IBLOCK_ID",
            "IBLOCK_SECTION_ID" => "BE.IBLOCK_SECTION_ID",
            "ACTIVE" => "BE.ACTIVE",
            "ACTIVE_FROM" => "BE.ACTIVE_FROM",
            "DATE_ACTIVE_FROM" => "BE.ACTIVE_FROM",
            "SORT" => "BE.SORT",
            "NAME" => "BE.NAME",
            "PREVIEW_PICTURE" => "BE.PREVIEW_PICTURE",
            "PREVIEW_TEXT" => "BE.PREVIEW_TEXT",
            "PREVIEW_TEXT_TYPE" => "BE.PREVIEW_TEXT_TYPE",
            "DETAIL_PICTURE" => "BE.DETAIL_PICTURE",
            "DETAIL_TEXT" => "BE.DETAIL_TEXT",
            "DETAIL_TEXT_TYPE" => "BE.DETAIL_TEXT_TYPE",
            "SEARCHABLE_CONTENT" => "BE.SEARCHABLE_CONTENT",
            "WF_STATUS_ID" => "BE.WF_STATUS_ID",
            "WF_PARENT_ELEMENT_ID" => "BE.WF_PARENT_ELEMENT_ID",
            "WF_LAST_HISTORY_ID" => "BE.WF_LAST_HISTORY_ID",
            "WF_NEW" => "BE.WF_NEW",
            "WF_LOCKED_BY" => "BE.WF_LOCKED_BY",
            "WF_DATE_LOCK" => "BE.WF_DATE_LOCK",
            "WF_COMMENTS" => "BE.WF_COMMENTS",
            "IN_SECTIONS" => "BE.IN_SECTIONS",
            "SHOW_COUNTER" => "BE.SHOW_COUNTER",
            "SHOW_COUNTER_START" => "BE.SHOW_COUNTER_START",
            "CODE" => "BE.CODE",
            "TAGS" => "BE.TAGS",
            "XML_ID" => "BE.XML_ID",
            "EXTERNAL_ID" => "BE.XML_ID",
            "TMP_ID" => "BE.TMP_ID",
            "LANG_DIR" => "L.DIR",
            "LID" => "B.LID",
            "IBLOCK_TYPE_ID" => "B.IBLOCK_TYPE_ID",
            "IBLOCK_CODE" => "B.CODE",
            "IBLOCK_NAME" => "B.NAME",
            "IBLOCK_EXTERNAL_ID" => "B.XML_ID",
            "DETAIL_PAGE_URL" => "B.DETAIL_PAGE_URL",
            "LIST_PAGE_URL" => "B.LIST_PAGE_URL",
            "CANONICAL_PAGE_URL" => "B.CANONICAL_PAGE_URL"
        );
        $orderStr = false;
        $arElement = [];
        $key = md5(json_encode($arOrder).json_encode(ksort($arFilter)).json_encode(sort($arSelectFields)));
        $cacheRes = $this->GetFromRedis($key);
        if($cacheRes) {
            return json_decode($cacheRes, true);
        } else {
            $this->connection();
            $sql = "SELECT ";
            if (is_array($arSelectFields) && !empty($arSelectFields)) {
                foreach ($arSelectFields as $k => $selectField) {
                    if (in_array($selectField, $arIblockElementFields)) {
                        if ($k == count($arSelectFields)) {
                            $sql .= $arIblockElementFields[$selectField] . " as " . $selectField . " ";
                        } else {
                            $sql .= $arIblockElementFields[$selectField] . " as " . $selectField . ", ";
                        }
                    }
                }
            }

            if (is_array($arOrder) && !empty($arOrder)) {
                foreach ($arOrder as $o => $order) {
                    if (!in_array($o, $arSelectFields) && in_array($o, $arIblockElementFields)) {
                        $sql .= $arIblockElementFields[$o] . " ";
                        $orderStr = " ORDER BY " . $o . " " . $order;
                    }
                }
            }

            $sql .= "FROM b_iblock B INNER JOIN b_lang L ON B.LID=L.LID 
                    INNER JOIN b_iblock_element BE ON BE.IBLOCK_ID = B.ID
                    WHERE 1=1 
                    AND (";

            if (is_array($arFilter) && !empty($arFilter)) {
                foreach ($arFilter as $l => $filter) {
                    if (in_array($l, $arIblockElementFields)) {
                        if ($l == count($arSelectFields)) {
                            $sql .= $arIblockElementFields[$l] . " = '" . $filter . "' ) ";
                        } else {
                            $sql .= $arIblockElementFields[$l] . " = '" . $filter . "' AND ";
                        }

                    }
                }
            }

            if ($orderStr) {
                $sql .= $orderStr;
            }

            $result = mysqli_query($sql);
            if ($result) {
                $arElement = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }
            $cache = [$key => json_encode($arElement)];
            $this->PutToRedis($cache);
        }
        return $arElement;
    }

    function connection() {
        // подключение к базе данных
    }

    function GetFromRedis($key) {
        // получение кешированных данных. Возвращает данные или false
    }

    function PutToRedis($data) {
        // отправка результата запроса в redis
    }
}