<?php
include "db_facade.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (array_key_exists('start', $_GET) &&
        array_key_exists('amount', $_GET) &&
        array_key_exists('sortValue', $_GET) &&
        array_key_exists('order', $_GET))
    {
        $productList = getList($_GET['start'], $_GET['amount'], $_GET['sortValue'], $_GET['order']);

        if($productList == false)
            failWithError('Incorrect parameter types', '500 Internal Server Error');
        else
            echo $productList;
    }
    else
    {
        failWithError('Invalid input data', '400 Bad Request');
    }
}
?>