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

				<!-- New row -->
				<div class ="row row-slider-show-schedule" style="">
					<!--- Left Side -->
					<div class ="col-lg-9 col-md-12 col-sm-12 left-side" style ="z-index: 1;">

						<div class="module-title" style ="margin-left: -15px;">
							<span class="post-featured">Featured</span>
							<div class="post-line"></div>
						</div>

						<div class ="row module-content">
							<div class = "slick-sliderr" style ="">

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
									'posts_per_page' => 5
								];
								$query1 = new WP_Query( $featuredQuery );
								if ( $query1->have_posts() ):
									while ( $query1->have_posts() ):	$query1->the_post();  $mostRecentFeaturedId = get_the_ID(); ?>
									<div>
										<a class="cya-styles index-post-link" href="<?php the_permalink(); ?>">
										<article class="index-post" data-url="<?php the_permalink(); ?>">

											<div class ="col-lg-5 col-md-5 col-sm-5 post-image">
												<div>
													<img src="<?php the_post_thumbnail_url() ?>">
												</div>
											</div>

											<div class ="col-lg-7 col-md-7 col-sm-7 post-text" style="background-color: white; overflow: height: 400px; max-height: 400px; min-height: 400px; overflow: hidden;">
												<div class="meta">
													<span class="post-title"><?php the_title(); ?> <span>/ <?php echo get_post_type()?></span></span>
													<br>
													<span class="entry-date"><?php echo get_the_date(); ?> / by <?php the_author(); ?></span>
												</div>
												<div class="snippet">
													<?php the_excerpt(); ?>
												</div>

											</div>
										</article>
									</a>
									</div>
								<?php   endwhile;
							endif;
							wp_reset_postdata();
							?>
							</div>
						</div>
						<script>
						jQuery(document).ready(function(){
							jQuery('.slick-sliderr').slick({
								dots: true,
								slidesToShow: 1,
								arrows: false
							});
						});
						</script>
					</div>


					<!-- Right Side -->
					<div class = "col-lg-3 col-md-12 col-sm-12 right-side">
						<?php date_default_timezone_set('America/Los_Angeles'); ?>
						<div class="module-title">
							<span class="post-featured"> <?php echo date('l');?>'s Schedule </span>
							<div class="post-line" style=""></div>
						</div>

						<div id ="module-shows-id" class = "module-content">
							<?php

							include(get_template_directory() . '/inc/SpinPapiConf.inc.php');

							$sp = new SpinPapiClient($mySpUserID, $mySpSecret, 'ksdt', true, $papiVersion);
							$sp->fcInit(get_template_directory() . '/.fc');

							$shows = $sp->query(array(
								'method' => 'getRegularShowsInfo',
								'When' => 'all'
							));
							//echo '<pre>' . var_export($shows, true) . '</pre>';

							if ($shows && $shows['success']) {
								//echo $shows['results'];
								$shows = $shows['results'];


								//sort shows into day of week buckets
								function showCmp($a, $b) {
									return intval(explode(':', $a['OnairTime'])[0]) < intval(explode(':', $b['OnairTime'])[0]) ? -1 : 1;
								}
								$shows = array(
									'Sun' => array_filter($shows, function($e) {
										// Checks if the sunday value exists in the array
										return in_array('Sun', $e['Weekdays']);
									}),
									'Mon' => array_filter($shows, function($e) {
										return in_array('Mon', $e['Weekdays']);
									}),
									'Tue' => array_filter($shows, function($e) {
										return in_array('Tue', $e['Weekdays']);
									}),
									'Wed' => array_filter($shows, function($e) {
										return in_array('Wed', $e['Weekdays']);
									}),
									'Thu' => array_filter($shows, function($e) {
										return in_array('Thu', $e['Weekdays']);
									}),
									'Fri' => array_filter($shows, function($e) {
										return in_array('Fri', $e['Weekdays']);
									}),
									'Sat' => array_filter($shows, function($e) {
										return in_array('Sat', $e['Weekdays']);
									}),
								);
								foreach ($shows as &$dayOfWeek) {
									usort($dayOfWeek, 'showCmp');
									foreach($dayOfWeek as &$show) {
										/* process time to human output */
										$show['OnairTimeAMPM'] = 'am';

										/*
										if (intval(explode(':', $show['OnairTime'])[0]) > 12) {
										$show['OnairTimeAMPM'] = 'pm';
										$show['OnairTime'] = intval(explode(':', $show['OnairTime'])[0]) - 12;
									} else {
									$show['OnairTime'] = intval(explode(':', $show['OnairTime'])[0]);
								}
								*/


								/* process djs */
								$show['djs'] = join(' & ', array_map('djs', $show['ShowUsers']));
							}
						}
					}
					function djs($a) {
						return $a['DJName'];
					}
					?>




					<?php foreach ($shows as $day => $showsDay): ?>
						<?php  if ($day == date('D')):?>
							<table class = "table table-hover">
								<tbody>
									<?php $number = 0; ?>
									<?php foreach($showsDay as &$show): ?>


										<tr id="module-show-<?php echo $number; ?>" style = "font-size: 1rem;" class = "module-show <?php if ( date('H') ==  date("H", strtotime($show['OnairTime']))) { echo "table-active";}?> clickable-row" data-href="/show/<?php echo $show['ShowName']; ?>">
											<!-- <span class="title"></span> -->

											<!-- date("g:i a", strtotime($show['OffairTime']) -->

											<td style="color: #f0ad4e;"><?php echo date("g a", strtotime($show['OnairTime'])); ?>-<?php echo date("g a", strtotime($show['OffairTime'])); ?></td>
											<td>
												<?php echo $show['ShowName']; ?>
												<br>
												w/ <?php echo $show['djs']; ?>
											</td>

										</tr>

										<script>

										var element = document.querySelector('#module-shows-id');
										var element2 = document.querySelector('#module-show-'+<?php echo $number; ?>)
										if( (element.offsetHeight < element.scrollHeight)){
											// your element have overflow
											element2.style.display = "none";
										}
										else{
											//your element don't have overflow
										}

										</script>
										<?php ++$number;?>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>

				</div>
				<div class = "module-link"> <a href="/programming">View More</a></div>
			</div>
		</div>

		<!-- This is where row and col were at -->

			<div class ="row row-index row-latest">
				<div class="post-header">
					<span class="post-featured">Latest</span>
					<div class="post-line" style="width: 60px;"></div>
				</div>
			</div>


	<?php

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

<script>
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
</script>

</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
