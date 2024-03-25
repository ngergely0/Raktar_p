<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('AbstractPage.php');
require_once('CsvTools.php');
require_once('DBShelves.php');
require_once('DBInventory.php');
require_once('DBWarehouses.php');
require_once('WarehousesDbTools.php');
require_once('InventoryDbTools.php');
require_once('ShelvesDbTools.php');

require_once('DB.php');
$db = new DB();


$csvtools = new CsvTools();
$warehousesDbTool = new WarehousesDbTools();
$shelvesDbTool = new ShelvesDbTools();
$inventoryDbTool = new InventoryDbTools();
$dbWarehouses = new DBWarehouses();
$dbInventory = new DBInventory();
$dbShelves = new DBShelves();



if (isset($_POST['import-btn']) && isset($_FILES['input-file']['tmp_name'])) {
    $tmpFilePath = $_FILES['input-file']['tmp_name'];
    $csvtools->importCsv($tmpFilePath, $warehousesDbTool, $shelvesDbTool, $inventoryDbTool);

    $csvData = $csvtools->getCsvData($csvtools::FILENAME);

    $createWarehousesTable = $dbWarehouses->createTable();
    $createShelvesTable = $dbShelves->createTable();
    $createInventoryTable = $dbInventory->createTable();    

    $getWarehouseId = $csvtools->getWarehouseId($csvData);
    $getShelves = $csvtools->getShelves($csvData);


    $truncateWarehousesTable = $csvtools->truncateWarehousesTable($warehousesDbTool,$csvData);
    $truncateShelvesTable = $csvtools->truncateShelvesTable($shelvesDbTool,$csvData);
    $truncateInventoryTable = $csvtools->truncateInventoryTable($inventoryDbTool, $csvData);



    $updateShelves = $shelvesDbTool->updateShelves($getWarehouseId,$getShelves);
}

    if(isset($_POST['clear-tables-btn'])) {
        $warehousesDbTool->truncateWarehouses();
        $shelvesDbTool->truncateShelves();
        $inventoryDbTool->truncateInventory();   
        $dbWarehouses->createTable();
        $dbShelves->createTable();
        $dbInventory->createTable();
        
    }
    
    if(isset($_POST['create-tables'])) {
        $dbWarehouses->createTable();
        $dbShelves->createTable();
        $dbInventory->createTable();    
    
    }
    
    if(isset($_POST['delete-tables-btn'])) {
        $warehousesDbTool->deleteWarehouses();
        $shelvesDbTool->deleteShelves();
        $inventoryDbTool->deleteInventory();
    }

    if(isset($_POST['create-database'])) {
        if(!DB::databaseExists()){
            DB::createDatabase();
        }
        else{
            echo 'Létezik';
        }
    }

    if(isset($_POST['delete-database'])){
        $db->deleteDatabase();
    }



?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css">
    <title>Document</title>
</head>
<body>
<button><a href="index.php"></a>Főoldal</button>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="input-file">
    <button type="submit" name="import-btn">Import</button>
    <button type="submit" name="delete-tables-btn">Táblák Törlése</button>
    <button type="submit" name="create-database">Adatbázis Létrehozása</button>
    <button type="submit" name="delete-database">Adatbázis Törlése</button>
    <button type="submit" name="create-tables">Táblák Létrehozása</button>
    <button type="submit" name="clear-tables-btn">Táblák kiűrítése</button>
</form>
</body>
</html>





