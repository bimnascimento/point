<?php
/*
* @Author 		PickPlugins
* Copyright: 	2015 PickPlugins.com
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

	get_header();
	do_action('uv_action_before_single_question');

	while ( have_posts() ) : the_post(); 
	?>
	<div id="question-<?php the_ID(); ?>" <?php post_class('single-question entry-content'); ?>>
        
    <?php do_action('uv_action_single_question_main'); ?>

    </div>
	<?php
	endwhile;
		
    do_action('uv_action_after_single_question');
	get_footer();