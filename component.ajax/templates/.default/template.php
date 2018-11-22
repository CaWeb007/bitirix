<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
    <button id="bx_test_button">Тест</button>
    <script>new AjaxTest(<?=json_encode($component->ajaxParams)?>)</script>