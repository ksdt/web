<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xx
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" integrity="sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fluidbox/2.0.3/css/fluidbox.min.css" integrity="sha256-LtmxIjp0kTPsfOD7AYymAwy2CRw4bwU403I7IT+SCkU=" crossorigin="anonymous" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<header id="masthead" class="site-header" role="banner">
		<span class="ksdt-name">
			<a href="/">KSDT Radio</a>
			<span class="ksdt-name-sub">
				<a href="/contact">LA JOLLA, CA</a>
			</span>
		</span>
		<nav class="navbar navbar-light bg-faded navbar-extend">
			
			<div class="collapse navbar-toggleable-md" id="exCollapsingNavbar2">
				<ul class="nav navbar-nav">
					<?php $main_menu = wp_get_nav_menu_items('main'); ?>
					
					<?php foreach ((array) $main_menu as $key => $menu_item): ?>
                		<?php 
                			$title = $menu_item->title;
					        $url = $menu_item->url;
					    ?>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo $url; ?>"><?php echo $title; ?></a>
						</li>
                    <?php endforeach; ?>
					
				</ul>
        <div class="player">
            <i class="fa fa-play" aria-hidden="true"></i>
        </div>
			</div>
			<button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar2" aria-controls="exCollapsingNavbar2" aria-expanded="false" aria-label="Toggle navigation">
				    &#9776;
				  </button>
		</nav>
            
	</header><!-- #masthead -->
	<div class="spinner"></div>
	<div id="content" class="site-content">
