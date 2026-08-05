<?php
/**
 * Comments template.
 *
 * @package WPRaffle_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="wpr-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="wpr-comments-title">
			<?php
			$count = get_comments_number();
			/* translators: %s: comment count. */
			printf( esc_html( _n( '%s Comment', '%s Comments', $count, 'wpraffle-theme' ) ), esc_html( number_format_i18n( $count ) ) );
			?>
		</h2>

		<ol class="wpr-comments-list list-unstyled">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 56,
			) );
			?>
		</ol>

		<?php the_comments_pagination( array( 'prev_text' => __( 'Previous', 'wpraffle-theme' ), 'next_text' => __( 'Next', 'wpraffle-theme' ) ) ); ?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'class_form'    => 'wpr-comment-form',
		'class_submit'  => 'btn btn-accent',
		'title_reply'   => __( 'Leave a Comment', 'wpraffle-theme' ),
	) );
	?>
</div>
