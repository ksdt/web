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
				<div class="nav-zone">
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
				<script>
					sbi_init();
				</script>
				<div class="reading-zone">
					<?php
						/* if you want a full frame iframe, put it here */
						/* else, just leave it blank, and instagram will show */
						$iframeUrl = get_theme_mod('fulliframe', '');

						if ($iframeUrl): ?>
							<iframe class="full-iframe" src="<?php echo $iframeUrl; ?>" frameborder="0" width="100%" height="100%"></iframe>
							<script>
								$ = $ ? $ : jQuery;
								
								function resizeFrame() {    
								    var $el = $('div.container'),
								        scrollTop = $(this).scrollTop(),
								        scrollBot = scrollTop + $(this).height(),
								        elTop = $el.offset().top,
								        elBottom = elTop + $el.outerHeight(),
								        visibleTop = elTop < scrollTop ? scrollTop : elTop,
								        visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
								    $('.full-iframe').height(visibleBottom - visibleTop);
								    $('.full-iframe').width($('div.reading-zone').width());
								}
								
								$(window).on('resize', resizeFrame);
								resizeFrame();
							</script>
					<?php else:
							$insta = file_get_contents('https://api.instagram.com/v1/users/1297925718/media/recent/?access_token='. INSTA_TOKEN .'&count=50');
							$insta = json_decode($insta, true)['data'];
					?>
						<?php foreach($insta as $gram): ?>
							<div class="lightbox-caption" data-gram="<?php echo $gram['id']; ?>">
								<div>
									<a class="cya-styles" href="//www.instagram.com/ksdtradio/"><img class="insta-profile img-circle" src="<?php echo $gram['user']['profile_picture']; ?>"/></a>
									<a class="cya-styles" href="//www.instagram.com/ksdtradio/"><span class="insta-user"><?php echo $gram['user']['username']; ?></span></a>
									<a class="cya-styles" href="<?php echo $gram['link']; ?>"><p><?php echo $gram['caption']['text']; ?></p></a>
								</div>
							</div>
							<a class="lightbox overlay-1" href="<?php echo $gram['images']['standard_resolution']['url']; ?>" data-gram="<?php echo $gram['id']; ?>">
								<img src="<?php echo $gram['images']['standard_resolution']['url']; ?>" alt="" title=""/>
							</a>
						<?php endforeach; ?>
						<script>
							/* lightbox shit for insta */
							$ = $ ? $ : jQuery;
							$(function() {
								$('.lightbox').each(function() {
									$(this).fluidbox({viewportFill: 0.8})
										.on('openend.fluidbox', function(e) {
											console.log(this);
											var gramId = $(this).data('gram');
											
											$('.reading-zone').find('div[data-gram='+gramId+']').show();
										})
										.on('closestart.fluidbox', function() {
											var gramId = $(this).data('gram');
											$('.reading-zone').find('div[data-gram='+gramId+']').hide();
										});
								});
							});
							$(window).scroll(function() {
								$('.lightbox').fluidbox('close');
							});
						</script>
					<?php endif; ?>
					
					<!--<div class="player" id="player">
						<div class="meta">
							<img class ="img-fluid" src="http://www.commmons.com/2015/08/28/20150828225933.jpg"/>
							<p>Undercooled by Ryuichi Sakamoto</p>
							<div class="start">
								<p>touch to listen</p>
							</div>
						</div>
					</div>-->
				</div>
				
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
