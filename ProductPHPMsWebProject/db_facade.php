<?php
    $servername = "us-cdbr-azure-west-c.cloudapp.net:3306";
    $username = "b048dbd3366153";
    $password = "9f54990f";
    $dbname = "ProductMySQL";

# TODO change to trigger on sort_by_id boolean value
# TODO change to work with cache 
function getList($start, $amount, $sortOrder){
    global $servername, $username, $password, $dbname;

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: ". mysql_error());
    }

    $sql = createQuery($start, $amount, $sortOrder);
    $result = mysqli_query($conn, $sql);

    $products = array();
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($products, array(
                'id' => $row['Id'],
                'name' => $row['Name'],
                'description' => $row['Description'],
                'price' => $row['Price'],
                'url' => $row['Url']));
        }
    }

    mysqli_close($conn);

	return json_encode($products);
}

function getById($id){
    global $servername, $username, $password, $dbname;

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: ". mysql_error());
    }

    $sql = "SELECT * FROM Product WHERE Id=$id";
    $result = mysqli_query($conn, $sql);

    $products = array();
    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($products, array(
                'id' => $row['Id'],
                'name' => $row['Name'],
                'description' => $row['Description'],
                'price' => $row['Price'],
                'url' => $row['Url']));
        }
    }
    else
    {
        die("Id not found ");
    }

    mysqli_close($conn);

	return json_encode($products[0]);
}

function insertProduct($name, $desc, $price, $url)
{
    global $servername, $username, $password, $dbname;

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysql_error());
    }

    $sql = "INSERT INTO Product (Name, Description, Price, Url) VALUES ('$name', '$desc', $price, '$url')";
    $result = mysqli_query($conn, $sql);
    
    mysqli_close($conn);

    return $result;
}

function updateProduct($id, $name, $desc, $price, $url)
{
    global $servername, $username, $password, $dbname;

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysql_error());
    }

    $sql = "UPDATE Product SET Name='$name', Description='$desc', Price=$price, Url='$url' WHERE Id=$id";
    $result = mysqli_query($conn, $sql);

    mysqli_close($conn);

    return $result;
}

function deleteProduct($id)
{
    global $servername, $username, $password, $dbname;

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysql_error());
    }

    $sql = "DELETE FROM Product WHERE Id=$id";
    $result = mysqli_query($conn, $sql);

    mysqli_close($conn);

    return $result;
}

function createQuery($start, $amount, $sortedid)
{
    $end = $start + $amount;

    if($sortedid){
        return "SELECT * FROM Product ORDER BY Id ASC LIMIT $start,$end";
    }
    return "SELECT * FROM Product ORDER BY Price ASC LIMIT $start,$end";

}

function failWithError($message, $httpError)
{
    $data = array('type' => 'error', 'message' => $message);
    header("HTTP/1.1 $httpError");
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
}

?>