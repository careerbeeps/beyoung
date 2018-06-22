  <?php if(is_singular()){ ?>
 <div class="container youmayalsolike">
<div class="row">
	<div class="col-xs-12 col-padding col-padding-xs">

   	<?php $categories = get_the_category();
					  if ( ! empty( $categories ) ) {
                             $firstCategorySlug = $categories[0]->slug;
					  }
					$currentpost= get_the_ID();
   	$blogarg =array('posts_per_page'=>-1,'post__not_in' => array( $currentpost, ),);
	if ( ! empty( $categories ) ) {
	$blogarg['tax_query'] = array(
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => $firstCategorySlug,
		),
	);
	}
                	$blogquery=new WP_Query($blogarg);

                        if ($blogquery->have_posts()): ?>
							<h3 class="alsolike">You May Also Like</h3>
							   <div class="loop owl-carousel owl-theme">
                           <?php while ($blogquery->have_posts()):
                                $blogquery->the_post();
                                $featured_img_url = get_the_post_thumbnail_url($post->ID,'full');
								if($featured_img_url==""){
									$featured_img_url= get_bloginfo('template_url')."/assets/images/ffffff.png";
								}
								
								$category = get_the_category();
					  
                                ?>
            <div class="item">
            	<?php if ( ! empty( $category ) ) {
                            $firstCategory = $category[0]->cat_name; ?>
            	<h4 class="catheading"><span><span to="<?php echo get_category_link($category[0]->term_id); ?>"><a class="category-tag" href="/category/live/"><?php echo $firstCategory; ?></a></h4>
            		<?php } ?>
              <h4 class="post-title">
                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                        <?php the_title(); ?>
                    </a>
                </h4>
           <div class="post-thumbnail">
        <a href="<?php the_permalink();?>" class="post-list-item-thumb ">
            <img width="500" height="342" src="<?php echo $featured_img_url; ?>" 
            class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" > </a>
    </div>

            </div>
                <?php endwhile; wp_reset_query();
                      ?> 
                      </div> 
                       <?php endif;
						?>
  
         
   </div>
    </div>
   </div>
   <?php } ?> 	
	<div class="gridContainer">
	<div class="fttbxc">
            <div class="row">
			
                <div class="col-md-3">
				<h3 class="foottxt">LOCATION</h3>
<ul class="blgftmenu">
<li><span> info@beyoung.in</span></li>
<li><span> (+91) 869-663-3366</span></li>
<li><span> 1-C-12, Gayatri Nagar, Sector 5</span></li>
<li><span>Udaipur, India 313002</span></li>
</ul>
				</div>
				<div class="col-md-3">
				<h3 class="foottxt">COMPANY</h3>
<ul class="blgftmenu">
<li><a href="http://beyoung.in/about-us"><span>About Us</span></a></li>
<li><a href="http://beyoung.in/blog"><span>Blog</span></a></li>
<li><a href="http://beyoung.in/terms-conditions"><span>Term and Conditions</span></a></li>
<li><a href="http://beyoung.in/privacy-policy"><span>Privacy Policy</span></a></li>
</ul>
				</div>
				<div class="col-md-3">
				<h3 class="foottxt">NEED HELP</h3>
<ul class="blgftmenu">
<li><a href="http://beyoung.in/contact"><span>Contact Us</span></a></li>
<li><a href="http://beyoung.in/return-refund-and-cancellation"><span>Return, Refund and Cancellation </span></a></li>
<li><a href="http://beyoung.in/FAQ"><span>FAQ's</span></a></li>
<li><a href="http://beyoung.in/sales/guest/form/"><span>Track Order</span></a></li>
</ul>
				</div>
				<div class="col-md-3">
				<h3 class="foottxt">LETS BE FRIENDS</h3>
<ul class="blgftmenu">
<a href="http://facebook.com/beyoungfolks"><span class="fa fa-fw"></span></a> 
<a href="https://twitter.com/BeyoungFolks"><span class="fa fa-fw"></span></a> 
<a href="https://in.pinterest.com/beyoungf/"><span class="fa fa-fw"></span></a> 
<a href="https://plus.google.com/u/0/115944147813954899038"><span class="fa fa-fw"></span></a> 
<a href="https://www.instagram.com/beyoung.in/"><span class="fa fa-instagram"></span></a>
</ul>
				</div>
<div class="footer_delever">Popular Categories<br>
<a href="http://beyoung.in/t-shirts">T-Shirts</a> | 
<a href="http://beyoung.in/trendy-t-shirts">Trendy T-Shirts</a> | 
<a href="http://beyoung.in/cool-t-shirts">Cotton T-Shirts</a> | 
<a href="http://beyoung.in/trendy-t-shirts">Trendy T-Shirts</a> | 
<a href="http://beyoung.in/cotton-t-shirts">Cotton T-Shirts</a> | 
<a href="http://beyoung.in/casual-t-shirts">Casual T-Shirts</a> | 
<a href="http://beyoung.in/designer-t-shirts">Designer T-Shirts</a> | 
<a href="http://beyoung.in/black-t-shirts">Black T-Shirts</a> | 
<a href="http://beyoung.in/white-t-shirts">White T-Shirts</a> | 
<a href="http://beyoung.in/fashion-t-shirts">Fashion T-Shirts</a> | 
<a href="http://beyoung.in/quirky-t-shirts">Quirky T-Shirts</a> | 
<a href="http://beyoung.in/latest-t-shirts">Latest T-Shirts</a> | 
<a href="http://beyoung.in/stylish-t-shirts">Stylish T-Shirts</a> |
<a href="http://beyoung.in/oversize-t-shirts">Oversize T-Shirts</a> | 
<a href="http://beyoung.in/sleeveless-t-shirt">Sleeveless T-Shirts</a> 

</div>

<div class="footer_delever" style="padding-top: 30px;"><strong style="font-size: 16px;">We Deliver in</strong> <br> Ahmedabad| Bangalore| Bhopal| Chandigarh| Chennai| Coimbatore| Faridabad| Ghaziabad| Goa| Gurgaon| Hyderabad| Indore| Jaipur| Jodhpur| Kochi| Kolkata| Lucknow| Ludhiana| Mumbai| Nagpur| Navi Mumbai| New Delhi| Noida| Pimpri Chinchwad| Pune| Secunderabad| Surat| Udaipur| Thane|Tirupur | Vadodara &amp; Across India.</div>
				
				
				
				
				
				</div>
			</div>
			<div class="copybotm">Copyright © 2018 Beyoung. All rights reserved.</div>
	
	</div>
	
	</div>
		<style>
		img.logo.dark, img.custom-logo{max-height:40px !important;}
.blgftmenu{margin:0px; padding:0px;}
.blgftmenu li{list-style:none; display:block; padding:5px 0px;}
.blgftmenu li a{color: #434343; font-size: 14px; text-decoration:none;transition: all 0.5s; -webkit-transition: all 0.5s; -moz-transition: all 0.5s; -o-transition: all 0.5s;-ms-transition: all 0.5s; position:relative;}
.blgftmenu li a:hover{color: #000 !important; padding-left: 15px;}
.blgftmenu li a:hover::before { position: absolute;left: 0px;content: '\25ba';font-size: 12px;color: #666; margin-top: 0px;}
.blgftmenu a{color: #434343; font-size: 14px;}
.blgftmenu li span{color: #434343; font-size: 14px;}
.foottxt{color: #434343; font-size: 16px;}
.fttbxc{width:100%; float:left; margin:20px 0px;}
#wcus_div {display: block !important; color:#333;}
.footer_delever a{font-size:16px;color:#333; text-decoration:none;}
.gridContainer {max-width: 100% !important;}
.copybotm{width:100%; float:left; font-size:12px; color:#333;}
ul.dropdown-menu>li>a{color:#000;font-family:'montserratmedium' !important; text-transform:uppercase; letter-spacing:1px; font-size:14px;}
.navigation-bar.bordered{background:#FFF; padding-top:0px !important; padding-bottom:0px !important;}
ul.dropdown-menu li{padding:1rem .85rem;}
ul.dropdown-menu li a{text-transform:uppercase; color:#333;}
ul.dropdown-menu ul li{padding:1.5rem 1.5rem;}
ul.dropdown-menu li ul{max-height: 400px;overflow: auto !important;}
ul.dropdown-menu.active-line-bottom>li:not(.current-menu-item):not(.current_page_item).hover>a, ul.dropdown-menu.active-line-bottom>li:not(.current-menu-item):not(.current_page_item):hover>a, ul.dropdown-menu.active-line-top>li:not(.current-menu-item):not(.current_page_item).hover>a, ul.dropdown-menu.active-line-top>li:not(.current-menu-item):not(.current_page_item):hover>a, ul.dropdown-menu.default>li:not(.current-menu-item):not(.current_page_item).hover>a, ul.dropdown-menu.default>li:not(.current-menu-item):not(.current_page_item):hover>a{color:#333 !important;}
ul.dropdown-menu li.menu-item-has-children>a:after, ul.dropdown-menu li.page_item_has_children>a:after{display:none;}
.tpbrbx{width:100%; float:left; margin:10px 0px;}
.tpbrbx ul{margin:0px; padding:0px;}
.tpbrbx ul li{list-style:none; display:inline; float:left; padding-right:20px;}
.tpbrbx ul li a{ font-size:12px; color:#000; text-decoration:none; font-family:'montserratmedium' !important; text-transform:uppercase; letter-spacing:1px;}
.navigation-bar.bordered::before{    content: ""; border-bottom:solid 1px #CCC; position:absolute; top:40px; width:100%;}
.trightm a:before {content: "\f0d1" !important;font-size: 14px;
    margin-right: 10px;    display: inline-block;font-family: FontAwesome; font-style: normal; font-weight: normal;line-height: 1;-webkit-font-smoothing: antialiased;}
.trightm{float:right !important; padding-right:0px !important;}
.main_menu_col { justify-content: flex-start !important;}
ul.dropdown-menu.active-line-bottom > .current-menu-item > a, ul.dropdown-menu.active-line-bottom > .current_page_item > a, ul.dropdown-menu.default > .current-menu-item > a, ul.dropdown-menu.default > .current_page_item > a{border-bottom:none !important;}
ul.dropdown-menu > li.hover > a, ul.dropdown-menu > li:hover > a{color:#333 !important;}
#offcanvas-wrapper{background-color:#FFF !important;}
.offcanvas_menu li{color:#000 !important;}
.offcanvas_menu li.open > a, .offcanvas_menu li.open > a:hover {background-color: #eee !important;color: #000 !important; border-left: 3px solid #000 !important;}
.offcanvas_menu li.open.menu-item-has-children > a .arrow, .offcanvas_menu li.open.page_item_has_children > a .arrow{color:#000 !important;}

@media only screen and (max-width:767px){
.navigation-bar.bordered::before{top:60px !important;}
.tpbrbx ul li a{font-size:10px !important;}	
	
}
</style>
<?php if ( is_singular() ) { ?>
       <script>
          jQuery(document).ready(function($) {
              jQuery('.loop').owlCarousel({
                
                items: 3,
                 loop:false,
				 margin:10,
                autoplay:true,
                 nav:true,
                responsive: {
		                   0:{
		            items:1
		        },
		        600:{
		            items:3
		        },
		        1000:{
		            items:3
		        }
                }
              }); 
              });
              </script>
              <?php } ?>
<?php wp_footer(); ?>
</body>
</html>