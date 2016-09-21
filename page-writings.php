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
			<div class="container" style = "background-color: white">
			    <article class = "page writings" href="/programming/">
			        <div class = "entry-content">
			        	<h1>Latest Events</h1>
			        
						<!-- Blog -->
			            <div class = "row">
			                <div class = "col-lg-2 col-md-3 col-title" href = "/writings/blog">
			                    <div class = "writings-container">
			                        <div class = "writings-text">Blog</div>
			                    </div>
			                </div>
			                
							<?php 
							
							$args = array(
							    'post_type'=> 'post',
							    'posts_per_page' => 3,
							    );              
							
							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-3 col-sm-4 col-xs-4 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<div class = "image-div">
											<img class = "card-img-top image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<div class = "card-block card-block-extended">
											<h5 class = "card-title card-title-extended"><?php the_title() ?></h5>
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
							    'posts_per_page' => 3,
							    );              
							
							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : ?>
			                <div class = "col-lg-2 col-md-3 col-title" href = "/writings/albumreviews/">
			                    <div class = "writings-container">
			                        <div class = "writings-text">Album Reviews</div>
			                    </div>
			                </div>							
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-3 col-sm-4 col-xs-4 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<?php if(has_post_thumbnail()) : ?>
										<div class = "image-div">
											<img class = "card-img-top image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<?php else:?>
										<div class = "no-image"></div>
										<?php endif;?>
										<div class = "card-block card-block-extended">
											<h5 class = "card-title card-title-extended"><?php the_title() ?></h5>
											<p><span class = "tag tag-default"><?php $key="rating"; echo get_post_meta($post->ID, $key, true); ?></span>
											<span class = "tag tag-info"><?php $key="genre"; echo get_post_meta($post->ID, $key, true); ?></span>
											</p>
										</div>
									</div>
				                </div>
							<?php endwhile; endif; ?>	          
			               
			            </div>
			        	
			        	
						<!-- Concert Reviews -->
			            <div class = "row">
			                <div class = "col-lg-2 col-md-3 col-title" href = "/writings/concertreviews/">
			                    <div class = "writings-container">
			                        <div class = "writings-text">Concert Reviews</div>
			                    </div>
			                </div>
			                
							<?php 
							
							$args = array(
							    'post_type'=> 'concertreviews',
							    'posts_per_page' => 3,
							    );              
							
							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : while ( $the_query->have_posts()) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-3 col-sm-4 col-xs-4 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<div class = "image-div">
											<img class = "card-img-top image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<div class = "card-block card-block-extended">
											<h5 class = "card-title card-title-extended"><?php the_title() ?></h5>
											<p><span class = "tag tag-default"><?php $key="location"; echo get_post_meta($post->ID, $key, true); ?></span>
											<span class = "tag tag-info"><?php $key="genre"; echo get_post_meta($post->ID, $key, true); ?></span>
											</p>
										
										</div>
									</div>
				                </div>
							<?php endwhile; endif; ?>	          
			               
			            </div>
						
						<!-- Weekly picks -->
			            <div class = "row">
			                <div class = "col-lg-2 col-md-3 col-title" href = "/writings/weeklypicks/">
			                    <div class = "writings-container">
			                        <div class = "writings-text">Weekly Picks</div>
			                    </div>
			                </div>
			                
							<?php 
							
							$args = array(
							    'post_type'=> 'weeklypicks',
							    'posts_per_page' => 3,
							    );              
							
							$the_query = new WP_Query( $args );
							$count = 0;
							if($the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				                <div class = "col-lg-3 col-md-3 col-sm-4 col-xs-4 col-extended" href = "<?php the_permalink(); ?>">
									<div class = "card">
										<div class = "image-div">
											<img class = "card-img-top image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
										</div>
										<div class = "card-block card-block-extended">
											<h5 class = "card-title card-title-extended"><?php the_title() ?></h5>
											<p><span class="tag tag-default">Artists</span> : <?php $key="artists"; echo get_post_meta($post->ID, $key, true); ?></p>
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
