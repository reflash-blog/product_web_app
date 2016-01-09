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
else
{
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (array_key_exists('id', $request)&&
            array_key_exists('name', $request) &&
            array_key_exists('description', $request) &&
            array_key_exists('price', $request) &&
            array_key_exists('url', $request))
        {
            $updateResult = updateProduct($request['id'], $request['name'], $request['description'], $request['price'], $request['url']);

            if($insertResult == false)
                failWithError('Data update failed', '500 Internal Server Error');
        }
        else
        {
            failWithError('Invalid input data', '400 Bad Request');
        }
    }
    
}
?>