<?php

ini_set('memory_limit','1024M');


class CsvTools {
    
    const FILENAME  = "car_parts_inventory.csv";
    public $csvData;
    public $result = [];
    public $warehouses = [];
    public $shelves = [];
    public $items = [];
    public $warehouseIds = [];
    public $header;  
    public $idxWarehouse; 
    public $idxShelf;  
    public $idxitemName;
    public $idxQty;
    public $idxwarehouseId;

    function __construct(){
        $this->csvData = $this->getCsvData(self::FILENAME);
        $this->header = $this->csvData[0];
        $this->idxWarehouse = array_search ('warehouse_name', $this->header);
        $this->idxShelf = array_search ('shelf_line', $this->header);
        $this->idxitemName = array_search ('item_name', $this->header);
        $this->idxQty = array_search ('quantity', $this->header);
        $this->idxwarehouseId = array_search ('warehouse_id', $this->header);

    }

    function getCsvData($fileName)
    {
        if (!file_exists($fileName)) {
            echo "$fileName nem található. ";
            return false;
        }
        $csvFile = fopen($fileName, 'r');
        $lines = [];
        while (!feof($csvFile)) {
            $line = fgetcsv($csvFile);
            $lines[] = $line;
        }
        fclose($csvFile);
        return $lines;
    }

    function getWarehouses($csvData)
    {
        if (empty($csvData)) {
            echo "Nincs adat.";
            return false;
        }
        $warehouse = '';
        foreach ($this->csvData as $idx => $line) {
            if(!is_array($line)){
                continue;
            }
            if ($idx == 0) {
                continue;
            }
            if ($warehouse != $line[$this->idxWarehouse]){
                $warehouseId = $line[$this->idxwarehouseId];
                $warehouse = $line[$this->idxWarehouse];
                $warehouses[] = [$warehouseId,$warehouse];
            }
        }
        return $warehouses;
    }

    function getShelves($csvData)
    {
        if (empty($csvData)) {
            echo "Nincs adat.";
            return false;
        }
        $shelf = '';
        foreach ($this->csvData as $idx => $line) {
            if(!is_array($line)){
                continue;
            }
            if ($idx == 0) {
                continue;
            }
            if ($shelf != $line[$this->idxShelf]){
                $shelf = $line[$this->idxShelf];
                $shelves[] = $shelf;
            }
        }
        return $shelves;
    }

    function getInventory($csvData)
    {
        if (empty($csvData)) {
            echo "Nincs adat.";
            return false;
        }
        $itemName = '';
        foreach ($this->csvData as $idx => $line) {
            if(!is_array($line)){
                continue;
            }
            if ($idx == 0) {
                continue;
            }
            if ($itemName != $line[$this->idxitemName]){
                $itemName = $line[$this->idxitemName];
                $Qty = $line[$this->idxQty];
                $items[] = [$itemName,$Qty];
            }
        }
        return $items;
    }

    function getWarehouseId($csvData)
    {
        if (empty($csvData)) {
            echo "Nincs adat.";
            return false;
        }
        $warehouseId = '';
        foreach ($this->csvData as $idx => $line) {
            if(!is_array($line)){
                continue;
            }
            if ($idx == 0) {
                continue;
            }
            if ($warehouseId != $line[$this->idxwarehouseId]){
                $warehouseId = $line[$this->idxwarehouseId];
                $warehouseIds[] = $warehouseId;
            }
        }
        return $warehouseIds;
    }

    function truncateWarehousesTable($warehousesDbTool,$csvData){
        $warehousesDbTool->truncateWarehouses();
        $warehouses = $this->getWarehouses($csvData);
        foreach ($warehouses as $warehouse){
            $warehousesDbTool->createWarehouses($warehouse[0], $warehouse[1]);
        }
    }

    function truncateShelvesTable($shelvesDbTool,$csvData){
        $shelvesDbTool->truncateShelves();
        $shelves = $this->getShelves($csvData);
        foreach ($shelves as $shelf){
            $shelvesDbTool->createShelves($shelf);
        }
    }

    function truncateInventoryTable($inventoryDbTool,$csvData){
        $inventoryDbTool->truncateInventory();
        $inventory = $this->getInventory($csvData);
        foreach ($inventory as $items){
            $inventoryDbTool->createInventory($items[0], $items[1]);
        }
    }

}



