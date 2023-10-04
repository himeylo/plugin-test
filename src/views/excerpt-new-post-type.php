<?php
/**
 * Custom post type excerpt, typically shown on archive pages and for search results.
 * phpcs:ignorefile WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
 *
 * @package WordPress_Plugin_Name
 */

// $props is provided to this file by the `render()` function.
?><table class="new-post-type fields" width="100%">
	<thead>
		<tr>
			<td colspan="3">Advanced Custom Fields values</td>
		</tr>
		<tr>
			<td>Field 1</td>
			<td>Field 2</td>
			<td>Field 3</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo (get_field( 'post_field_1' ) ? get_field( 'post_field_1' ) : '(empty)'); ?></td>
			<td><?php echo (get_field( 'post_field_2' ) ? get_field( 'post_field_2' ) : '(empty)'); ?></td>
			<td><?php echo (get_field( 'post_field_3' ) ? get_field( 'post_field_3' ) : '(empty)'); ?></td>
		</tr>
	</tbody>
</table>
<?= $props->excerpt ?>
