<?php
/**
 * The template for displaying all pages.
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
			<div class="container">
				<article class="page contact">
				    <div class="entry-content">
    				    <div class="row">
                            <?php echo do_shortcode('[caption id="attachment_921" align="aligncenter" width="700"]<img class="wp-image-921" src="https://ksdt.ucsd.edu/wp-content/uploads/2015/01/18193100_10208828185282504_8597061108762774029_o.jpg" width="700" height="394" /> 2016-2017 Staff[/caption]'); ?>
                            <div class="row">
                                <div class="col-lg-4 offset-lg-2" style="margin-top:30px;">
                                    <span style="text-decoration: underline;"><strong>Meetings</strong></span><br>
                                    <em>General Body Meeting: </em>5PM - 6PM on Wednesdays<br>

                                    GBMs are open to all members and non-members, and will mainly be updates about the station and upcoming events. It’s the perfect meeting to come to if you are looking to get involved further with the station, or just want to keep up with the latest news.

                                    <br><br><em>Staff Meeting: </em>5PM - 6PM on Fridays<br>

                                    Staff meetings are where the details get worked out and ideas get introduced. They are open to all staff + djs! If you are interested in becoming a staff member, sitting in on these meetings helps tremendously! (:
                                </div>
                                <div class="col-lg-4" style="margin-top:30px;">
                                    <span style="text-decoration: underline;"><strong>About the Station</strong></span><br>

                                    KSDT is a student-run radio station located on the campus of the University of California, San Diego. The station has existed since 1967, but has taken many different forms since then. In its current configuration, KSDT broadcasts solely via streaming MP3 on the internet. KSDT provides music and activities for the UCSD community and the greater worldwide web — striving to promote independent music not available from mainstream sources and work to help the San Diego Community.
                                    <br><br>
                                    In addition to producing radio shows, KSDT maintains and operates studio facilities providing practice space for undiscovered area bands. KSDT uses its contacts in the music industry to produce campus appearances by KSDT approved acts, producing live Studio Sessions for up and coming independent bands.
                                </div>
                            </div>
    				    </div>

                	</div><!-- .entry-content -->
                </article><!-- #post-## -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
