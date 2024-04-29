<?php
 
require_once('AbstractPage.php');
require_once('WarehousesDbTools.php');
require_once('ShelvesDbTools.php');
require_once('InventoryDbTools.php');
 
$warehousesDbTool = new WarehousesDbTools();
$shelvesDbTool = new ShelvesDbTools();
$inventoryDbTool =  new InventoryDbTools();
 
AbstractPage::insertHtmlHead();
$warehouses = $warehousesDbTool->getAllWarehouses();
AbstractPage::showDropDown($warehouses);
AbstractPage::showAddInventory();
AbstractPage::showPdfExport();
AbstractPage::showMailButton();




$selectedWarehouseId = null;
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["warehouseDropdown"])) {
        $selectedWarehouseId = $_POST["warehouseDropdown"];
        $shelves = $shelvesDbTool->getShelvesByWarehouseId($selectedWarehouseId);
        $inventory = $inventoryDbTool->getInventoryByWarehouseId($selectedWarehouseId);

        if (!empty($shelves)) {
            $warehouseName = $shelves[0]['warehouse_name'];
            echo '<h2 class="nev">' . (!empty($warehouseName) ? $warehouseName . ' Raktár:' : '') . '</h2>';
            AbstractPage::showMainTable($shelves, $inventory);
        }
    }
}

if (isset($_POST['delete_shelf'])) {
    if (isset($_POST['shelf_id'])) {
        $shelfIdToDelete = $_POST['shelf_id'];
        $shelvesDbTool->deleteShelfById($shelfIdToDelete);
        if (isset($selectedWarehouseId)) {
            $shelves = $shelvesDbTool->getShelvesByWarehouseId($selectedWarehouseId);
        }
    }
}



if (isset($_POST['modify_shelf'])) {
    if (isset($_POST['modify_shelf_id'])) {
        $modifyShelfId = $_POST['modify_shelf_id'];
        $shelfToModify = $shelvesDbTool->getShelfById($modifyShelfId);
        $inventory = $inventoryDbTool->getInventoryByWarehouseId($selectedWarehouseId);
        AbstractPage::showModifyShelf($shelfToModify, $modifyShelfId, $inventory);
    }
}


if (isset($_POST['modify_shelf_submit'])) {
    if (isset($_POST['modify_shelf_id'])) {
        $modifyShelfId = $_POST['modify_shelf_id'];
        $modifiedShelfLine = $_POST['modified_shelf_line'];
        $modifiedShelfId = $_POST['modified_shelf_id'];
        $modifiedItemName = $_POST['modified_shelf_itemName'];
        $modifiedItemQuantity = $_POST['modified_item_qty'];      
        $shelvesDbTool->modifyShelf($modifyShelfId, $modifiedShelfLine, $modifiedShelfId, $modifiedItemName);
        $inventoryDbTool->modifyInventory($modifiedItemQuantity, $modifiedItemName);
        if (isset($selectedWarehouseId)) {
            $shelves = $shelvesDbTool->getShelvesByWarehouseId($selectedWarehouseId);
            $inventory = $inventoryDbTool->getInventoryByWarehouseId($selectedWarehouseId); 
        }
    }
}



if (isset($_POST['add_inventory'])) {
    $newItemName = $_POST['new_item_name'];
    $newShelfName = $_POST['new_shelf_name'];
    $newShelfId = $_POST['new_shelf_id'];
    $newItemQuantity = $_POST['new_item_quantity'];
    $warehouseId = $_POST['warehouse_id'];

    if (!empty($newItemName) && !empty($newItemQuantity) && !empty($warehouseId)) {
       
        $shelvesDbTool->addShelf($newItemName, $newShelfName, $newShelfId, $warehouseId);

        $inventoryDbTool->addInventory($newItemName, $newItemQuantity);
        
        $shelves = $shelvesDbTool->getShelvesByWarehouseId($warehouseId);
    } else {
        echo "Please fill out all fields!";
    }
}









?>