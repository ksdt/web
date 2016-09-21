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
    permalink         rewrite
    playlist/41142 => playlist/?playlist=42141
    
*/
    include(get_template_directory() . '/inc/SpinPapiConf.inc.php');

    $sp = new SpinPapiClient($mySpUserID, $mySpSecret, $myStation, true, $papiVersion);
    $sp->fcInit(get_template_directory() . '/.fc');

    $playlist = get_query_var('playlist');
    function isInteger($input) {
        return(ctype_digit(strval($input)));
    }
    if (!isInteger($playlist)) {
        status_header( 404 );
        get_template_part( 404 ); exit();
    } else if ($playlist) {
        $playlistQ = $sp->query(array(
            'method' => 'getPlaylistInfo',
            'PlaylistID' => $playlist
        ));
        if ($playlistQ['success'] && $playlistQ['results']) {
            $playlist = $playlistQ['results'];
            $songs = $sp->query(array(
                'method' => 'getSongs',
                'PlaylistID' => $playlist['PlaylistID']
            ))['results'];
        } else {
            status_header( 404 );
            get_template_part( 404 ); exit();
        }
    } else {
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
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<article class="page playlist">
				    <div class="entry-content">
				        <div class="show">
				            <div class="row">
				                <div class="col-lg-3 offset-lg-3 col-md-12 info">
			                        <span class="title"><a class="cya-styles" href="/show/<?php echo $playlist['ShowName']; ?>"><?php echo $playlist['ShowName']; ?></a></span>
			                        <span class="playlist-date">Playlist on <?php echo $playlist['PlaylistDate']; ?></span>
				                    <link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/sass/plyr.css'; ?>" type="text/css" />
				                    <?php if (strtotime($playlist['PlaylistDate']) > strtotime('-2 weeks')): ?>
    				                    <audio controls src="<?php echo 'https://f001.backblazeb2.com/file/ksdt-archive/' . str_replace(' ', '+', $playlist['ShowName']) . '+' . $playlist['PlaylistDate'] . '.mp3' ?>">
                                            Your browser does not support the <code>audio</code> element.
                                        </audio>
                                        <script src="<?php echo get_template_directory_uri() . '/js/plyr.js'; ?>"></script>
    				                    <script>
    				                       var $ = $ ? $ : jQuery;
    				                       var audios = plyr.setup({
    				                           controls: ['play', 'current-time', 'mute', 'volume']
    				                       });
    				                       audios.forEach(function (audio) {
    				                           audio.on('error', function(e) {
    				                               console.log(e);
    				                               audio.destroy();
    				                               $('audio').replaceWith('Error retrieving show.');
    				                           });
    				                       });
    				                    </script>
    				                <?php else: ?>
    				                    <p>Shows older than 2 weeks old cannot be listened to online.</p>
    				                <?php endif; ?>
				                </div>
				                <div class="col-lg-6 col-md-12 other-info">
				                    <ul class="songs">
				                        <?php foreach($songs as $song): ?>
				                            <li>
				                                <span class="timestamp"><?php echo timestamp($song['Timestamp']); ?></span>
			                                    <span class="song"><?php echo $song['SongName']; ?></span>
			                                    <span class="artist"><?php echo $song['ArtistName']; ?></span>
				                            </li>
				                        <?php endforeach; ?>
				                    </ul>
				                </div>
				            </div>
                	    </div>
                	</div><!-- .entry-content -->
                </article><!-- #post-## -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
