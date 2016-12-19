<?php $this->wow_wishlist_settings=get_option('wow_wishlist_settings'); ?>
<div class="woocommerce-wishlist-wrapper woocommerce">
<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
            <?php if($this->wow_wishlist_settings['wow_wishlist_enable_image']==1): ?>
			<th class="product-thumbnail">&nbsp;</th>
            <?php endif; ?>
			<th class="product-name"><?php _e( 'Product', $this->plugin_name ); ?></th>
            <?php if($this->wow_wishlist_settings['wow_wishlist_enable_unit_price']==1): ?>
			<th class="product-price"><?php _e( 'Price', $this->plugin_name ); ?></th>
            <?php endif; ?>
            <?php if($this->wow_wishlist_settings['wow_wishlist_enable_stock']==1): ?>
            <th class="product-stock"><?php _e( 'Stock', $this->plugin_name ); ?></th>
            <?php endif; ?>
            <?php if($this->wow_wishlist_settings['wow_wishlist_enable_add_to_cart']==1): ?>
			<th class="product-action"></th>
            <?php endif; ?>

		</tr>
	</thead>
	<tbody>
		<?php
		$products=$this->wow_wishlist_post_in_ids();
		if(sizeof($products)>0){
		$loop=new Wp_Query(array("post_type"=>"product","posts_per_page"=>-1,"post__in"=>$products,"order"=>"DESC"));
		while($loop->have_posts()):$loop->the_post();
			global $product;
				?>
				<tr id="wow-wishlist-product-id-<?php echo get_the_ID(); ?>">
					<td class="product-remove">
					<?php
					echo '<a href="javascript:void(0)" class="wow_wishlist_remove remove"  data-product_id="'.get_the_ID().'" >&times;</a>';

						?>
					</td>
				<?php if($this->wow_wishlist_settings['wow_wishlist_enable_image']==1): ?>
					<td class="product-thumbnail">
					<a href="<?php the_permalink(); ?>"><?php echo $product->get_image(); ?></a>
					</td>
				<?php endif; ?>
					<td class="product-name">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</td>
				<?php if($this->wow_wishlist_settings['wow_wishlist_enable_unit_price']==1): ?>
					<td class="product-price">
						<?php echo $product->get_price_html(); ?>
					</td>
                 <?php endif; ?>

				<?php
				$stock_status= get_post_meta(get_the_ID(),'_stock_status',true);
				 if($this->wow_wishlist_settings['wow_wishlist_enable_stock']==1): ?>
					<td class="product-stock">
					<?php
					 if($product->managing_stock()=='0')
					 {
					   $stock_message=__('In Stock', $this->plugin_name);
					 }else{
						$stock_message=$product->get_stock_quantity()." ".__('in Stock', $this->plugin_name);
					 }
					echo $stock_message;

					if($stock_status=='instock'){ ?>
						<span class="stock in-stock"><?php sprintf(__('%s',$this->plugin_name),$stock_message); ?></span>
                    <?php }else{ ?>
						<span class="stock out-of-stock"><?php _e('Out of Stock',$this->plugin_name); ?></span>
                    <?php } ?>
					</td>
                    <?php endif; ?>

                    <?php if($this->wow_wishlist_settings['wow_wishlist_enable_add_to_cart']==1): ?>
                    <td class="product-price">
					<?php

					if($stock_status=='instock'):

					echo "<a id='wow_wishlist_add_cart' class='button' href='" . $product->add_to_cart_url() ."'>".__($product->add_to_cart_text())."</a>";
					endif;
					?>
					</td>
			<?php endif; ?>

				</tr>
				<?php
		endwhile; wp_reset_query();

		}else{ ?>
			<tr><td colspan='6'><?php _e('No Items found on your wishlist!',$this->plugin_name); ?></td></tr>
        <?php } ?>
	</tbody>
</table>
</div>