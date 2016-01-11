<?php
include "db_facade.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    if (array_key_exists('name', $request) &&
        array_key_exists('description', $request) &&
        array_key_exists('price', $request) &&
        array_key_exists('url', $request))
    {
        $insertResult = insertProduct($request['name'], $request['description'], $request['price'], $request['url']);

        if($insertResult == false)
            failWithError('Data insertion failed', '500 Internal Server Error');
    }
    else
    {
        failWithError('Invalid input data', '400 Bad Request');
    }
}

?>