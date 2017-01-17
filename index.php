<?php
/**
* The main template file.
*
* This is the most generic template file in a WordPress theme
* and one of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query.
* E.g., it puts together the home page when no home.php file exists.
*
* @link https://codex.wordpress.org/Template_Hierarchy
*
* @package xx
*/

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="container container-extended">
			<div class="index">
				<div class="row row-mission">
					<div class="col-lg-4">
						<p class="mission">
							free-form<br> college radio<br> at UC San Diego
						</p>
						<p class="about">
							Since 1967, KSDT radio has existed to serve students and the greater San Diego community. Broadcasting over the internet 24/7, KSDT DJs curate an eclectic sound, featuring underrepresented genres and emerging artists. KSDT regularly organizes live performances, and provides a fully equipped recording and podcasting studio free of charge to students.
						</p>
					</div>
					<div class="col-lg-8">
						<div class="slick-slider">
							<div>
								<iframe class="hero-video" src="https://www.youtube.com/embed/aCVORgDQaCA" frameborder="0" allowfullscreen></iframe>
							</div>
							<div>
								<iframe class="hero-video" src="https://www.youtube.com/embed/AtIw9GMMr0g" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
						<script>
						jQuery(document).ready(function(){
							jQuery('.slick-slider').slick({
								dots: true,
								slidesToShow: 1,
								arrows: false
							});
						});
						</script>
					</div>
				</div>

				<!-- This is where row and col were at -->

				<?php

				wp_reset_query();

				$mostRecentFeaturedId;

				$featuredQuery = [
					'category_name' => 'featured',
					'post_type' => array(
						'post',
						'weeklypicks',
						'concertreviews',
						'albumreviews'
					),
					'posts_per_page' => 1
				];

				$query1 = new WP_Query( $featuredQuery );

				if ( $query1->have_posts() ):
					while ( $query1->have_posts() ):	$query1->the_post();  $mostRecentFeaturedId = get_the_ID(); ?>


					<div class ="row row-index">
						<div class="post-header">

							<span class="post-featured">Featured</span>
							<div class="post-line" style="width: 150px;"></div>
						</div>
						<a class="cya-styles index-post-link" href="<?php the_permalink(); ?>">
							<!-- ********************* -->

							<!-- new row and col -->
							<article class="index-post" data-url="<?php the_permalink(); ?>">
								<div class="col-lg-4 col-image">
									<div class="post-image">
										<img class = "image-extended" src = "<?php the_post_thumbnail_url() ?>" >
									</div>
								</div>

								<div class="col-lg-8 post-text">
									<div class="meta">
										<span class="post-title"><?php the_title(); ?> <span>/ <?php echo get_post_type()?></span> </span> <br>
										<span class="entry-date"><?php echo get_the_date(); ?> / by <?php the_author(); ?>  </span>

									</div>
									<p class="snippet">
										<?php the_excerpt(); ?>
									</p>
								</div>
							</article>
							<!-- new end row and col -->

						</a>


					</div>

					<div class ="row row-index row-latest">
					<div class="post-header">
						<span class="post-featured">Latest</span>
						<div class="post-line" style="width: 100px;"></div>
					</div>
				</div>

				<?php   endwhile;
			endif;
			wp_reset_postdata();

			$regularQuery = [
				'posts_per_page' => 10,
				'post_type' => array(
					'post',
					'weeklypicks',
					'concertreviews',
					'albumreviews'
				)
			];

			$query = new WP_Query( $regularQuery );

			if ( $query->have_posts() ) :
				/* Start the Loop */

				while ( $query->have_posts() ) : $query->the_post();
				if (get_the_id() == $mostRecentFeaturedId) continue;
				?>
				<div class ="row row-index">
					<a class="cya-styles index-post-link" href="<?php the_permalink(); ?>">
						<!-- ********************* -->

						<article class="index-post" data-url="<?php the_permalink(); ?>">


							<!-- if(get_the_category() && get_the_time() ) -->
							<?php if (get_the_time() ): ?>
								<div class="col-lg-4 col-image">
									<div class="post-image">
									<img class = "card-img-top image-extended" src = "<?php the_post_thumbnail_url() ?>" >
								</div>
								</div>
								<div class="col-lg-8 post-text">
									<div class="meta">
										<span class="post-title"><?php the_title(); ?> <span>/ <?php echo get_post_type()?></span></span>
										<br>
										<span class="entry-date"><?php echo get_the_date(); ?> / by <?php the_author(); ?></span>

									</div>
								<?php endif; ?>
								<p class="snippet">
									<?php the_excerpt(); ?>
								</p>

							</div>

						</article>
					</a>

				</div>

				<?php

			endwhile;
		endif;
		?>


		<br> <br> <br>
		<!-- Divider -->
		<!-- What's new section -->

	</div>

</div>
</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
