<article <?php post_class() ?>>

<div class ="entry-content">
	<div class ="row">
		<span class="title"><?php post_type_archive_title() ?></span>
		<br>
		<div class="line"></div>
	    <?php while( have_posts() ) : the_post(); ?>
            <div class = "col-lg-3 col-md-6 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
				<div class = "card">
					<?php if(has_post_thumbnail()) : ?>
					<div class = "image-div">
						<img class = "image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
					</div>
					<?php else:?>
					<div class = "no-image"></div>
					<?php endif;?>
					<div class = "card-block card-block-extended">
						<span class = "card-title card-title-extended"><?php the_title() ?></span>
						<br>
						<span class="entry-date"><?php echo get_the_date(); ?> <span>/ by <?php the_author(); ?> </span> </span>
					</div>
				</div>
            </div>
	    <?php  endwhile;?>




	</div>

</div>
</article>
