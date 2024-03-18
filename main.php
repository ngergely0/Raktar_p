<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('AbstractPage.php');
require_once('CsvTools.php');
require_once('WarehousesDbTools.php');
require_once('InventoryDbTools.php');
require_once('ShelvesDbTools.php');
require_once('DBShelves.php');
require_once('DBInventory.php');
require_once('DBWarehouses.php');

$csvtools = new CsvTools();
$warehousesDbTool = new WarehousesDbTools();
$shelvesDbTool = new ShelvesDbTools();
$inventoryDbTool = new InventoryDbTools();
$dbWarehouses = new DBWarehouses();
$dbInventory = new DBInventory();
$dbShelves = new DBShelves();
$csvData = $csvtools->getCsvData($csvtools::FILENAME);

$createWarehousesTable = $dbWarehouses->createTable();
$createShelvesTable = $dbShelves->createTable();
$createInventoryTable = $dbInventory->createTable();


$truncateWarehousesTable = $csvtools->truncateWarehousesTable($warehousesDbTool,$csvData);
$truncateShelvesTable = $csvtools->truncateShelvesTable($shelvesDbTool,$csvData);
$truncateInventoryTable = $csvtools->truncateInventoryTable($inventoryDbTool, $csvData);

$getWarehouseId = $csvtools->getWarehouseId($csvData);
$getShelves = $csvtools->getShelves($csvData);

$updateShelves = $shelvesDbTool->updateShelves($getWarehouseId,$getShelves);









