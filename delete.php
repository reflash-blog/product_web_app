<?php
include "db_facade.php";

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (array_key_exists('id', $request))
        {
            $deleteResult = deleteProduct($request['id']);

            if($deleteResult == false)
                failWithError('Data update failed', '500 Internal Server Error');
        }
        else
        {
            failWithError('Invalid input data', '400 Bad Request');
        }
    }
    
?>