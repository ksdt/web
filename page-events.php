<?php get_header(); ?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div class="container">
			<article class="page events">
			    <div class="entry-content">
			    	
                    <div class="row">
                    	<?php if (class_exists('EM_Events')) {
                    	echo "<h1>Upcoming Events </h1>";
                    	echo EM_Events::output( array(
                    		'limit'=>99,
                    		'orderby'=>'event_start_date',
                    		'format' => '<div class = "col-lg-3 col-md-3 col-sm-6 col-xs-12 col-extended" href="#_EVENTURL"><div class = "card text-xs-center"><div class = "image-div"><img class="card-img-top image-extended" src="#_EVENTIMAGEURL" alt="Card image cap"></div><div class = "card-block card-block-extended"><h4 class="card-title card-title-extended">#_EVENTNAME</h4><p class="card-text"><span class="tag tag-primary">#_LOCATIONNAME</span> <span class="tag tag-default">#_{D m.d}#@_{/m.d}</span></p></div></div></div>'
                    		/*
                    	    'format' => '<div class="pure-u-md-1-4 pure-u-1 showDiv"><a href="#_EVENTURL" class="agenda-link" style = "text-decoration: none;"><div class = "showContents"><div class = "eventImage">#_EVENTIMAGE</div><div class = "eventText"><h3>#_EVENTNAME</h3><h4>#_LOCATIONNAME</h4><h5>#_{D d.m}#@_{/d.m}</h5></div></div></a></div>'*/
                    	) );
                    	} ?>
                    </div>
                    
                    <div class="row">
                    	<?php if (class_exists('EM_Events')) {
                    	echo "<h1>Past Events</h1>";
                    	echo EM_Events::output( array(
                    		'limit'=>99,
                    		'scope'=>"past",
        		            'order'=>"DESC",
                    		'format' => '<div class = "col-lg-3 col-md-3 col-sm-6 col-xs-12 col-extended" href="#_EVENTURL"><div class = "card text-xs-center"><div class = "image-div"><img class="card-img-top image-extended" src="#_EVENTIMAGEURL" alt="Card image cap"></div><div class = "card-block card-block-extended"><h4 class="card-title card-title-extended">#_EVENTNAME</h4><p class="card-text"><span class="tag tag-primary">#_LOCATIONNAME</span> <span class="tag tag-default">#_{D m.d}#@_{/m.d}</span></p></div></div></div>'
                    		/*
                    	    'format' => '<div class="pure-u-md-1-4 pure-u-1 showDiv"><a href="#_EVENTURL" class="agenda-link" style = "text-decoration: none;"><div class = "showContents"><div class = "eventImage">#_EVENTIMAGE</div><div class = "eventText"><h3>#_EVENTNAME</h3><h4>#_LOCATIONNAME</h4><h5>#_{D d.m}#@_{/d.m}</h5></div></div></a></div>'*/
                    	) );
                    	} ?>
                    </div>


                       
			    </div>
			</article>
		</div>
	</main>
</div>
<?php get_footer();
