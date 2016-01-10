<?php
include "db_facade.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (array_key_exists('id', $_GET)){
        $productList = getById($_GET['id']);

        if($productList == false)
            failWithError('Id not found', '500 Internal Server Error');
        else
            echo $productList;
    }
    else
    {
        failWithError('Invalid input data', '400 Bad Request');
    }
}
?>