<?php
/**
 * Plugin Name: Restriction on Cart
 * Plugin URI: https://ondway2legend.wordpress.com/
 * Description: Set Restriction in cart. You can set the maximum amount of which an user can buy at a time. Also You can set how many items an user can buy at a time.     
 * Author: Md. Atiqur Rahman Sujon
 * Author URI: https://developersquad.com/
 * Version: 1.0
 License: GPLv2 or later
 */

add_action('admin_menu', 'ondway2legend_restriction_on_cart_menu');

function ondway2legend_restriction_on_cart_menu()
{
    add_options_page('Restriction on Cart', 'Restriction on Cart', 'manage_options', 'functions','ondway2legend_global_custom_options');
}

function ondway2legend_global_custom_options()
{
?>
    <div class="wrap">
        <h2>Points Restriction on Cart</h2>
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options') ?>
            <p><strong>Maximum amount a customer can buy:</strong><br />Leave blank or set to 0 if you do not want any restriction<br />
                <input type="text" name="max_points" size="45" value="<?php echo esc_attr (get_option('max_points')); ?>" />
            </p>
            <p><strong>Maximum Number of items customer can buy:</strong><br />Leave blank or set to 0 if you do not want any restriction<br />
                <input type="text" name="max_items" size="45" value="<?php echo esc_attr (get_option('max_items')); ?>" />
            </p>
            <p><input type="submit" name="Submit" value="Save" /></p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="max_points,max_items" />
        </form>
    </div>
<?php
}



// Check the maximum points and items in cart and checkout
add_action( 'woocommerce_check_cart_items', 'ondway2legend_points_restriction_on_checkout' );

function ondway2legend_points_restriction_on_checkout() {
    // Only run in the Cart or Checkout pages
    if( is_cart() || is_checkout()) {
        
        global $woocommerce;
        $total_quantity = 0;
        $total_price = 0;
        
        // Get the maximum number of items customer can buy
        $maximum_num_products = get_option('max_items');
        // Get the Cart's total number of products
        $cart_num_products = WC()->cart->cart_contents_count;

        // Restrict the quantity if max_items not set to 0 or null    
        if(get_option('max_items') !='' && get_option('max_items') != 0){
            if( $cart_num_products > $maximum_num_products ) {
                // Display our error message
                wc_add_notice( sprintf( '<strong>You can buy maximum %s items at a time.</strong>' 
                    . 'Current Total number of items is: %s.',
                    $maximum_num_products,
                    $cart_num_products ),
                'error' );
            } 
        }
        

        $cart_total = WC()->cart->cart_contents_total;

        $maximum_points =  get_option('max_points');

        // Restrict the quantity if max_points not set to 0 or null   
        if(get_option('max_points') !='' && get_option('max_points') != 0){
            if($cart_total > $maximum_points){
                // Display our error message
                wc_add_notice( sprintf( '<strong>You can buy with maximum %s amount at a time.</strong>' 
                    . 'Current Total amount is: %s.',
                    $maximum_points,
                    $cart_total ),
                'error' );
            }
        }
        

    }
}


?>