<?php
/**
 * Shortcode functionality for the FAQs Custom Post Type.
 *
 * @package     CarmeMias\FAQsFunctionality\src
 * @author      carmemias
 * @copyright   2017 Carme Mias Studio
 * @license     GPL-2.0+
 *
 */

namespace CarmeMias\FAQsFunctionality\src;

//[faqs category="category-slug"]
function faqs_shortcode_handler( $atts ){
	//TODO we must add the stylesheet and js for the view and the accordion
	
	//the default value for category
	$a = shortcode_atts( array(
	        'category' => ''
	    ), $atts );
	
	if( ('' != $a['category']) ){
		//the category attribute has been set
		//does this category exist?
		$category_exists = term_exists($a['category'],'faq-category');
		
		//if it doesn't return error message
		if((0==$category_exists)||(null==$category_exists)){
			return '<p>The selected category does not exist.</p>';
		} 
		
		//if it does, find all FAQs of that category
 	    $cm_faq_args = array ('post_type' => 'cm_faq',
	 						 'post_status' => 'publish',
							 'faq-category' => $a['category'],
							 'order' => 'ASC',
							 'orderby' => 'meta_value_num',
							 'meta_key' => '_cm_faq_order',
							 'posts_per_page' => -1);	
		
		$questions = get_posts( $cm_faq_args ); //returns an array
		
	} else {
		//no arguments have been set by the Editor, so all FAQs will be listed grouped by category and in the order specified.
 	    $cm_faq_args = array ('post_type' => 'cm_faq',
	 						 'post_status' => 'publish',
							 'order' => 'ASC',
							 'orderby' => 'meta_value_num',
							 'meta_key' => '_cm_faq_order',
							 'posts_per_page' => -1);	
		
		$questions = get_posts( $cm_faq_args ); //returns an array
		
	} 
	
	//now we have the data, we can build the view
	?>
	
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	
	<?php foreach ( $questions as $question ) : setup_postdata( $question ); 
			$question_id = $question->ID;
			
			//TODO we must not display the FAQs set to order = "not show"
			//TODO where do the FAQs with order not set go? hidden or to the bottom? 
		?>
	
	  <article id="post-<?php echo $question_id; ?>" <?php post_class($question_id); //TODO post_class here gives us the page classes rather than the FAQ classes ?>>  

		<header class="entry-header" role="tab" id="heading-<?php echo $question_id; ?>">
			<h3 class="entry-title">
				<a role="button" class="collapsed" data-parent="#accordion" href="#collapse-<?php echo $question_id; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $question_id; ?>">
					<?php echo $question->post_title;?>
				    <span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true" role="img"></span>
				</a>
			</h3>
		</header><!-- .entry-header -->

		<div class="entry-content collapse" role="tabpanel" aria-labelledby="heading-<?php echo $question_id; ?>" id="collapse-<?php echo $question_id; ?>">
			<?php the_content();?>
		</div --><!-- .entry-content -->

	  </article><!-- #post-## -->
	
	<?php endforeach; ?>
	
		</div><!-- accordion -->
		
	<?php wp_reset_postdata();
}

add_shortcode( 'faqs', __NAMESPACE__ . '\faqs_shortcode_handler');
?>