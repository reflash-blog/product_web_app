<?php
$product1 = array(
	'id' => 2,
	'name' => 'SomeProduct2',
	'description' => 'This is a simple product',
	'price' => '1242.0',
	'image_url' => 'http://kevy.com/wp-content/uploads/2013/09/total-product-marketing.jpg');

$product2 = array(
	'id' => 0, 
	'name' => 'SomeProduct', 
	'description' => 'This is a simple product', 
	'price' => '1112.0',
	'image_url' => 'http://kevy.com/wp-content/uploads/2013/09/total-product-marketing.jpg');

$products = array($product1,$product2);

echo json_encode($products);
?>