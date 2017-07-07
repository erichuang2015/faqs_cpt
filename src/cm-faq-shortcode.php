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

//See https://wordpress.stackexchange.com/questions/165754/enqueue-scripts-styles-when-shortcode-is-present
/*
* Enqueue javascript and stylesheet files used by the shortcode view
*/
function cm_faqs_shortcode_enqueue_scripts(){
	wp_register_style('faqs_shortcode_style', FAQ_FUNCTIONALITY_URL . '/src/assets/css/faqs_shortcode_style.css');
	wp_register_script('faqs_shortcode_script', FAQ_FUNCTIONALITY_URL . '/src/assets/js/faqs_shortcode_script.js');
	
	//OPTIMIZE this currently loads the script and style on all pages. It could be improved by loading themonly if a shortcut is present
	wp_enqueue_style( 'faqs_shortcode_style' );
	wp_enqueue_script( 'faqs_shortcode_script' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\cm_faqs_shortcode_enqueue_scripts');	


//[faqs category="category-slug|category name"] category attrib value not case sensitive
function cm_faqs_shortcode_handler( $atts ){
	
	//the default value for category
	$a = shortcode_atts( array(
	        'category' => ''
	    ), $atts );
	
	$results_array = [];
	$faq_categories = [];
	
	//find category/ies
	if( ('' != $a['category']) ){
		
		//the category attribute has been set
		//does this category exist?
		$category_ID = term_exists($a['category'],'faq-category');
		if( is_array($category_ID) ){ $category_ID = array_shift($category_ID);}
		
		//if the category doesn't exist, return error message
		if( ( 0==$category_ID ) || ( null==$category_ID ) ) {
			
			return '<p>The selected category does not exist.</p>';
			
		} 
		
		//finds the category object, returns an array with a single object
		$faq_categories = get_terms( array( 'taxonomy' => 'faq-category', 'include' => $category_ID));
		
	} else {
		
		//no arguments have been set by the Editor, so all FAQs will be listed grouped by category and in the order specified.
 	    $faq_categories = get_terms( array( 'taxonomy' => 'faq-category', 'hide_empty' => false ) );
		
	} 
	
	
	if ( ! empty( $faq_categories ) && ! is_wp_error( $faq_categories ) ){
		foreach ( $faq_categories as $category_obj ) {
			$single_result_obj=[];
				
			$cm_faq_args = array ('post_type' => 'cm_faq',
 						 	'post_status' => 'publish',
							'faq-category' => $category_obj->slug,
							 'order' => 'ASC',
							 'orderby' => 'meta_value_num',
							 'meta_key' => '_cm_faq_order',
							 'posts_per_page' => -1);	
	
			$faqs = get_posts( $cm_faq_args ); //returns an array
			
			//create a $result array combining the faq-category and the corresponding faqs
			$single_result_obj = [ 'category' => $category_obj,'questions' => $faqs ];
	
			//$results_array = [['category' => $category_obj,'questions' => $questions]]
			array_push($results_array,$single_result_obj);
		}//end foreach
	}//end if !empty...
	
	//now we have the data, we can build the view
	
	foreach ( $results_array as $single_result ) {
		$category_name = $single_result['category']->name;
		$questions = $single_result['questions'];
	?>
	
		<h3 class="category-title"><?php echo $category_name; ?></h3>
	
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	
			<?php foreach ( $questions as $question ) : setup_postdata( $GLOBALS['post'] =& $question ); 
			
	    		$question_meta = get_post_meta( get_the_ID(), '_cm_faq_order', true );

	    		if(( $question_meta != 'hidden' ) && ( $question_meta != 'not set' )){
			?>
	
	  	  		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>  

					<header class="entry-header" role="tab" id="heading-<?php the_ID(); ?>">
						<?php 
					the_title( '<h4 class="entry-title"><a role="button" class="collapsed" data-parent="#accordion" href="#collapse-'. get_the_ID() .'" aria-expanded="false" aria-controls="collapse-'. get_the_ID() .'">', '<span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true" role="img"></span></a></h4>' );
						?>
					</header><!-- .entry-header -->

					<div class="entry-content collapse" role="tabpanel" aria-labelledby="heading-<?php the_ID(); ?>" id="collapse-<?php the_ID(); ?>">
			<?php the_content();?>
					</div --><!-- .entry-content -->

	  		  </article><!-- #post-## -->
	
	<?php 
			} //end if $question_meta
	
		endforeach; //foreach questions array within single_result ?>
	
		</div><!-- accordion -->
	
		<?php } //foreach $results_array ?>
		
	<?php wp_reset_postdata();
  }

add_shortcode( 'faqs', __NAMESPACE__ . '\cm_faqs_shortcode_handler');

?>