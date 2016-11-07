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
					<div class="row">
						<div class="col-lg-4">
							<p class="mission">
								free form<br> student radio<br> at UC San Diego.
							</p>
							<p class="about">
								Since 1967, KSDT Radio has existed to serve students and the greater San Diego community. Broadcasting over the internet 24/7, KSDT DJs curate an eclectic sound, featuring underrepresented genres and emerging artists. KSDT regularly organizes live performances, and provides a fully equipped recording and podcasting studio free of charge to students.
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
					<div class="row">
						<div class="col-lg-5">
							<p class="mission">
								what's new
							</p>
							<ul class="index-post-list">
								<?php

									wp_reset_query();

									$mostRecentFeaturedId;

									$featuredQuery = [
										'category_name' => 'featured',
										'posts_per_page' => 1
									];

									$query1 = new WP_Query( $featuredQuery );

									if ( $query1->have_posts() ):
										while ( $query1->have_posts() ):	$query1->the_post();  $mostRecentFeaturedId = get_the_ID(); ?>

										<a class="cya-styles index-post-link" href="<?php the_permalink(); ?>">
											<li class="index-post-entry featured">
												<article class="index-post" data-url="<?php the_permalink(); ?>">
													<h1><?php the_title(); ?></h1>
													<div class="meta">
														<span class="category"><?php echo get_the_category()[0]->slug; ?></span>
														<span class="date"><?php echo human_time_diff(get_the_time('U')) . ' ago'; ?></span>
													</div>
													<p class="snippet">
														<?php the_excerpt(); ?>
													</p>
												</article>
											</li>
										</a>
										<hr>

								<?php   endwhile;
									endif;
									wp_reset_postdata();

									$regularQuery = [
										'posts_per_page' => 10
									];

									$query = new WP_Query( $regularQuery );

									if ( $query->have_posts() ) :
										/* Start the Loop */

										while ( $query->have_posts() ) : $query->the_post();
											if (get_the_id() == $mostRecentFeaturedId) continue;
											?>
												<a class="cya-styles index-post-link" href="<?php the_permalink(); ?>">
													<li class="index-post-entry">
														<article class="index-post" data-url="<?php the_permalink(); ?>">
															<h1><?php the_title(); ?></h1>
															<div class="meta">
																<span class="category"><?php echo get_the_category()[0]->slug; ?></span>
																<span class="date"><?php echo human_time_diff(get_the_time('U')) . ' ago'; ?></span>
															</div>
															<p class="snippet">
																<?php the_excerpt(); ?>
															</p>
														</article>
													</li>
												</a>
												<hr>
											<?php

										endwhile;
									endif;
								?>
							</ul>
						</div>
						<div class="col-lg-7">
							<a href="https://www.facebook.com/events/180379865743803/">
								<img class="poster" src="https://ksdt.ucsd.edu/wp-content/uploads/2016/11/14615661_10154213208193264_6854662183800466507_o.jpg" alt="">
							</a>
							<a href="https://www.facebook.com/events/784703248334388/">
								<img class="poster" src="https://ksdt.ucsd.edu/wp-content/uploads/2016/11/the_most_suggestive_sounding_festival.png" alt="">
							</a>
						</div>
					</div>
				</div>

			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
