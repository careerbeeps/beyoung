<?php mesmerize_get_header();  

?>

    <div class="content blog-page bdk">    
        <div class="<?php mesmerize_page_content_wrapper_class(); ?>">
            <div class="row">
                <div class="col-xs-12 <?php mesmerize_posts_wrapper_class(); ?>">
                    <div class="post-list row" id="overload" >
                        <?php echo do_shortcode('[ajax_load_more post_type="post" posts_per_page="5"  button_label="" transition_container="false"]');
						
                      /**  $blogarg =array('posts_per_page'=>2);
                	$blogquery=new wp_query($blogarg);
                        if ($blogquery->have_posts()):
                            while ($blogquery->have_posts()):
                                $blogquery->the_post();
                                get_template_part('template-parts/content', get_post_format());
                            endwhile; wp_reset_query();
                        else:
                            get_template_part('template-parts/content', 'none');
                        endif;
						**/
                        ?>
                    </div>
                    <div class="navigation-c"> 
                        <?php
                        /**if ($blogquery->have_posts()):
                            mesmerize_print_pagination();
                        endif;**/
                        ?>
                    </div>
                </div>
                <div class="sidebar col-sm-4 col-md-3"><div class="sidebar-row">
				  <?php dynamic_sidebar('homepage-sidebar'); 
				?>
					</div>
					</div>
            </div>
        </div>
    </div>

<?php get_footer();
