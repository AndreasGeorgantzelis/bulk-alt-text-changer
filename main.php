/*
* Plugin Name: wp-bulk-alt-text-changer
* Author: andreasgeorgantzelis@gmail.com
* Description: Wordpress plugin that changes the alt-text of the product images, according to the product title.
*/

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

add_action('save_post', 'update_alt_text_on_product_save');
