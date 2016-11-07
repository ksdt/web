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

	<div class="egg">
		<img src="<?php echo get_template_directory_uri() . '/van.png'; ?>">
	</div>

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
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
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

	/* index slider */
	$(document).ready(function(){
		$('.slick-slider').slick({
			dots: true,
			slidesToShow: 1,
			arrows: false
		});
	});

	/* global ajax settings */
	$.ajaxSetup({
	    timeout: 5000, //if longer than 5 seconds just give up
	    /* on error function, TODO */
	    error: function(event, request, settings){
	        console.log(event, request, settings);
	    }
	});

	/* on nav links, reload full view */
	$('header').on('click', 'a.nav-link.local-link', function(e) {
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

	$('header .ksdt-name').on('click', 'a', function(e) {
		var url = $(e.currentTarget).attr('href');
		console.log(url);
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
	});



	$('main.site-main').on('click', 'a', function(e) {
		var url = $(e.currentTarget).attr('href');
		if (url.charAt(0) == '/') {
			sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
			e.preventDefault();
			e.stopImmediatePropagation();
		}
	});

	$('header .player .show-info').on('click', 'a', function(e) {
		var url = $(e.currentTarget).attr('href');
		sickAjaxRoutine(url, 'main.site-main', 'main.site-main', false);
		e.preventDefault();
		e.stopImmediatePropagation();

	});

    $('header.site-header').on('click', '.player i.fa-play', function(e) {
        AudioHandler.play();
    });

    $('header.site-header').on('click', '.player i.fa-pause', function(e) {
        AudioHandler.pause();
    });

	function updateShowInfo() {
		$.getJSON('./radio-data')
			.done(function(data) {
				if (data.success && !data.results) {
					$('.player .show-name').text('Rotation');
					$('.player .show-djs').text('rotation');
					$('.player .show-name')
						.parent().css('pointer-events', 'none')
				} else {
					$('.player .show-name').text(data.results[0].ShowName);
					var djs =
							data
								.results[0]
								.ShowUsers
								.map(function(x) { return x['DJName']; })
								.join(' & ');
					$('.player .show-djs').text(djs);
					$('.player .show-name')
						.parent().css('pointer-events', 'all')
					$('.player .show-name')
						.parent()
						.attr('href', 'show/' + $('.player .show-name').text())
				}

			})
	} updateShowInfo();
	window.setInterval(updateShowInfo, 5000);

	var egg = 0;
	$('.egg img').click(function() {
		if (egg == 0) {
			$('.egg img').parent().css({
				'transform': 'inherit',
				'opacity': 1
			});
			egg++;
			return;
		}
		var eggElem = $('.egg img');
		eggElem.css('transform', 'translateX(-' + (egg * ($(document).width() / 3)) + 'px)');
		egg++;
		if (egg == 4) {
			console.log("HERE");
			setTimeout(function() {
				window.location.href = "https://www.youtube.com/watch?v=9Yrt9qkBQ2Q";
			}, 400);
		}

	});

	var Visualizer = {
		_elem: $('#vis')[0],
		_ctx: null,
		_width: null,
		_height: null,
		init: function() {
			this._ctx = this._elem.getContext('2d');
			this._width = this._elem.offsetWidth;
			this._height = this._elem.offsetHeight;
			this._elem.width = this._width;
			this._elem.height = this._height;
			console.log(this);
		},
		draw: function(audioDatas) {
			/* put data into x buckets first */
			var buckets = 32;
			var freqs = [];
			for (var i = 0; i < buckets; i++) {
				var avg = 0;
				for (var x = Math.floor(i * audioDatas[0].length / buckets); x < Math.floor((i+1) * audioDatas[0].length / buckets); x++) {
					avg += (audioDatas[0][x] + audioDatas[1][x]) / 2;
				}
				freqs.push(avg / (audioDatas[0].length / buckets));
			}
			/* drop high freqs */
			freqs = freqs.slice(0, 32);
			this._ctx.clearRect(0, 0, this._width, this._height);

			for (var i = 0; i < freqs.length; i++) {
				var nFreq = this._height * (freqs[i] / 256); //normalize freq
				this._ctx.fillRect(i * (this._width / freqs.length) + 0.5, this._height - nFreq,
									this._width / freqs.length - 2, nFreq)
			}
		}
	};

	var Oscilloscope = {
		_elem: $('.oscilloscope')[0],
		_height: null,
		_width: null,
		_xy: false,
		amplifer: function(v) { return v * 3.0; },
		init: function() {
			// Create the oscilloscopt wave element
		    this.wave = document.createElementNS("http://www.w3.org/2000/svg", 'path');
		    this.wave.setAttribute('class', 'oscilloscope__wave');

		    // Create the oscilloscope svg element
		    this.svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
		    this.svg.setAttribute('width', this._elem.offsetWidth);
		    this.svg.setAttribute('height', this._elem.offsetHeight);
		    this.svg.setAttribute('class', 'oscilloscope__svg');
		    this.svg.appendChild(this.wave);

			this._height = this._elem.offsetWidth;
			this._width = this._elem.offsetHeight;

		    // Append the svg element to the target container
		    this._elem.appendChild(this.svg);
		},
		draw: function(byteFreqDatas) {
			var path = 'M';

			if (this._xy) { //xy mode
		        for (var i = 0; i < byteFreqDatas[0].length - 400; i++) {
	            	path += (this._height / 2 * ((byteFreqDatas[0][i] / 128)) ) + ' ' + (this._height / 2 * ((byteFreqDatas[1][i] / 128))) + ', ';
		        }
			} else {
				for (var i = 0; i < byteFreqDatas[0].length; i++) {
					path += ((this._width / byteFreqDatas[0].length) * i) + ' ' + ((this._height / 2.0) + ((this._height) * ((byteFreqDatas[0][i] - 128.0) / 100))) + ', ';
				}
			}

	        this.wave.setAttribute('d', path);
		}
	};
	$(Oscilloscope._elem).click(function() {
		Oscilloscope._xy = !Oscilloscope._xy;
		if (Oscilloscope._xy) {
			$(Oscilloscope.svg).css('transform', 'scale(4.5)');
			$('.oscilloscope__wave').css('stroke-width', '0.1px');
		} else {
			$(Oscilloscope.svg).css('transform', 'scale(1)');
			$('.oscilloscope__wave').css('stroke-width', '0.5px');
		}
	})


    var AudioHandler = {
        _isPlaying: false,
        _audioSource: 'https://ksdt.ucsd.edu/listen/stream.mp3',
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
					this._audio.crossOrigin = "anonymous";
                    var _this = this;
					Visualizer.init();
					Oscilloscope.init();
                    AudioAnalyser(this._audio, { passthru: true, smoothing: 0.7 }).then((result) => {
                        _this._analyser = result;
                        _this._analyser.audio.play();
						_this._draw = function draw() {
                            window.requestAnimationFrame(draw);
							if (!_this._audio.paused) {
                            	Visualizer.draw([
									_this._analyser.frequencies(0), _this._analyser.frequencies(1)
								]);
								Oscilloscope.draw([
									_this._analyser.waveform(0), _this._analyser.waveform(1)
								]);
							}
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
