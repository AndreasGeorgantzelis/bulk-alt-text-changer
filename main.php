<?php
/*
* Plugin Name: wp-bulk-alt-text-changer
* Author: andreasgeorgantzelis@gmail.com
* Description: WordPress plugin that changes the alt-text of the product images, according to the product title.
* Requires at least: 3.0
* Tested up to: 5.4
* Requires PHP: 7.0
*/

// Function to update alt text on product save
function update_alt_text_on_product_save($post_id) {
    // Check if the post type is 'product' (you may need to adjust this based on your actual post type)
    if (get_post_type($post_id) === 'product') {
        // Get the product title
        $product_title = get_the_title($post_id);

        // Get all images associated with the product (including those in content)
        $attachments = get_posts(array(
            'post_parent'    => $post_id,
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_mime_type' => 'image',
            'fields'         => 'ids',
        ));

        // Update alt text for each associated image
        foreach ($attachments as $attachment_id) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $product_title);
        }
    }
}

// Function to update alt text on existing products
function update_alt_text_on_existing_products() {
    // Get all products
    $products = get_posts(array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
    ));

    foreach ($products as $product) {
        $product_id = $product->ID;
        $product_title = get_the_title($product_id);

        // Get all images associated with the product (including those in content)
        $attachments = get_posts(array(
            'post_parent'    => $product_id,
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_mime_type' => 'image',
            'fields'         => 'ids',
        ));

        // Update alt text for each associated image
        foreach ($attachments as $attachment_id) {
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $product_title);
        }
    }
}

// Hook to run the function on product save
add_action('save_post', 'update_alt_text_on_product_save');

// Runs the function once to update existing products
update_alt_text_on_existing_products();
