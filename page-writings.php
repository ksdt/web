<?php
/**
 * The template for displaying show pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xx
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container" style = "">
			    <article class = "page writings" href="/programming/">
			        <div class = "entry-content">

						<!-- Blog -->
			            <div class = "row">
										<!--
			                <div class = "col-lg-2 col-md-3 col-title" href = "/writings/blog">
			                    <div class = "writings-container">
			                        <div class = "writings-text">Blog</div>
			                    </div>
			                </div>
										-->
										<a  class="entry-link" href = "/writings/blog">
										<div class="col-lg-12" href = "/writings/blog">
										<div class ="entry-header">
											<span class="post-featured">Blog Posts</span>
											<div class="post-line" style="width: 174px;"></div>
										</div>
									</div>
								</a>

							<?php

							$args = array(
							    'post_type'=> 'post',
							    'posts_per_page' => 4,
							    );

							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-6 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<div class = "image-div">
											<img class = " image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<div class = "card-block card-block-extended">
											<span class = "card-title card-title-extended"><?php the_title() ?></span>
											<br>
											<span class="entry-date"><?php echo get_the_date(); ?> <span>/ by <?php the_author(); ?> </span> </span>
										</div>
									</div>
				                </div>
							<?php endwhile; endif; ?>

			            </div>

			            <!-- Album Reviews -->
			            <div class = "row">



							<?php
							$args = array(
							    'post_type'=> 'albumreviews',
							    'posts_per_page' => 4,
							    );

							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : ?>
							<a  class="entry-link" href = "/writings/albumreviews">
							<div class="col-lg-12">
								<div class ="entry-header">
									<span class="post-featured">Album Reviews</span>
									<div class="post-line" style="width: 255px;"></div>
								</div>
						</div>
					</a>

							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-6 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<?php if(has_post_thumbnail()) : ?>
										<div class = "image-div">
											<img class = "image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<?php else:?>
										<div class = "no-image"></div>
										<?php endif;?>
										<div class = "card-block card-block-extended">
											<span class = "card-title card-title-extended"><?php the_title() ?></span>
											<br>
											<span class="entry-date"><?php echo get_the_date(); ?> <span>/ by <?php the_author(); ?> </span> </span>

											<!--
											<p><span class = "tag tag-default"><?php $key="rating"; echo get_post_meta($post->ID, $key, true); ?></span>
											<span class = "tag tag-info"><?php $key="genre"; echo get_post_meta($post->ID, $key, true); ?></span>
										</p> -->


										</div>
									</div>
				                </div>
							<?php endwhile; endif; ?>

			            </div>


						<!-- Concert Reviews -->
			            <div class = "row">
										<a  class="entry-link" href = "/writings/concertreviews">
										<div class="col-lg-12">
										<div class ="entry-header">
											<span class="post-featured">Concert Reviews</span>
											<div class="post-line" style="width: 283px;"></div>
										</div>
									</div>
								</a>

							<?php

							$args = array(
							    'post_type'=> 'concertreviews',
							    'posts_per_page' => 4,
							    );

							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : while ( $the_query->have_posts()) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-6 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<div class = "image-div">
											<img class = "image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<div class = "card-block card-block-extended">
											<span class = "card-title card-title-extended"><?php the_title() ?></span>
											<br>
											<span class="entry-date"><?php echo get_the_date(); ?> <span>/ by <?php the_author(); ?> </span> </span>

											<!--
											<p><span class = "tag tag-default"><?php $key="location"; echo get_post_meta($post->ID, $key, true); ?></span>
											<span class = "tag tag-info"><?php $key="genre"; echo get_post_meta($post->ID, $key, true); ?></span>
										</p> -->

										</div>
									</div>
				                </div>
							<?php endwhile; endif; ?>

			            </div>

						<!-- Weekly picks -->
			            <div class = "row">
										<a  class="entry-link" href = "/writings/weeklypicks">
										<div class="col-lg-12">
										<div class ="entry-header">
											<span class="post-featured">Weekly Picks</span>
											<div class="post-line" style="width: 217px;"></div>
										</div>
									</div>
								</a>

							<?php

							$args = array(
							    'post_type'=> 'weeklypicks',
							    'posts_per_page' => 4,
							    );

							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-6 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<div class = "image-div">
											<img class = "image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<div class = "card-block card-block-extended">

											<span class = "card-title card-title-extended"><?php the_title() ?></span>
											<br>
											<span class="entry-date"><?php echo get_the_date(); ?> <span>/ by <?php the_author(); ?> </span> </span>

											<!-- <p><span class="tag tag-default">Artists</span> : <?php $key="artists"; echo get_post_meta($post->ID, $key, true); ?></p> -->
										</div>
									</div>
				                </div>
							<?php endwhile; endif; ?>

			            </div>

			        </div>
			    </article>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
get_sidebar();
get_footer();
