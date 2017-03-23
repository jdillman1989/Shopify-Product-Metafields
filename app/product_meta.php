<?php 

require_once(dirname(__FILE__).'/../shopify-methods/shopify.php');
$shopify = new Shopify_API;
$namespace = 'solid-custom-fields';

session_start();

$query = array(
	"Content-type" => "application/json"
);

$get_data = json_decode($_GET['data'], TRUE);

if (!is_null($get_data['meta'])) {

	$get_product_meta = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], '/admin/products/'.$get_data['product']['id'].'/metafields.json', array(), 'GET');

	$put_product_meta = json_decode($get_product_meta['response'], TRUE);

	$modify_data = array();

	foreach ($put_product_meta['metafields'] as $put_meta) {
		if ($put_meta['id'] == $get_data['meta']['id']) {
			$modify_data = array(
				"metafield" => array(
					"id" => $put_meta['id'],
					"value" => $get_data['meta']['value'],
					"value_type" => "string"
				)
			);
		}
	}

	$post_meta = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], '/admin/products/'.$get_data['product']['id'].'/metafields/'.$get_data['meta']['id'].'.json', $modify_data, 'PUT');
}

$product_meta = $shopify->shopify_call($_SESSION['token'], $_SESSION['shop'], '/admin/products/'.$get_data['product']['id'].'/metafields.json', array(), 'GET');

$meta = json_decode($product_meta['response'], TRUE);

$return = '<p id="product-list-return">&larr; Return to product list</p>';
$return .= '<h2>'.$get_data['product']['title'].'</h2>';

if (count($meta['metafields'])) {
	foreach ($meta['metafields'] as $metafield) {
		if ($metafield['namespace'] == $namespace) {
			$return .= '<p>'.$metafield['key'].':</p>';
			$return .= '<input id="'.$metafield['key'].'" value="'.$metafield['value'].'">';
			$return .= '<button class="modify-meta" data-metaid="'.$metafield['id'].'" data-metakey="'.$metafield['key'].'" data-productid="'.$get_data['product']['id'].'" data-producttitle="'.$get_data['product']['title'].'">Save '.$metafield['key'].'</button>';
		}
	}
}

echo $return;

?>