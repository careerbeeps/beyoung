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
        <?php mesmerize_print_inner_pages_header_content(); ?>
        <?php mesmerize_print_header_separator(); ?>
    </div>
</div>


	<div class="content blog-page">
	<div class="gridContainer content">
		<div class="row">

				<?php
				$args = array( 'posts_per_page' => 1,'post_type'=>'post', 'tag' => 'Featured' );

				$postlist = get_posts($args); 

				foreach ( $postlist as $post ) {
				setup_posdata($post ) 

				?>
				<div class="col-xs-12 col-sm-8 col-md-9">
				<?php the_post_thumbnail_url( 'full' ); ?>
				</div>
				<div class="col-xs-12 col-sm-8 col-md-3 pull-left">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
				<?php the_excerpt();?>

				</div>

				<?php
				}
				wp_reset_postdata();


				?>

		
		</div>
	</div>
	</div> 