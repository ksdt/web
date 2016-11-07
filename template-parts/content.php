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

			<div class="featured-lead" style="background-image: url(<?php the_post_thumbnail_url('full'); ?>);"></div>
			<div class="meta" style="margin-top: -10px;">
				<h1 class="entry-title" style = "color: #404040; font-size: 2.3rem; font-weight: 100; margin-bottom: -11px;"><?php the_title(); ?></h1>
				<span style="color: #404040; font-weight: 100; font-size: 1.2rem"><?php the_date('l, F jS Y'); ?></span>
			</div>
		<?php
		elseif (is_single()):
			the_title( '<h1 class="entry-title">', '</h1>' );
			the_date('l, F jS Y');
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
