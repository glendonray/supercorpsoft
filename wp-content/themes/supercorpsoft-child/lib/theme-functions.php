<?php
/**
 * Any support functions unique to this site should be added here, if not in a plugin.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Basetheme
 * @since 1.0
 * @version 1.0
 */


/**
 * Reusable Blocks accessible in backend
 *
 * @link https://www.billerickson.net/reusable-blocks-accessible-in-wordpress-admin-area
 */
function ad_reusable_blocks_admin_menu() {
	add_menu_page( 'Patterns', 'Patterns', 'edit_posts', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22 );
}
add_action( 'admin_menu', 'ad_reusable_blocks_admin_menu' );

/**
 * Custom Query Args for Kadence Query Loops
 * @link https://www.kadencewp.com/help-center/docs/kadence-blocks/custom-queries-for-advanced-query-loop-block/
 */
add_filter( 'kadence_blocks_pro_query_loop_query_vars', function( $query, $ql_query_meta, $ql_id ) {
	// Single Missionary - From the Field (PID: 1032),  Program Partnerships (PID: 1037)
	if ( $ql_id == 1032 || $ql_id == 1037 ) :
	   $query['meta_query'] = array(
		  array(
			 'key' => 'associated_missionary',
			 'value' => get_the_id(),
			 'compare' => 'LIKE',
		  )
	   );
	endif;

	// Single Program - Meet the Local Team (PID: 4651), Single Special Project - About the Missionary (PID: 4761)
	if ( $ql_id == 4651 || $ql_id == 4761) :
		$query['post__in'] = get_field('associated_missionary',get_the_id()); 
		endif;
	return $query;
 }, 10, 3 );


 add_filter('the_content', 'wphelp_remove_shortcodes_divi');
function wphelp_remove_shortcodes_divi( $content ) {
$content = preg_replace('/\[\/?et_pb.*?\]/', '', $content);
return $content;
}




comment_form(
	array(
		'logged_in_as'       => null,
		'title_reply'        => esc_html__( 'Subnit a reply', 'atmdst-child' ),
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>',
	)
);