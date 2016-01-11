<?php
    $servername = "us-cdbr-azure-west-c.cloudapp.net:3306";
    $username = "b048dbd3366153";
    $password = "9f54990f";
    $dbname = "ProductMySQL";

    $memcacheServerName = "pub-memcache-12335.us-east-1-3.4.ec2.garantiadata.com";
    $memcachePort = 12335;

function getList($start, $amount, $sortOrder){
    global $servername, $username, $password, $dbname;

    $valueFromCache = getFromCache("K$start.$amount.$sortOrder");

    if($valueFromCache)
        return $valueFromCache;

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

    $jsonResult = json_encode($products);
    addToCache("K$start.$amount.$sortOrder", $jsonResult);

	return $jsonResult;
}

function getById($id){
    global $servername, $username, $password, $dbname;

    $valueFromCache = getFromCache("D.$id");

    if($valueFromCache)
        return $valueFromCache;

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

	$jsonResult = json_encode($products[0]);
    addToCache("D.$id", $jsonResult);

	return $jsonResult;
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

    clearCache();

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

    clearCache();

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

    clearCache();

    return $result;
}

function createQuery($start, $amount, $sortedid)
{
    $end = $start + $amount;
    return "SELECT * FROM Product ORDER BY $sortedid ASC LIMIT $start,$end";
}

function getFromCache($key)
{
    global $memcacheServerName, $memcachePort;

    $memcache_obj = memcache_connect($memcacheServerName, $memcachePort);
    return memcache_get($memcache_obj, $key);
}

function addToCache($key, $item)
{
    global $memcacheServerName, $memcachePort;

    $memcache_obj = memcache_connect($memcacheServerName, $memcachePort);
    memcache_add($memcache_obj, $key, $item, false, 60);
}

function clearCache()
{
    global $memcacheServerName, $memcachePort;

    $memcache_obj = memcache_connect($memcacheServerName, $memcachePort);
    memcache_flush($memcache_obj);
}


function failWithError($message, $httpError)
{
    $data = array('type' => 'error', 'message' => $message);
    header("HTTP/1.1 $httpError");
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
}

?>