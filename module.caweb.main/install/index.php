<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\InvalidPathException;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity\Base;
use Caweb\Giim\CompanyTable;
use Bitrix\Main\Config\Option;
Loc::loadMessages(__FILE__);
class caweb_main extends \CModule{
 var $exclusionAdminFiles;
    function __construct(){
        $arModuleVersion = array();
        include (__DIR__."/version.php");
        $this->exclusionAdminFiles = array();
        $this->MODULE_ID = "caweb.main";
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("CAWEB_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("CAWEB_MODULE_DESCRIPTION");

        $this->PARTNER_NAME = Loc::getMessage("CAWEB_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("CAWEB_PARTNER_URI");

        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = "Y";
        $this->MODULE_GROUP_RIGHTS = "Y";
    }
    function DoInstall()
    {
        global $APPLICATION;
        if ($this->isVersionD7()){
            ModuleManager::registerModule($this->MODULE_ID);
        }else{
            $APPLICATION->ThrowException(Loc::getMessage("CAWEB_INSTALL_ERROR_VERSION"));
        }
        $APPLICATION->IncludeAdminFile(Loc::getMessage("CAWEB_INSTALL_TITLE"), $this->GetPath()."/install/step.php");
    }
    function isVersionD7(){
        return CheckVersion(ModuleManager::getVersion('main'),'14.00.00');
    }
    function GetPath($notDocumentRoot = false){
        if ($notDocumentRoot){
            return str_ireplace(Application::getDocumentRoot(),'',dirname(__DIR__));
        }else{
            return dirname(__DIR__);
        }
    }
    function DoUninstall(){
        global $APPLICATION;
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        if ($request["step"] < 2){
            $APPLICATION->IncludeAdminFile(Loc::getMessage("CAWEB_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep1.php");
        }elseif ($request["step"] == 2){
            ModuleManager::unRegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(Loc::getMessage("CAWEB_UNINSTALL_TITLE"), $this->GetPath()."/install/unstep2.php");
        }
    }
}