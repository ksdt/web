<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xx
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">

	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js" integrity="sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD" crossorigin="anonymous"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fluidbox/2.0.3/js/jquery.fluidbox.min.js" integrity="sha256-GT/cwCQ+2t1r4qUpW3eSXcQr1N2Shq/TVCrAzWaSgOY=" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/d391844db3.js"></script>
<script src="<?php echo get_template_directory_uri() . '/js/'; ?>audio.js"></script>
<script>
	/* http://v4-alpha.getbootstrap.com/ */
	
	

	/* function does ajax request to url, strips html response to the subset selector, 
	   and then replaces / updates destinationSelector content depending on replace flag
	   
	   url: url of new content
	   subset: content selector with which to get data from e.g. #main-content
	   destinationSelector: content selector with which to put data into e.g. #content-zone
	   replace: if true, replaces destinationSelector with subset, if false, replaces CONTENT of destinationSelector with subset
	*/
	function sickAjaxRoutine(url, subset, destinationSelector, replace) {
		if (!subset) subset = '';
		$(destinationSelector).addClass('loading');
		$('.spinner').show();
		$.get(url, function(response) {
			$('.spinner').hide();
			$(destinationSelector).removeClass('loading');
			if (replace)
				$(destinationSelector).replaceWith($(response).find(subset).contents());
			else 
				$(destinationSelector).html($(response).find(subset).contents());
			window.scrollTo(0, 0);
			history.pushState({url: url, 
				subset: subset, 
				destinationSelector: destinationSelector, 
				replace: replace 
				}, null, url);
			document.title = $(response).filter('title').text();
            $( document.body ).trigger( 'post-load' );
		})
	}
	window.addEventListener('popstate', function(e) {
		
		sickAjaxRoutine(e.state.url, e.state.subset, e.state.destinationSelector, e.state.replace);
		
		e.preventDefault();
	});
	
	/* global ajax settings */
	$.ajaxSetup({
	    timeout: 5000, //if longer than 5 seconds just give up
	    /* on error function, TODO */
	    error: function(event, request, settings){
	        alert("Oops!");
	        console.log(event, request, settings);
	    }
	});
	
	/* on nav links, reload full view */
	$('header').on('click', 'a.nav-link', function(e) {
		var url = $(e.currentTarget).attr('href');
		console.log(url);
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
	});
	
	/* on list entry links, just load into reading zone */
	$('main.site-main').on('click', 'ul.index-post-list > a', function(e) {
		var url = $(e.currentTarget).attr('href');
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
		e.stopImmediatePropagation();
	});
	
	$('main.site-main').on('click', '.day-selection li', function(e) {
		var day = $(e.currentTarget).index();
		$('.day-selection li').removeClass('selected');
		$(e.currentTarget).addClass('selected');
		$('table.day').removeClass('selected');
		$('table.day').eq(day).addClass('selected');
	});
	/* clicking a show on the programming page */
	$('main.site-main').on('click', 'article.page.programming td > a', function(e) {
		var url = $(e.currentTarget).attr('href');
		console.log(url);
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
		e.stopImmediatePropagation();
	});
	
	/* need one for article.page.writings and article.hentry -- will complete once I get back home */
	/* For writings */
	$('main.site-main').on('click', 'article .col-extended', function(e) {
		var url = $(e.currentTarget).attr('href');
		console.log(url);
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
		e.stopImmediatePropagation();
		
	});

	$('main.site-main').on('click', 'article.page.writings .col-title', function(e) {
		var url = $(e.currentTarget).attr('href');
		console.log(url);
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
		e.stopImmediatePropagation();
		
	});
	
	
	
	$('main.site-main').on('click', 'a', function(e) {
		var url = $(e.currentTarget).attr('href');
		if (url.charAt(0) == '/') {
			sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
			e.preventDefault();
			e.stopImmediatePropagation
		}
	});

    $('header.site-header').on('click', '.player i.fa-play', function(e) {
        AudioHandler.play();
    });

    $('header.site-header').on('click', '.player i.fa-pause', function(e) {
        AudioHandler.pause();
    });

    var AudioHandler = {
        _isPlaying: false,
        _audioSource: '//ksdt.ucsd.edu/listen/stream.mp3',
        _audio: null,
        _draw: null,
        _elem: $('div.player'),
        pause: function() {
            this._analyser.audio.pause();
            this._elem
                    .find('i')
                    .removeClass('fa-pause')
                    .addClass('fa-play');
        },
        play: function() {
            try {
                if (this._analyser && this._analyser.audio) {
                    var _this = this;
                    var latestBufPoint = this._analyser.audio.buffered.length;
                    latestBufPoint = this._analyser.audio.buffered.end(latestBufPoint - 1);
                    this._analyser.audio.currentTime = latestBufPoint;
                    this._analyser.audio.play();
                    this._elem.find('i').removeClass('fa-play');
                    this._elem.find('i').addClass('fa-spinner fa-spin');
                    $(this._audio).one('playing', function(e) {
                        _this._elem.find('i')
                        .removeClass('fa-spinner fa-spin')
                        .addClass('fa-pause');
                    });
                } else {
                    this._audio = new Audio(this._audioSource);
                    var _this = this;
                    AudioAnalyser(this._audio, { passthru: true }).then((result) => {
                        _this._analyser = result;
                        _this._analyser.audio.play();
                        _this._draw = function draw() {
                            window.requestAnimationFrame(draw);
                            //console.log(_this._analyser.frequencies());
                        }; _this._draw();
                        
                    });    
                    this._elem.find('i').removeClass('fa-play');
                    this._elem.find('i').addClass('fa-spinner fa-spin');
                    $(this._audio).one('playing', function(e) {
                        _this._elem.find('i')
                        .removeClass('fa-spinner fa-spin')
                        .addClass('fa-pause');
                    });
                }
            } catch (e) {
                console.log(e);
            }
        },
    };
	
</script>
</body>
</html>
