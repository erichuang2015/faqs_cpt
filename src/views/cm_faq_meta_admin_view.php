<?php
namespace CarmeMias\FAQsFunctionality\src\views;

	//$custom = get_post_custom($post->ID);
	// Get the data if its already been entered
 	//SEE SP-BOOK-DEFINITION.PHP LINE66  $cm_class_age_range = get_post_meta($post->ID, '_cm_class_age_range', true);
	
	$cm_faq_order = get_post_meta($post->ID, '_cm_faq_order', true);
	$current_num_faqs = wp_count_posts('cm_faq')->publish;
	
?>

<table class="form-table">
	<tr>
		<td>Choose in which position the question will appear:</br><em>Lowest number shows highest.</em></td>
	</tr>
	<tr style="border-bottom: 1px solid #eee;">
		<td>
			<label>FAQ Order:</label>
			<!-- input type="number" name="_cm_class_age_from" class="" value="< ?php echo $cm_class_age_from; ?>" --> 
			 <select name="_cm_faq_order" class="" value="<?php echo $cm_faq_order; ?>">
			  <option value="not set">Select order...</option>
			  <option value="hidden">Don't show</option>
			  <option value="10000" <?php if($cm_faq_order && $cm_faq_order == '10000') { ?> selected <?php } //end if ?>>Bottom of the screen</option>
          	<?php for ( $i=1; $i <= $current_num_faqs; $i++ ) { ?>
          		<option value="<?php echo $i; ?>" <?php if($cm_faq_order && $cm_faq_order == $i) { ?> selected <?php } //end if ?> > <?php echo $i; ?> </option>
              <?php } //end for loop ?>
			</select>
		</td><!-- Age from... -->
	</tr>
</table>