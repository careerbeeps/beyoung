<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="stylesheet" type="text/css" href="http://beyoung.in/pub/static/frontend/Emthemes/everything_default/en_US/css/customcss/mainbeyoung.css">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0&appId=1795968774032189&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div  id="page-top" class="header-top">
	<?php mesmerize_print_header_top_bar(); ?>
	<?php mesmerize_get_navigation(); ?>
</div>

<div id="page" class="site">
    <div class="header-wrapper">
        <div <?php echo mesmerize_header_background_atts(); ?>>
            <?php do_action( 'mesmerize_before_header_background' ); ?>
            <?php mesmerize_print_video_container(); ?>
            <?php //mesmerize_print_inner_pages_header_content(); ?>
            <?php mesmerize_print_header_separator(); ?>
        </div>
    </div>
    <div class="top-header-section" style="margin-top:70px; ">
<style type="text/css">
  .img-section {max-height: 380px;    overflow: hidden;}
  .img-section img {    width: 100%;}
</style>
      <div class="content blog-page"> 


                 <?php
                 global $exclude_post_id;
                 if(is_home() || is_front_page()){
                  echo ' <div class="gridContainer content"><div class="row hometickypost"> ';
                $args = array( 'posts_per_page' => 1,'post_type'=>'post', 'tag' => 'Featured' );

                $postlist = get_posts($args); 

                if(!empty( $postlist)){

                foreach ( $postlist as $post ) { 
                  setup_postdata($post);
                  $exclude_post_id[]=$post->ID;

                    ?>
                      <div class="col-xs-12 col-sm-8 col-md-3">
                      <h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                      <?php $category = get_the_category();
					  if ( ! empty( $category ) ) {
                            $firstCategory = $category[0]->cat_name;
					 
							?>
							
                      <a class="cat_u" href="<?php echo get_category_link($category[0]->term_id); ?>"><?php echo $firstCategory; ?></a>
                      <?php } ?>
                      <div>By <?php echo the_author_meta( 'display_name', $postlist->post_author ); ?> ● <?php echo get_the_date( 'F, j Y', $postlist->ID ); ?></div>
                    <?php the_excerpt();?>

                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-9 img-section">
                   <a href="<?php the_permalink( $post->ID); ?>"> <img src="<?php the_post_thumbnail_url( 'full' ); ?>" class="img-responsive" /></a>
                    </div>
                  

                    <?php
                 }
                   wp_reset_postdata(); 
                }
                  echo ' </div></div> ';
              }elseif(is_single()){
                ?>
                 <div class="img-section">
                  <img src="<?php the_post_thumbnail_url( 'full' ); ?>" class="img-responsive" />
                 </div>
                 <?php 

                 
              }
              ?> 
      </div> 
      
    </div>
