<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package xx
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        	<div class="container" style = "background-color: white">
        			    <article class = "page blog" href="https://new-website-ksdt-tennysonholloway.c9users.io/programming/">
        			        <div class = "entry-content">
        			        
        						<!-- Blog -->
        			            <div class = "row">
        							<?php 
        							
        							$args = array(
        							    'post_type'=> 'post',
        							    
        							    );              
        							
        							$the_query = new WP_Query( $args );
        							$count = 0;
        							if($the_query->have_posts() ) : ?>
        							    <h1>Blog</h1>
        							<?php 
        						
        							while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                                        <div class = "col-lg-3 col-md-4 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
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
