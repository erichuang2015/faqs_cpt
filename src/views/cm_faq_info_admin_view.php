<?php
namespace CarmeMias\FAQsFunctionality\src\views;

	//[faqs category="category-slug|category name"] category attrib value not case sensitive
?>

<table class="form-table"> <!-- TODO make translatable -->
	<tr>
		<td><?php echo __('To add a FAQs listing, paste:', 'faqs-functionality'); ?></td>
	</tr>
	<tr style="border-bottom: 1px solid #eee;">
		<td>
			<blockcode><strong>[faqs category="</strong><em><?php echo __('category name', 'faqs-functionality'); ?></em><strong>"]</strong></blockcode>
		</td><!-- staff_members_cpt order -->
	</tr>
	<tr><td><?php echo __('You can leave out the category name if you want all FAQs grouped by category.', 'faqs-functionality'); ?></td></tr>
</table>
