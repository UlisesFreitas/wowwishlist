<?php 	$this->wow_wishlist_settings=get_option('wow_wishlist_settings');	 ?>
 <script type="text/javascript">
  jQuery.noConflict();
	jQuery(document).ready(function($){
	 $("span.wow_wishlist_loader").hide();
	 $("#wow_wishlist_message").hide();
	 $('span#wow_wishlist_added').hide();


     $('.add_to_wishlist_button').click(function(){
		 var id_new=$(this).attr("id");
		 $("span#"+id_new).show();
		var prod_id= $(this).attr("data-product_id");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: { action: "wow_wishlist_action",
				product_id: prod_id,
				action_id : "add"
           		  },
			success: function(result)				{
				if (result[0].response == 'success') {

				$("span#"+id_new).hide();
				$('a#'+id_new).text('Product added!');
				$('.wow_wishlist_added_'+prod_id).css("display","block");
				$('a#'+id_new).attr('href','<?php echo get_permalink($this->wow_wishlist_settings['wow_wishlist_page']); ?>')
				.text('<?php echo 	$this->wow_wishlist_settings['wow_wishlist_view_wishlist_text']; ?>').attr("id","");
				setTimeout(function(){$('.wow_wishlist_added_'+prod_id).fadeOut(); }, 500);

				}

			}
       	});
	  });

		 $('.wow_wishlist_remove').click(function(){
		var prod_id= $(this).attr("data-product_id");
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: { action: "wow_wishlist_action",
				product_id: prod_id,
				action_id : "remove"
           		  },
			success: function(result){
			var parts = result[0].response.split('-');

			if (parts[0] == 'removed') {
				var pid = parts[1];
				$('tr#wow-wishlist-product-id-'+pid).hide();
				//location.href="<?php get_permalink($this->wow_wishlist_settings['wow_wishlist_page']); ?>";
				}

				}
       				 });
						  });

    							});
	</script>