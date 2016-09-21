<article <?php post_class() ?>>

	
	<div class ="row">
		<h1><?php post_type_archive_title() ?></h1>
	    <?php while( have_posts() ) : the_post(); ?>
            <div class = "col-lg-3 col-md-4 col-sm-6 col-xs-12 col-extended" href = "<?php the_permalink(); ?>">
				<div class = "card">
					<?php if(has_post_thumbnail()) : ?>
					<div class = "image-div">
						<img class = "card-img-top image-extended" src = "<?php the_post_thumbnail_url() ?>" alt = "Card image cap">
					</div>
					<?php else:?>
					<div class = "no-image"></div>
					<?php endif;?>
					<div class = "card-block card-block-extended">
						<h5 class = "card-title card-title-extended"><?php the_title() ?></h5>
					</div>
				</div>
            </div>
	    <?php  endwhile;?>
	    

		
			    
	</div>
</article>