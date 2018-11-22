<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
class TestAjax extends \CBitrixComponent{
    public static $postName = 'TEST_AJAX';
    protected $isAjax = false;
    public $ajaxParams = array();
    public function __construct($component){
        parent::__construct($component);
        Loc::loadMessages(__FILE__);
        Loader::includeModule('caweb.giim');
        Loader::includeModule('iblock');
    }
    protected function readData(){
        $data = $this->request->getPost(static::$postName);
        $this->isAjax = ($data['AJAX'] === 'Y');
    }
    protected function setAjaxParams(){
        $this->ajaxParams = array(
            'url' => $this->getPath().'/ajax.php',
            'postName' => static::$postName
        );
    }
    protected function initActions(){
        $this->readData();
        $this->setAjaxParams();
    }
    public function executeComponent(){
        $this->initActions();
        if ($this->isAjax) $this->setTemplateName('ajax');
        $this->includeComponentTemplate();
    }
}