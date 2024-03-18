<?php
   
    require_once('AbstractPage.php');
    require_once('WarehousesDbTools.php');

    $warehousesDbTool = new WarehousesDbTools();

    AbstractPage::insertHtmlHead();
    $warehouses = $warehousesDbTool->getAllWarehouses();
    AbstractPage::showDropDown($warehouses);