$(document).ready(function() {
	function initialState() {
		$.ajax({
			type: 'GET',
			dataType : 'json',
			async: false,
			url: 'get_products.php',
			data: {},
			complete: function(response) {
				$('#solidShopifyContent').html(response.responseText);
			}
		});
	}

	initialState();

	$('#solidShopifyContent').on('click', '#product-list-return', function() {
		initialState();
	});

	$('#solidShopifyContent').on('click', '#update-all-products', function() {
		$.ajax({
			type: 'GET',
			dataType : 'json',
			async: false,
			url: 'update_products.php',
			data: {},
			complete: function(response) {
				$('#solidShopifyContent').append(response.responseText);
			}
		});
	});

	$('#solidShopifyContent').on('click', '#product-list li', function() {
		var productID = $(this).data('productid');
		var productTitle = $(this).data('producttitle');
		var data = JSON.stringify({ product: { id: productID, title: productTitle }});
		$.ajax({
			type: 'GET',
			dataType : 'json',
			async: false,
			url: 'product_meta.php',
			data: {data},
			complete: function(response) {
				$('#solidShopifyContent').html(response.responseText);
			}
		});
	});

	$('#solidShopifyContent').on('click', 'button.modify-meta', function(e) {
		e.preventDefault();
		var productID = $(this).data('productid');
		var productTitle = $(this).data('producttitle');
		var metaID = $(this).data('metaid');
		var metaKey = $(this).data('metakey');
		var newValue = $('#solidShopifyContent').find('#' + metaKey).val();
		var data = JSON.stringify({ meta: { id: metaID, value: newValue}, product: { id: productID, title: productTitle}});
		$.ajax({
			type: 'GET',
			dataType : 'json',
			async: false,
			url: 'product_meta.php',
			data: {data},
			complete: function(response) {
				$('#solidShopifyContent').html(response.responseText);
				$('#solidShopifyContent').append("Saved " + metaKey);
			}
		});
	});
});