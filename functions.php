<?php
/**
* Set javascript vars
* 
* @return
*/
function pluginname_ajaxurl(){
	?>
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var siteurl = '<?php echo site_url(); ?>';
		var is_user_logged_in = '<?php echo (bool)is_user_logged_in(); ?>';
	</script>
	<?php
}
add_action('wp_head','pluginname_ajaxurl');
/**
* Add javascript file
* 
* @return null
*/
function costom_add_scripts(){	
	wp_enqueue_script('scripts', get_template_directory_uri().'/js/scripts.js', array('jquery'), NULL, true);
}
add_action('wp_enqueue_scripts', 'costom_add_scripts');
/**
* Ajax get posts 
* 
* @return json
*/
function get_posts_ajax(){
	extract($_POST);
	$result = array();
	$args = json_decode(base64_decode($args), true); 
	$query = new WP_Query($args);
	ob_start();
	if($query->have_posts()) while($query->have_posts()): $query->the_post(); get_post_template(); endwhile;
    wp_reset_postdata();
	wp_reset_query();
	$result['html'] = ob_get_clean();
	if($query->max_num_pages > $args['paged']){
		$args['paged']++;
		$result['args'] = base64_encode(json_encode($args));
	}
	echo json_encode($result);
	wp_die();
}
add_action( 'wp_ajax_get_posts_ajax', 'get_posts_ajax');
add_action( 'wp_ajax_nopriv_get_posts_ajax', 'get_posts_ajax');
/**
* Front-end function show posts
* 
* @return html
*/
function get_posts_template(){
	?>
	<div id="ajaxOut">
        <?php if(have_posts()) while(have_posts()) : the_post(); get_post_template(); endwhile;?>
	</div>
	<?php
	global $wp_query;
	$args = $wp_query->query_vars;
	$args['paged'] = 1;
	$query = new WP_Query($args);    	
	if($query->max_num_pages > 1): $args['paged']++;?>
	<div class="moreLink">
		<a href="#" class="loadmoreAjax" data-args="<?php echo base64_encode(json_encode($args));?>">VIEW MORE</a>
	</div>
	<?php endif;
	wp_reset_query();
}
/**
* Loop item template
* 
* @return html
*/
function get_post_template(){
	?>
	<div class="postBox">
        <a href="<?php the_permalink(); ?>">
	        <?php if(has_post_thumbnail()): the_post_thumbnail(); ?>
	        <?php else: ?>
	        	<img width="92" height="92" alt="" class="attachment-thumbnail wp-post-image" src="<?php echo get_template_directory_uri(); ?>/img/placeholders.jpg">
	        <?php endif; ?>
	        <div class="infoBox">
	        	<time><?php echo get_the_date('d.m.Y'); ?></time> |
	        	<span class="catList"><?php echo get_the_category_list(); ?></span>
	        </div>
	        <h3><?php the_title();?></h3>
	        <?php the_excerpt();?>
	        <div class="metaBox">
				<span class="commentsNumber"><?php comments_number(); ?></span>	            
	        </div>
        </a>
    </div>
	<?php
}