<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://mobeenabdullah.com
 * @since      1.0.0
 *
 * @package    Extel
 * @subpackage Extel/admin/partials
 */

// Register Custom Post Type Certification
function create_certification_cpt() {

	$labels = array(
		'name' => _x( 'Certifications', 'Post Type General Name', 'extel' ),
		'singular_name' => _x( 'Certification', 'Post Type Singular Name', 'extel' ),
		'menu_name' => _x( 'Certifications', 'Admin Menu text', 'extel' ),
		'name_admin_bar' => _x( 'Certification', 'Add New on Toolbar', 'extel' ),
		'archives' => __( 'Certification Archives', 'extel' ),
		'attributes' => __( 'Certification Attributes', 'extel' ),
		'parent_item_colon' => __( 'Parent Certification:', 'extel' ),
		'all_items' => __( 'All Certifications', 'extel' ),
		'add_new_item' => __( 'Add New Certification', 'extel' ),
		'add_new' => __( 'Add New', 'extel' ),
		'new_item' => __( 'New Certification', 'extel' ),
		'edit_item' => __( 'Edit Certification', 'extel' ),
		'update_item' => __( 'Update Certification', 'extel' ),
		'view_item' => __( 'View Certification', 'extel' ),
		'view_items' => __( 'View Certifications', 'extel' ),
		'search_items' => __( 'Search Certification', 'extel' ),
		'not_found' => __( 'Not found', 'extel' ),
		'not_found_in_trash' => __( 'Not found in Trash', 'extel' ),
		'featured_image' => __( 'Featured Image', 'extel' ),
		'set_featured_image' => __( 'Set featured image', 'extel' ),
		'remove_featured_image' => __( 'Remove featured image', 'extel' ),
		'use_featured_image' => __( 'Use as featured image', 'extel' ),
		'insert_into_item' => __( 'Insert into Certification', 'extel' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Certification', 'extel' ),
		'items_list' => __( 'Certifications list', 'extel' ),
		'items_list_navigation' => __( 'Certifications list navigation', 'extel' ),
		'filter_items_list' => __( 'Filter Certifications list', 'extel' ),
	);
	$args = array(
		'label' => __( 'Certification', 'extel' ),
		'description' => __( 'CPT for Certifications', 'extel' ),
		'labels' => $labels,
		'menu_icon' => 'dashicons-index-card',
		'supports' => array('title', 'editor', 'thumbnail'),
		'taxonomies' => array(),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'menu_position' => 5,
		'show_in_admin_bar' => true,
		'show_in_nav_menus' => true,
		'can_export' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'exclude_from_search' => false,
		'show_in_rest' => true,
		'publicly_queryable' => true,
		'capability_type' => 'post',
	);
	register_post_type( 'certification', $args );

}
add_action( 'init', 'create_certification_cpt', 0 );

// Display Certifications Shortcode
function show_certifications() {

    ob_start(); ?>

    <div class="certifications-list">
        <?php $slidesQuery = new WP_Query( array(
            'post_type' => 'certification'
        ) );
        if ( $slidesQuery->have_posts() ) :
            while ( $slidesQuery->have_posts() ) : $slidesQuery->the_post(); ?>

            <!-- Single Certification -->
            <div class='single-certification certification-<?php the_ID(); ?>'>
            
                <div class="certification-thumbnail">
                    <a href="#!">
                        <img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                    </a>
                </div>
                <div class="certification-info">
                    <h3><a href="#!"><?php the_title(); ?></a></h3>
                    <h4><?php the_field('certification_tagline'); ?></h4>
                </div>

                <?php $check_download_status = get_field('show_download_form', get_the_ID()); ?>

                <ul class="certification-buttons">
                    <li><a href="#!" data-certificate-id="<?php the_ID(); ?>" class='show-readmore-popup'>Read more</a></li>
                    <?php if($check_download_status == 1 ) { ?>
                    <li><a href="#!" data-certificate-id="<?php the_ID(); ?>" class='show-download-popup'>Download</a></li>
                    <?php } ?>
                </ul>
            
            </div>
            <!-- ./Single Certification -->

            <?php endwhile;
        endif; wp_reset_postdata(); ?>
    </div>

    <?php $content = ob_get_clean();
    return $content;
    
}
    
add_shortcode('show_certifications', 'show_certifications');

function cpt_get_adjacent_ID($direction = 'next', $type = 'post', $current) {

    // Get all posts with this custom post type
    $posts = get_posts('posts_per_page=-1&order=DESC&post_type='.$type);

    $postsLength = sizeof($posts)-1;
    $currentIndex = 0;
    $index = 0;
    $result = 0;

    // Iterate all posts in order to find the current one
    foreach($posts as $p){
        if($p->ID == $current) $currentIndex = $index;
        $index++;
    }
    if($direction == 'prev') {
        // If it's 'prev' return the previous one unless it's the first one, in this case return the last. 
        $result = !$currentIndex ? $posts[$postsLength]->ID : $posts[$currentIndex - 1]->ID;
    } else {
        // If it's 'next' return the next one unless it's the last one, in this case return the first. 
        $result = $currentIndex == $postsLength ? $posts[0]->ID : $posts[$currentIndex + 1]->ID;
    }
    return $result;
}

// Certification Popup
function certification_popup() {
    if ( is_page( 'accreditations' ) ) { ?>
        <!-- Popup --> 
        <div class="certificationPopup__cover">
            <div class="certification__popup">
                <div class="certification__popup-content">
                    <!-- DYNAMIC CONTENT HERE -->
                </div>
                <div class="close__popup" id="closePopup"><span><i class="dashicons dashicons-no"></i></span></div>
            </div>
        </div> 
    <?php }
}
add_action('wp_footer', 'certification_popup');

function data_fetch_certifications() {

    $certification_ID = $_POST['getCertificationID'];

    $get_certificate = get_post( $certification_ID ); ?>

    <div class="certification__popup-content--left">
        <div class="cert-image">
            <img src="<?php echo get_the_post_thumbnail_url($get_certificate->ID); ?>" alt="<?php echo $get_certificate->post_title; ?>" />
        </div>
        <ul class="cert-next-prev">
            <?php
                $prev_certificate_id = cpt_get_adjacent_ID('prev', 'certification', $get_certificate->ID);
                $next_certificate_id = cpt_get_adjacent_ID('next', 'certification', $get_certificate->ID);
            ?>
            <li>
                <span class="prev-accreditation accreditation-adjacent" data-certificate-id="<?php echo $prev_certificate_id; ?>">prev. accreditation</span>
            </li>
            <li>
                <span class="next-accreditation accreditation-adjacent" data-certificate-id="<?php echo $next_certificate_id; ?>">next accreditation</span>
            </li>
        </ul>
    </div>
    <div class="certification__popup-content--right">
        <h3 class="cert-title"><?php echo $get_certificate->post_title; ?></h3>
        <span class="cert-subtitle"><?php the_field('certification_tagline', $get_certificate->ID); ?></span>

        <h4>Overview</h4>
        <div class="cert-content">
            <?php echo $get_certificate->post_content; ?>
        </div>

        <?php $check_download_status_popup = get_field('show_download_form', $get_certificate->ID); ?>

        <?php if($check_download_status_popup == 1 ) { ?>
        <div class="cert-download-form">
            <span class="accreditation_attachment_url" style="display: none;"><?php echo get_field('accreditation_attachment', $get_certificate->ID); ?></span>
            <?php echo do_shortcode('[contact-form-7 id="2277" title="Download Certification Form"]'); ?>
        </div>
        <?php } ?>
    </div>
    <?php

    die();

}
add_action('wp_ajax_data_fetch_certifications' , 'data_fetch_certifications');
add_action('wp_ajax_nopriv_data_fetch_certifications','data_fetch_certifications');