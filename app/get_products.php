<?php 

// Get our helper functions
require_once(dirname(__FILE__).'/../shopify-methods/shopify.php');
$shopify = new Shopify_API;

session_start();

$query = array(
	"Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
);

// Run API call to get products
$products = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], "/admin/products.json", array(), 'GET');

$products = json_decode($products['response'], TRUE);

$return = '<h2>Products</h2>';

$return .= '<ul id="product-list">';

foreach ($products['products'] as $product) {
	$return .= '<li data-productid="'.$product['id'].'" data-producttitle="'.$product['title'].'">'.$product['title'].'</li>';
}

$return .= '</ul>';
$return .= '<button id="update-all-products">Update Products</button>';

echo $return;

?>