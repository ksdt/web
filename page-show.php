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

<?php
    /*
    permalink              rewrite rule
    /shows/Burger Town  -> show/?showName=Burger Town
    /shows/rtfm         -> show/?showName=rtfm

    spinpapi api: https://spinitron.com/user-guide/pdf/SpinPapi-v2.pdf
    */

    include(get_template_directory() . '/inc/SpinPapiConf.inc.php');

    $sp = new SpinPapiClient($mySpUserID, $mySpSecret, $myStation, true, $papiVersion);
    $sp->fcInit(get_template_directory() . '/.fc');

    $showName = get_query_var('showName');

    if ($showName) { /* if showName exists and isn't blank */
        /* get all shows from spinitron */
        $shows = $sp->query(array(
            'method' => 'getRegularShowsInfo',
            'When' => 'all'
        ));
        /* if the spiniron request was successful and has results */
        if ($shows['success'] && $shows['results']) {
            $shows = $shows['results']; /* set $shows to the results */
            $show = ''; /* placeholder for our matched show */

            /* search for show by show name */
            $showMatch = 0; /* similar_text returns a higher int if match is closer */
            foreach ($shows as $s) {
                if (similar_text(strtolower($s['ShowName']), strtolower($showName)) > $showMatch) {
                    $show = $s;
                    $showMatch = similar_text(strtolower($s['ShowName']), strtolower($showName));
                }
            }

            /* grab all playlists for matched show */
            $playlistsQ = $sp->query(array(
                'method' => 'getPlaylistsInfo',
                'ShowID' => $show['ShowID'],
                'Num' => 99,
                'EndDate' => date('Y-m-d')
            ));

            $firstPlaylist = array();

            /* parse first playlist to get song information */
            if ($playlistsQ['success'] && $playlistsQ['results']) {
                $firstPlaylist = $sp->query(array(
                        'method' => 'getSongs',
                        'PlaylistID' => $playlistsQ['results'][0]['PlaylistID']
                    ))['results'];
            }

            $allPlaylists = $playlistsQ['results'];
        } else { /* spinitron query failed */
        echo 'spinitron failed';
            status_header( 404 );
            get_template_part( 404 ); exit();
        }
    } else { /* showname was blank */
    echo 'no showname';
        status_header( 404 );
        get_template_part( 404 ); exit();
    }

    function get_times($show) {
        $weekday = DateTime::CreateFromFormat('D', $show['Weekdays'][0]);
        $start = DateTime::CreateFromFormat('G:i:s', $show['OnairTime']);
        $end = DateTime::CreateFromFormat('G:i:s', $show['OffairTime']);
        return $weekday->format('l\s') . ' from ' . $start->format('ga') . '-' . $end->format('ga');
    }

    function timestamp($time) {
        $time = DateTime::CreateFromFormat('G:i:s', $time);
        return $time->format('g:ia');
    }

    function get_djs($show) {
        $string = '';
        foreach($show['ShowUsers'] as $user) {
            $string = $user['DJName'] . ' & ';
        }
        return substr($string, 0, -3);
    }

    function get_random_shows($shows) {
        return array_rand($shows, 10);
    }

?>


	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<article class="page show">
				    <div class="entry-content">
				        <div class="show">
				            <div class="row">
				                <div class="col-lg-3 offset-lg-3 col-md-12 info">
				                    <span class="tag tag-default <?php echo $show['ShowCategory']; ?>"><?php echo $show['ShowCategory']; ?></span>
			                        <h1 class="title"><?php echo $show['ShowName']; ?></h1>
			                        <span class="djs"><?php echo get_djs($show); ?></span>
			                        <span class="times"><?php echo get_times($show); ?></span>
			                        <p class="description"><?php echo $show['ShowDescription']; ?></p>
				                </div>
				                <div class="col-lg-6 col-md-12 other-info">
				                    <?php if ($firstPlaylist): ?>
				                        <h2>Most Recent Playlist</h2>
				                    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/sass/plyr.css'; ?>" type="text/css" />
                                        <audio controls src="/playlists/<?php echo $allPlaylists[0]['PlaylistID']  . '.mp3' ?>">
                                            Your browser does not support the <code>audio</code> element.
                                        </audio>
                                        <script src="<?php echo get_template_directory_uri() . '/js/plyr.js'; ?>"></script>
				                    <script>
				                       var $ = $ ? $ : jQuery;
				                       var audios = plyr.setup({
				                           controls: ['play', 'progress', 'current-time', 'mute', 'volume']
				                       });
				                       audios.forEach(function (audio) {
				                           audio.on('error', function(e) {
				                               console.log(e);
				                               audio.destroy();
				                               $('audio').replaceWith('Error retrieving show.');
				                           });
				                       });
				                    </script>
				                        <span class="playlist-date"><?php echo human_time_diff(strtotime($allPlaylists[0]['PlaylistDate']), get_the_time('U', true)) . ' ago'; ?></span>
				                        <ul class="songs">
				                            <?php foreach ($firstPlaylist as $song): ?>
				                                <li>
				                                    <span class="timestamp"><?php echo timestamp($song['Timestamp']); ?></span>
				                                    <span class="song"><?php echo $song['SongName']; ?></span>
				                                    <span class="artist"><?php echo $song['ArtistName']; ?></span>
                                                </li>
                                            <?php endforeach; ?>
				                        </ul>
				                        <h2>All Playlists</h2>
    				                    <ul class="playlists">
    				                        <?php foreach ($allPlaylists as $playlist): ?>
    				                            <li><a href="/playlist/<?php echo $playlist['PlaylistID']; ?>"><?php echo $playlist['PlaylistDate']; ?></a></li>
    				                        <?php endforeach; ?>
    				                    </ul>
    				                <?php endif; ?>
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
