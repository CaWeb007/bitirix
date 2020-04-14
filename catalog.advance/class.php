<?php

use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Query\Result;
use Bitrix\Main\SystemException;

/**
 * Created by PhpStorm.
 * User: p.reutov
 * Date: 17.03.2020
 * Time: 11:12
 */
Loc::loadMessages(__FILE__);
class AdvanceItems extends \CBitrixComponent {
    public function executeComponent() {
        $this->arResult['FILTER'] = $this->getFilters();
        $this->includeComponentTemplate();
    }
    private function getFilters() {
        $result = array();
        $result = array(
            'CLASS_ID' => 'CondGroup',
            'DATA' => array(
                'All' => 'OR',
                'True' => 'True'
            ),
            'CHILDREN' => array()
        );
        try{
            $props = $this->getPropsValue();
        }catch (\Exception $exception){
            $this->arResult['ERROR'] = $exception->getMessage();
            $this->setTemplateName('error');
            return false;
        }
        foreach ($props as $id => $value) {
            $result['CHILDREN'][] = array(
                'CLASS_ID' => 'CondIBProp:2:'.$id,
                'DATA' =>array (
                    'logic' => 'Equal',
                    'value' => $value,
                )
            );
        }
        return json_encode($result);
    }
    private function getPropsValue() {
        $props = $this->arParams['PROPS_CODE'];
        $param['filter'] = array('PROPERTY_ID' => $props);
        $param['select'] = array('ID', 'PROPERTY_ID');
        $param['cache'] = array('ttl' => 7200);
        $tmpResult = array();
        $props = array_flip($props);
        $db = PropertyEnumerationTable::getList($param);
        while ($ar = $db->fetch()){
            $tmpResult[$ar['PROPERTY_ID']][] = (int)$ar['ID'];
            unset($props[$ar['PROPERTY_ID']]);
        }
        if (!empty($props))
            throw new SystemException($this->getNotListExceptionMessage(array_flip($props)));
        $result = array();
        foreach ($tmpResult as $propertyID => $arPropertyValue){
            if (count($arPropertyValue) > 1)
                throw new SystemException($this->getNotOnceValueExceptionMessage(array($propertyID)));
            $result[$propertyID] = array_shift($arPropertyValue);
        }
        return $result;
    }
    private function getNotOnceValueExceptionMessage(array $propertyId){
        $db = $this->getProperty($propertyId);
        $ar = $db->fetch();
        return Loc::getMessage('PROPERTY_NOT_ONCE_VALUE', array('#NAME#' => $ar['NAME']));
    }
    private function getNotListExceptionMessage($props){
        $db = $this->getProperty($props);
        $result = array();
        while ($ar = $db->fetch()){
            if ($ar['PROPERTY_TYPE'] !== 'L')
                $result['dontList'][] = $ar['NAME'];
            else
                $result['dontValue'][] = $ar['NAME'];
        }
        $message = array();
        if (!empty($result['dontList']))
            $message[] = Loc::getMessage('PROPERTY_DONT_LIST', array('#NAMES#' => implode(', ', $result['dontList'])));
        if (!empty($result['dontValue']))
            $message[] = Loc::getMessage('PROPERTY_DONT_VALUE', array('#NAMES#' => implode(', ', $result['dontValue'])));
        return implode('<br>', $message);
    }
    /**
     * @param $propertyId array
     * @return Result
     */
    private function getProperty(array $propertyId){
        $param['filter'] = array('ID' => $propertyId);
        $param['select'] = array('ID', 'NAME', 'PROPERTY_TYPE');
        return PropertyTable::getList($param);
    }
}