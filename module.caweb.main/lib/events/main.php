<?
namespace Caweb\Main\Events;
use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);
class Main{
    public function OnBeforeUserRegister($arFields){
        if (($arFields["PERSONAL_PROFESSION"]=="КП(ЮР)") && Helper::notUniqueLegalUser($arFields['UF_INN'], $arFields['UF_KPP'])){
            global $APPLICATION;
            $APPLICATION -> ThrowException(Loc::getMessage('CAWEB_NOT_UNIQUE_LEGAL_USER'));
            return false;
        }
    }
}
