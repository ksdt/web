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
            $shows = $shows['results'];
            //sort shows into day of week buckets
            function showCmp($a, $b) {
                return intval(explode(':', $a['OnairTime'])[0]) < intval(explode(':', $b['OnairTime'])[0]) ? -1 : 1;
            }
            $shows = array(
                'Sun' => array_filter($shows, function($e) {
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
                    if (intval(explode(':', $show['OnairTime'])[0]) > 12) {
                        $show['OnairTimeAMPM'] = 'pm';
                        $show['OnairTime'] = intval(explode(':', $show['OnairTime'])[0]) - 12;
                    } else {
                        $show['OnairTime'] = intval(explode(':', $show['OnairTime'])[0]);
                    }
                    /* process djs */
                    $show['djs'] = join(' & ', array_map('djs', $show['ShowUsers']));
                }
            }
        }
        function djs($a) {
            return $a['DJName'];
        }
    ?>

    <?php //echo '<pre>' . var_export($shows, true) . '</pre>'; ?>



	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
				<article class="page programming">
				    <?php //echo '<pre>'; //echo wp_send_json($shows); echo '</pre>'?>
				    <div class="entry-content">
				        <div class="day-selection">
				            <ul>
				                <li <?php if ('Sun' == date('D')) echo 'class="selected"'; ?>>S</li>
				                <li <?php if ('Mon' == date('D')) echo 'class="selected"'; ?>>M</li>
				                <li <?php if ('Tue' == date('D')) echo 'class="selected"'; ?>>T</li>
				                <li <?php if ('Wed' == date('D')) echo 'class="selected"'; ?>>W</li>
				                <li <?php if ('Thu' == date('D')) echo 'class="selected"'; ?>>T</li>
				                <li <?php if ('Fri' == date('D')) echo 'class="selected"'; ?>>F</li>
				                <li <?php if ('Sat' == date('D')) echo 'class="selected"'; ?>>S</li>
				            </ul>
				        </div>
				        <?php foreach ($shows as $day => $showsDay): ?>
    				        <table class="day <?php echo $day; if ($day == date('D')) echo ' selected'; ?> table">
                            <?php foreach($showsDay as &$show): ?>
                                <?php if (!$show) continue; /* if show obj is bad, just skip. */ ?>
    				            <tr>
    				                <td class="time"><?php echo $show['OnairTime']; ?><span class="ampm"><?php echo $show['OnairTimeAMPM']; ?></span></td>
    				                <td class="show-td">
			                            <a href="/show/<?php echo $show['ShowName']; ?>" >
        				                    <div class="show">
                    				                <span class="title"><?php echo $show['ShowName']; ?></span><span class="tag tag-default <?php echo $show['ShowCategory']; ?>"><?php echo $show['ShowCategory']; ?></span>
                    				                <span class="djs"><?php echo $show['djs']; ?></span>
                    				                <p class="description">
                    				                    <?php echo $show['ShowDescription']; ?>
                    				                </p>
                				            </div>
    				                    </a>
    				                </td>
    				            </tr>
        				    <?php endforeach; ?>
        				    </table>
				        <?php endforeach; ?>
                	</div><!-- .entry-content -->
                </article><!-- #post-## -->
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
