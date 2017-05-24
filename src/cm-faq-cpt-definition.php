<?php
/**
 * FAQs Custom Post Type.
 *
 * @package     CarmeMias\FAQsFunctionality\src
 * @author      carmemias
 * @copyright   2017 Carme Mias Studio
 * @license     GPL-2.0+
 *
 */

namespace CarmeMias\FAQsFunctionality\src;

add_action( 'init', __NAMESPACE__ . '\cm_faq_cpt' );
/**
 * Register CPT `faq_faq`
 * See http://web-profile.net/wordpress/docs/custom-post-types/
 * See https://codex.wordpress.org/Function_Reference/register_post_type 
 */
function cm_faq_cpt() { //see https://codex.wordpress.org/Function_Reference/post_type_supports
	$features = get_all_post_type_features('post', array( #list of excluded features. See lines 59 and 88
		'excerpt',
		'trackbacks',
		'custom-fields',
		'comments',
		'revisions',
		'author',
		'thumbnail',
		'post-formats'
	));
	
	register_post_type( 'cm_faq', array(
		'labels'  => array(
			'name' => _x( 'FAQs', 'faqs CPT general name' , 'faqs-functionality'),
			'singular_name' => _x( 'FAQ', 'faqs CPT singular name' , 'faqs-functionality'),
			'all_items' => __('All FAQs'),
 		    'add_new_item' => __('Add New Question', 'faqs-functionality'),
 		    'edit_item' => __('Edit Question', 'faqs-functionality'),
 		    'new_item' => __('New Question', 'faqs-functionality'),
 		    'view_item' => __('View Question', 'faqs-functionality'),
 		    'view_items' => __('View Questions', 'faqs-functionality'),
 		    'search_items' => __('Search Question', 'faqs-functionality'),
 		    'not_found' => __( 'No questions found.', 'faqs-functionality' ),
 		   	'not_found_in_trash' => __( 'No questions found in Trash.', 'faqs-functionality' )
		),
		'public' => true,
		'show_ui' => true,
		'has_archive' => true, #this means it'll have an "index/loop" page
		'rewrite' => array(
			'slug' => _x( 'faqs', 'CPT permalink slug', 'cm_faq'),
			'with_front' => false,
		),
		'menu_icon'   => 'dashicons-megaphone',
		'menu_position' => 20,
		'supports' => $features, #see line 29
		'taxonomies' => array('faq-category'),
		//'register_meta_box_cb' => __NAMESPACE__.'\cm_faqs_add_meta_boxes' //see line 95
	));
	
	register_taxonomy('faq-category', 'cm_faq', array(
		'hierarchical' => true,
		'show_in_nav_menus' => false,
		'labels' => array(
			'name' => __('FAQ categories'),
			'singular_name' => __('FAQ category'),
			'all_items' => __('All FAQ categories'),
			'edit_item' => __('Edit FAQ category'),
			'view_item' => __('View FAQ category'),
			'update_item' => __('Update FAQ category'),
			'add_new_item' => __('Add new FAQ category'),
			'new_item_name' => __('New FAQ category'),
			'popular_items' => __('Most used FAQ categories'),
			'separate_items_with_commas' => __('Separate FAQ categories with commas'),
			'add_or_remove_items' => __('Add or remove FAQ categories'),
			'choose_from_most_used' => __('Choose from most used FAQ categories'),
		)
	) ); //categories will be set as "children classes" and "teacher training"
}

/**
* Get all the post type features for the given post type
* @param string $post_type - given post type
* @param array $exclude_features - array of features to exclude
* @return array
**/
function get_all_post_type_features($post_type = 'post', $excluded_features = array()){
	#see https://knowthecode.io/labs/custom-post-type-basics#configure-features
	$raw_features = get_all_post_type_supports($post_type);
	
	if(!$excluded_features){return array_keys($raw_features);}
	
	$included_features = array();
	foreach($raw_features as $key => $value){
		if(!in_array($key, $excluded_features)){
			$included_features[]=$key;
		}
	}
	return $included_features;
}

add_filter( 'post_updated_messages',  __NAMESPACE__ . '\cm_faq_updated_messages' );
/**
 * FAQs update messages.
 *
 * @param array $messages Existing post update messages.
 *
 * @return array Amended post update messages with new CPT update messages.
 */
function cm_faq_updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['cm_faq'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'FAQs updated.', 'faqs-functionality' ),
		2  => __( 'Custom field updated.', 'faqs-functionality' ),
		3  => __( 'Custom field deleted.', 'faqs-functionality' ),
		4  => __( 'FAQs updated.', 'faqs-functionality' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'FAQ restored to revision from %s', 'faqs-functionality' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'FAQ published.', 'faqs-functionality' ),
		7  => __( 'FAQ saved.', 'faqs-functionality' ),
		8  => __( 'FAQ submitted.', 'faqs-functionality' ),
		9  => sprintf(
			__( 'FAQ scheduled for: <strong>%1$s</strong>.', 'faqs-functionality' ),
			// translators: Publish box date format, see http://php.net/date
			date_i18n( __( 'M j, Y @ G:i', 'faqs-functionality' ), strtotime( $post->post_date ) )
		),
		10 => __( 'FAQ draft updated.', 'faqs-functionality' )
	);

	if ( $post_type_object->publicly_queryable && 'cm_faq' === $post_type ) {
		$permalink = get_permalink( $post->ID );

		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View FAQ', 'faqs-functionality' ) );
		$messages[ $post_type ][1] .= $view_link;
		$messages[ $post_type ][6] .= $view_link;
		$messages[ $post_type ][9] .= $view_link;

		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview FAQ', 'faqs-functionality' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}


add_filter( 'bulk_post_updated_messages', __NAMESPACE__ . '\cm_faq_bulk_updated_messages', 10, 2 );
/**
 * FAQs bulk update messages.
 * See https://themeforest.net/forums/thread/custom-post-type-change-delete-message/114401
 * @param array $messages Existing post bulk update messages.
 *
 * @return array Amended post update messages with new CPT update messages.
 */
function cm_faq_bulk_updated_messages( $bulk_messages, $bulk_counts ) {

    $bulk_messages['cm_faq'] = array(
        'updated'   => __( '%s FAQ updated.', '%s FAQs updated.', $bulk_counts['updated'] ),
        'locked'    => __( '%s FAQ not updated, somebody is editing it.', '%s FAQs not updated, somebody is editing them.', $bulk_counts['locked'] ),
        'deleted'   => __( '%s FAQ permanently deleted.', '%s FAQs permanently deleted.', $bulk_counts['deleted'] ),
        'trashed'   => __( '%s FAQ moved to the Bin.', '%s FAQs moved to the Bin.', $bulk_counts['trashed'] ),
        'untrashed' => __( '%s FAQ restored from the Bin.', '%s FAQs restored from the Bin.', $bulk_counts['untrashed'] ),
    );

    return $bulk_messages;

}

