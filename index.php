<?php get_header(); ?>
    <section>
        <div class="sectionTitle">
            <h2>
            	<div>Posts</div>
       			<?php if(is_search()):?><span class="searchResultTitle"> Search results </span><?php endif;?>
       		</h2>
        </div>
   		<?php get_posts_template();?>
	</section>
<?php get_footer(); ?>