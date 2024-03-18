<?php

abstract class AbstractPage {

    static function insertHtmlHead()
    {
        echo '<!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Városok</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="fontawesome/css/all.css" type="text/css">
    </head>
    <body>
    
    <h1>Városok</h1>';
    }


    static function showDropDown(array $warehouses)
    {
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
            <label for="countyDropdown">Raktárak:</label>
            <select id="countyDropdown" name="countyDropdown">
            <option value="">Válassz egy Raktárat</option>';
            foreach ($warehouses as $warehouse) {
                echo '<option value="' . $warehouse['id'] . '">' . $warehouse['name'] . '</option>';
                }
            echo '</select>
            <input type="submit" name="submit" value="Küldés">
        </form>';
    }

    /*static function showAddCity()
    {
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
        if (isset($_POST["countyDropdown"])) {
            $selectedCountyId = isset($_POST["countyDropdown"]) ? $_POST["countyDropdown"] : '';
        }
        echo '<input type="hidden" name="county_id" value="' . (isset($selectedCountyId) ? $selectedCountyId : '') . '">
        <label for="new_city_name">Új város neve:</label>
        <input type="text" id="new_city_name" name="new_city_name">
        <label for="new_city_zip">Irányítószám:</label>
        <input type="text" id="new_city_zip" name="new_city_zip">
        <input type="hidden" name="id_county" value="<?php echo $selectedCountyId; ?>">
        <input type="submit" name="add_city" value="Hozzáad">
        </form>';
    }

    static function showContainer(array $allFlags, ?int $idxFlag, array $population, ?int $idxPopulation, array $CountySeat, ?int $idxCountySeat)
    {
        echo '<div class="container">
                <table class = "data" >
                    <tr>
                        <td colspan="3" class="border"><img class="flag-img" src="' . $allFlags[$idxFlag]['flag'] . '" alt="County Flag"></td>
                    </tr>
                    <tr>
                        <td>Népesség:</td>
                        <td>' . $population[$idxPopulation] . '</td>
                    </tr>
                    <tr>
                        <td>Megyeszékhely:</td>
                        <td>' . $CountySeat[$idxCountySeat] . '</td>
                    </tr>
                </table>
            </div>';
    }

    static function showMainTable(array $cities)
    {
        echo '<table>
                <tr>
                    <th>Irányítószám</th><th>Város</th><th>Megye</th><th class="muveletek" colspan="2">Műveletek</th>
                </tr>';
        foreach ($cities as $city) {
            echo '<tr>
                    <td>' . $city['zip_code'] . '</td>
                    <td>' . $city['city'] . '</td>
                    <td>' . $city['county_name'] . '</td>
                    <td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="city_id" value="' . $city['id'] . '"><input type="submit" name="delete_city" value="Törlés"></form></td>
                    <td><form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '"><input type="hidden" name="modify_city_id" value="' . $city['id'] . '"><input type="submit" name="modify_city" value="Módosítás"></form></td>
                </tr>';
        }
        echo '</table>';
    }

    static function showModifyCity(array $cityToModify, ?int $modifyCityId)
    {
        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">
                <input type="hidden" name="modify_city_id" value="' . $modifyCityId . '">
                <label for="modified_city_name">Módosított város neve:</label>
                <input type="text" id="modified_city_name" name="modified_city_name" value="' . $cityToModify['city'] . '">
                <label for="modified_city_zip">Módosított irányítószám:</label>
                <input type="text" id="modified_city_zip" name="modified_city_zip" value="' . $cityToModify['zip_code'] . '">
                <input type="submit" name="modify_city_submit" value="Mentés">
            </form>';
    }*/

}