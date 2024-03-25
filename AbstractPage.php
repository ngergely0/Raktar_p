<?php
 
abstract class AbstractPage {
 
    static function insertHtmlHead()
    {
        echo '<!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Raktár</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    </head>
    <body>
   
    <h1>Ráktarak</h1>';
    }
 
 
    static function showDropDown(array $warehouses)
    {
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <label for="warehouseDropdown">Raktárak:</label>
            <select id="warehouseDropdown" name="warehouseDropdown">
            <option value="">Válassz egy Raktárat</option>';
            foreach ($warehouses as $warehouse) {
                echo '<option value="' . $warehouse['id'] . '">' . $warehouse['name'] . '</option>';
                }
            echo '</select>
            <input type="submit" name="submit" value="Küldés">
        </form>';
    }
    static function showMainTable(array $shelves)
    {  
        echo '<table>
                <tr>
                    <th>id</th><th>Polcok</th><th>Termékek</th><th class="muveletek" colspan="2">Műveletek</th>
                </tr>';
        foreach ($shelves as $shelf) {
            echo '<tr>';
            echo '<td>' . $shelf['id'] . '</td>';
            echo '<td>' . $shelf['shelf_line'] . '</td>';
            if (!empty($shelf['inventory'])) {
                foreach ($shelf['inventory'] as $item) {
                    echo '<td>' . $item['item_name'] . '</td>';
                }
            } else {  
                echo '<td colspan="2">Nincsenek termékek a polcon</td>';
            }  
                echo ' <td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="shelf_id" value="' . $shelf['id'] . '"><input type="submit" name="delete_shelf" value="Törlés"></form></td>';
                echo ' <td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="modify_shelf_id" value="' . $shelf['id'] . '"><input type="submit" name="modify_shelf" value="Módosítás"></form></td>';
                echo '</tr>';
        }
        echo '</table>';
    }
 
}