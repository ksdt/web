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

		<div class="meta">
			<a href="/writings">back to posts</a>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<span class="entry-date"><?php the_date('l, F jS Y'); ?></span>
			| by <span class="entry-author"><?php the_author(); ?></span>
			<br>
						<img class="entry-image" src="<?php if (has_post_thumbnail()) echo the_post_thumbnail_url() ?>">
		</div>


	</header><!-- .entry-header -->


	<div class="entry-content" style = "">
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
