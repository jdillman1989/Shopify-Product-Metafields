<?php 

require_once(dirname(__FILE__).'/../shopify-methods/shopify.php');
$shopify = new Shopify_API;
$product_field_config  = file_get_contents($_SESSION['shop'].'_product_fields.json');
$product_field_data = json_decode($product_field_config, true);
$namespace = 'solid-custom-fields';

session_start();

$query = array(
	"Content-type" => "application/json"
);

$products = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], "/admin/products.json", array(), 'GET');

$products = json_decode($products['response'], TRUE);

$list_updated = array();

foreach ($products['products'] as $product) {

	$get_product_meta = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], '/admin/products/'.$product['id'].'/metafields.json', array(), 'GET');

	$put_product_meta = json_decode($get_product_meta['response'], TRUE);

	foreach ($product_field_data as $field) {
		$this_field_needs_updating = true;

		if (count($put_product_meta['metafields'])) {
			foreach ($put_product_meta['metafields'] as $put_meta) {
				if ($put_meta['namespace'] == $namespace) {
					if ($put_meta['key'] == $field['key']) {
						$this_field_needs_updating = false;
					}
				}
			}
		}

		if ($this_field_needs_updating) {
			$data = array(
				'metafield' => array(
					'namespace' => $namespace,
					'key' => $field['key'], 
					'value' => $field['default_value'],
					'value_type' => $field['value_type']
				) 
			);
			$post_meta = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], '/admin/products/'.$product['id'].'/metafields.json', $data, 'POST');
			$list_updated[] = array('product' => $product['title'] );
		}
	}
}

$return = '<br>';

if (count($list_updated)) {
	$return .= '<p><strong>Updated:</strong></p>';
	foreach ($list_updated as $updated) {
		$return .= '<p>'.$updated['product'].'</p>';
	}
}
else{
	$return .= '<p><strong>All products up to date.</strong></p>';
}

echo $return;

?>