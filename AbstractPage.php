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
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    </head>
    <body>
   
    <h1>Raktárak</h1>

    <button><a href="main.php">Adatbázis kezelése</a></button>';
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
    static function showMainTable(array $shelves, array $inventory)
    {  
        echo '<table>
                <tr>
                    <th>id</th><th>Polcok</th><th>Termékek</th><th>Mennyiség</th><th class="muveletek" colspan="2">Műveletek</th>
                </tr>';
        foreach ($shelves as $shelf) {
            echo '<tr>';
            echo '<td id='. $shelf['id'] . '>' . $shelf['id'] . '</td>';
            echo '<td>' . $shelf['shelf_line'] . '</td>';
            echo '<td>' . $shelf['item_name'] . '</td>';

          
            $quantity = isset($inventory[$shelf['item_name']]) ? $inventory[$shelf['item_name']] : 'N/A';
            echo '<td>' . $quantity . '</td>';
            echo '<td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="shelf_id" value="' . $shelf['id'] . '"><input type="submit" name="delete_shelf" value="Törlés"></form></td>';
            echo '<td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="modify_shelf_id" value="' . $shelf['id'] . '"><input type="submit" name="modify_shelf" value="Módosítás"></form></td>';
            if($quantity < 10)
            {
                echo '<td>Hiánycikk</td>';
            }
            else
            {
                echo '<td>Elegendő mennyiség</td>';
            }
            echo '</tr>';

        }
        echo '</table>';
    }

    static function showMainTable2(array $shelves, array $inventory)
    {  
        echo '<table>
                <tr>
                    <th>id</th><th>Polcok</th><th>Termékek</th><th>Mennyiség</th>
                </tr>';
        foreach ($shelves as $shelf) {
            echo '<tr>';
            echo '<td id='. $shelf['id'] . '>' . $shelf['id'] . '</td>';
            echo '<td>' . $shelf['shelf_line'] . '</td>';
            echo '<td>' . $shelf['item_name'] . '</td>';

          
            $quantity = isset($inventory[$shelf['item_name']]) ? $inventory[$shelf['item_name']] : 'N/A';
            echo '<td>' . $quantity . '</td>';
            
        }
        echo '</table>';
    }

    
    static function showModifyShelf(array $shelfToModify, int $modifyShelfId, array $inventory)
    {
        $quantity = isset($inventory[$shelfToModify['item_name']]) ? $inventory[$shelfToModify['item_name']] : 'N/A';
    
        echo '<h3>Módosítás: Polc #' . $modifyShelfId . '</h3>';
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <input type="hidden" name="modify_shelf_id" value="' . $modifyShelfId . '">
            <label for="modified_shelf_line">Módosított Polc neve:</label>
            <input type="text" id="modified_shelf_line" name="modified_shelf_line" value="' . $shelfToModify['shelf_line'] . '">
            <br>
            <label for="modified_shelf_itemName">Módosított Termék neve:</label>
            <input type="text" id="modified_shelf_itemName" name="modified_shelf_itemName" value="' . $shelfToModify['item_name'] . '">
            <br>
            <label for="modified_shelf_id">Módosított Polc Id:</label>
            <input type="number" id="modified_shelf_id" name="modified_shelf_id" value="' . $shelfToModify['id'] . '">
            <br>
            <label for="modified_item_qty">Módosított Termék mennyisége:</label>
            <input type="number" id="modified_item_qty" name="modified_item_qty" value="' .  $quantity . '">
            <br>
            <input type="submit" name="modify_shelf_submit" value="Mentés">
        </form>';
    }

    static function showAddInventory()
    {
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
    
       
        if (isset($_POST["warehouseDropdown"])) {
            $selectedWarehouseId = isset($_POST["warehouseDropdown"]) ? $_POST["warehouseDropdown"] : '';
        }
    
        echo '<input type="hidden" name="warehouse_id" value="' . (isset($selectedWarehouseId) ? $selectedWarehouseId : '') . '">
            <label for="new_item_name">Új termék neve:</label>
            <input type="text" id="new_item_name" name="new_item_name">
            <label for="new_shelf_id">Új Polc Id:</label>
            <input type="number" id="new_shelf_id" name="new_shelf_id">
            <label for="new_shelf_name">Új Polc neve:</label>
            <input type="text" id="new_shelf_name" name="new_shelf_name">
            <label for="new_item_quantity">Mennyiség:</label>
            <input type="number" id="new_item_quantity" name="new_item_quantity">
            <input type="submit" name="add_inventory" value="Hozzáad">
            </form>';
    }

    static function showPdfExport()
    {
        echo '<form method="post" action="pdf.php">
                <button id="btn-pdf" name="btn-pdf" title="Pdf">PDF</button>
                </form>';
    }

    static function showMailButton(){
        echo '
            
            <form method="post" action="mail.php" class ="Mail">
                <button id="btn-mail" name="btn-mail" title="Mail">
                <i class="fa-solid fa-envelope"></i>&nbsp;Mail</button>
            </form>
            ';
    }

    static function login()
    {
        echo ' <!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés és Regisztráció</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container" id="loginForm">
    <h2>Bejelentkezés</h2>
    <form method= "post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <input type="email" name="Email" id="Email" placeholder="Email" required><br>
        <input type="password" name="Jelszó" id="Jelszó" placeholder="Jelszó" required><br>
        <input type="submit" id="Bejelentkezés" name="Bejelentkezés" value="Bejelentkezés">
    </form>
    <div class="options">
        <a href="registration.php">Regisztráció</a>
    </div>
</div>

</body>
</html>';
    

   
    
    }


static function registration()
    {
        echo ' <!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés és Regisztráció</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container" id="registrationForm">
    <h2>Regisztráció</h2>
    <form method= "post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
        <select id="privilegeDropdown" name="privilegeDropdown">
        <option value="User">User</option>
        <option value="Admin">Admin</option>
        </select>
        <input type="text" id="Név" name="Név" placeholder="Név" required><br>
        <input type="email" id="Email" name="Email" placeholder="Email" required><br>
        <input type="password" id="Jelszó" name="Jelszó" placeholder="Jelszó" required><br>
        <input type="password" id="Jelszó2" name="Jelszó2" placeholder="Jelszó újra" required><br>
        <input type="submit" id="Regisztráció" name="Regisztráció" value="Regisztráció">
    </form>
    <div class="options">
        <a href="login.php">Vissza a bejelentkezéshez</a>
    </div>
</div>

</body>
</html>';

    }

    static function validation()
    {
        echo '<!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Raktár</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    </head>
    <body>
   
    <button><a href="login.php">OK</a></button>
    ';


    }
}
