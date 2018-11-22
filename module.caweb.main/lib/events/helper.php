<?
namespace Caweb\Main\Events;
use Bitrix\Main\UserTable;
class Helper{
    public static function notUniqueLegalUser($inn, $kpp){
        $params['filter'] = array('UF_INN' => $inn, 'UF_KPP' => $kpp);
        return (!empty(UserTable::getRow($params)));
    }
}
