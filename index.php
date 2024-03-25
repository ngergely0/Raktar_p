<?php
 
require_once('AbstractPage.php');
require_once('WarehousesDbTools.php');
require_once('ShelvesDbTools.php');
require_once('InventoryDbTools.php');
 
$warehousesDbTool = new WarehousesDbTools();
$shelvesDbTool = new ShelvesDbTools();
 
AbstractPage::insertHtmlHead();
$warehouses = $warehousesDbTool->getAllWarehouses();
AbstractPage::showDropDown($warehouses);
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["warehouseDropdown"])) {
        $selectedWarehouseId = $_POST["warehouseDropdown"];
        $shelves = $shelvesDbTool->getShelvesByWarehouseId($selectedWarehouseId);
       
       
        if (!empty($shelves)) {
            $warehouseName = $shelves[0]['warehouse_name'];
            echo '<h2 class="nev">' . (!empty($warehouseName) ? $warehouseName . ' Raktár:' : '') . '</h2>';
            AbstractPage::showMainTable($shelves);
        }
    }
}
?>