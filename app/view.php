<?php 

$app_config_dir = 'metafields-app-config';
require_once(dirname(__FILE__).'/../shopify-methods/shopify.php');
require(dirname(__FILE__).'/../../'.$app_config_dir.'/conf.php');
$shopify = new Shopify_API;

session_start();

$shop_url = explode('.', $_GET['shop']);
$_SESSION['shop'] = $shop_url[0];

$token = $shopify->get_store_token($_SESSION['shop'], $app_config_dir);
$_SESSION['token'] = $token;

?>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
	<script type="text/javascript">
		ShopifyApp.init({
			"apiKey": "<?php echo SHOPIFY_APP_API_KEY; ?>",
			"shopOrigin": "<?php echo $_GET['shop']; ?>"
		});
	</script>
</head>
<body>
	<section id="solidShopifyContent"></section>
	<script src="assets/js/app.js"></script>
</body>
</html>
