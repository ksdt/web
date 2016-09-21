<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xx
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_single() && has_post_thumbnail() ) :
			?>
			<div class="featured-lead" style="background-image: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(<?php the_post_thumbnail_url('full'); ?>)">
				<div class="meta">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					<span><?php the_date('l, F jS Y'); ?></span>
				</div>
			</div>
		<?php
		elseif (is_single()):
			the_title( '<h1 class="entry-title">', '</h1>' );
		else:
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
	</header><!-- .entry-header -->

	<div class="entry-content" style = " margin: auto; margin-top: 30px;">
		<?php
			the_content( sprintf(
				/* translators: %s: Name of current post. */
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'xx' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );
		?>
		<script src="//platform.instagram.com/en_US/embeds.js"></script>
		<script>
			if ( typeof window.instgrm !== 'undefined' ) {
			    window.instgrm.Embeds.process();
			}	
		</script>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
