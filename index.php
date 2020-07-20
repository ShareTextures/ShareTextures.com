<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("MAIN");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
$last_blogs = getLatestBlogs(9);
$last_textures=getLatestTexturesCANA(9);
$last_patreons_super = getLatestPatreons(9);
$last_patreons = getLatestPatreonsSUPER(9);
?>
<link href='/css/carousel.css' rel='stylesheet' type='text/css' />
<link href='/css/carousel_theme.css' rel='stylesheet' type='text/css' />
<script src="/js/landing-slider.js" async></script>	
<script src="/js/carousel.js"></script>

<div id='landing-banner-wrapper'>
    <div id='banner-img-a'>
        <div class='banner-img-credit'>Render by <a href="http://www.ozgenkaragol.com/" rel="nofollow noopener nofollow" target="_blank">Ozgen Karagol</a></div>
		 <div class='banner-img-patron'>Become a patron and show your render <a href="https://www.patreon.com/join/sharetextures" rel="nofollow noopener nofollow" target="_blank">[Texture King]</a></div>
    </div>
    <div id='banner-img-b' class='hide'>
        <div class='banner-img-credit'></a></div>
    </div>
    <div id='banner-img-paddle-l' class='banner-img-paddle'><i class="material-icons">keyboard_arrow_left</i></div>
    <div id='banner-img-paddle-r' class='banner-img-paddle'><i class="material-icons">keyboard_arrow_right</i></div>


    <div id='banner-title-wrapper'>
        <img src="files/site_images/sharetexture.png" id="banner-logo" />
        <p>Free PBR Textures</p>
		 <div class="col-1">
                    <div class="search-form">
                            <form action="//<?php echo $_SERVER['HTTP_HOST'] ?>/search/index.php" id="searchform" method="post">
                                    <input name="search" type="text" placeholder="Find Textures"><button type="submit">Search</button>
                                    <input type="hidden" name="o" value="popular"><input type="hidden" name="c" value=""><input type="hidden" name="c2" value="">
                                    <input type="hidden" name="mainpage" value="yes">
                            </form>
                    </div>
                </div>  
				
				<div class="segment-c">
            <div class="segment-inner">
            
                
                <div class="col-3">
                <div class="button-red">
                    <a style="color:white" href="//<?php echo $_SERVER['HTTP_HOST'] ?>/textures/">Browse Textures</a>
            </div>
                </div> </div> 
            
            
            
            </div>
        
		
    </div>
</div> 
<div id='landing-page'>

    <div class="segment-b">
      
        
        <h1>Latest Free Textures</h1>
		<strong><?php echo countFreeTextures() ?></strong> free textures and increasing every day
        
                    <div class="owl-carousel" id="owl2">
                        <?php
                        foreach ($last_textures as $last_texture) {
                            if($last_texture['image_thumb_300']!=""){
                                echo '<div><a title="'.$last_texture['name'].'" class="group" href="/textures/'.$last_texture['slug'].'"><img src="/'.$last_texture['image_thumb_300'].'" width="216px"></a></div>';
                            }else{
                                echo '<div><a title="'.$last_texture['name'].'" class="group" href="/textures/'.$last_texture['slug'].'"><img src="/'.$last_texture['image_featured'].'" width="216px"></a></div>';
                            }
                        }
                       ?>
                    </div>        

 
        </div>
    </div>

    <div class="segment-a">
        <div class="segment-inner">

         <!--   <h1>Supported by you<img src="/files/site_images/icons/heart.svg" class='heart'></h1>-->
            <div class="col-3">
                <h2 class="patreon-stat" id="patreon-num-patrons"><?php echo sizeof($GLOBALS['PATRON_LIST']) ?> patrons</h2>
            </div>
            
            <div class="col-3">
                <h2 class="patreon-stat" id="patreon-income"><strong><?php echo countTextures() ?></strong> textures</h2>
            </div>
            
            <div class="col-3">
                <h2 class="patreon-stat" id="patreon-income">$<?php echo $GLOBALS['PATREON_EARNINGS'] ?> per month</h2>
            </div>

            <div class='patreon-bar-wrapper'>
                <div class="patreon-bar-outer">
                    <div class="patreon-bar-inner-wrapper">
                        <div class="patreon-bar-inner" style="width: <?php echo $GLOBALS['PATREON_CURRENT_GOAL']['completed_percentage'] ?>%"></div>
                    </div>
                </div>
                <div class="patreon-current-goal">Current goal: <b><?php
                    echo goal_title($GLOBALS['PATREON_CURRENT_GOAL']);
                    echo " ($";
                    echo $GLOBALS['PATREON_CURRENT_GOAL']['amount_cents']/100;
                    echo ")";
                ?></b><!--<i class="material-icons hide-mobile">arrow_upward</i>--></div>
            </div>

     

            <a href="https://www.patreon.com/sharetextures/overview" target="_blank">
                <div class='button-inline'>Read More / Become a Patron</div>
            </a>
        </div>
    </div>




<div class="segment-b">
    
    <div class="segment-inner">
        
        <h1 class="mavbaslik">Patron Tier's and Rewards</h1>
        
            <div class="col-3">
                
                <h1 class="mavh1">Specialist</h1>
                  <img class="mt1" src="files/site_images/specialist.png" />
                <h1 class="mavh1">Get Google Drive Access</h1>
                <p class="ktu">
        Our supporters will be awarded Google-Drive access to download all textures easily.
        Also, they will reach unpublished textures early.
                </p>
        </div>


            <div class="col-3">
                
                <h1 class="mavh1">Master</h1>
                 <img class="mt1"  src="files/site_images/master.png" />
       <h1 class="mavh1">Open Patron-Exclusive Textures </h1>
        <p class="ktu">
        When you become a patron as Master tier, you will be awarded <b>Patron-Exclusive</b> texture sets which is 150+ exclusive textures and rewards of <b>previous tiers</b>.
        </p>
        </div>

            <div class="col-3">
                <h1 class="mavh1">Knight</h1>
                <img class="mt1"  src="files/site_images/knight.png" />
       <h1 class="mavh1">Get Vray, Lumion Material Library  </h1>
        <p class="ktu">
        You can reach Real Texture sets which is include minimum 25 texture inside and they are from real manufacturers.
         Also, you can download Vray, Lumion ready to render material library. 
        </p>
    
    </div>
    
        
        
</div>
    
    <!--
    
    <div class="elementor-shape elementor-shape-bottom" data-negative="false">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path class="elementor-shape-fill" opacity="0.33" d="M473,67.3c-203.9,88.3-263.1-34-320.3,0C66,119.1,0,59.7,0,59.7V0h1000v59.7 c0,0-62.1,26.1-94.9,29.3c-32.8,3.3-62.8-12.3-75.8-22.1C806,49.6,745.3,8.7,694.9,4.7S492.4,59,473,67.3z"></path><path class="elementor-shape-fill" opacity="0.66" d="M734,67.3c-45.5,0-77.2-23.2-129.1-39.1c-28.6-8.7-150.3-10.1-254,39.1 s-91.7-34.4-149.2,0C115.7,118.3,0,39.8,0,39.8V0h1000v36.5c0,0-28.2-18.5-92.1-18.5C810.2,18.1,775.7,67.3,734,67.3z"></path><path class="elementor-shape-fill" d="M766.1,28.9c-200-57.5-266,65.5-395.1,19.5C242,1.8,242,5.4,184.8,20.6C128,35.8,132.3,44.9,89.9,52.5C28.6,63.7,0,0,0,0 h1000c0,0-9.9,40.9-83.6,48.1S829.6,47,766.1,28.9z"></path></svg></div>
                        
    -->
    
</div>




  <div class="segment-b">
         <h1>Patron-Exclusive Texture Packages</h1>
         <h3><b>Available Tiers:</b> Texture Knight, Texture King</h3>
            <div class="owl-carousel" id="owl3">
                <?php
                foreach ($last_patreons as $last_texture) {
                    echo '<div><a title="'.$last_texture['name'].'" class="group" href="/textures/'.$last_texture['slug'].'"><img src="/'.$last_texture['image_featured'].'" width="216px"></a></div>';
                }
               ?>
            </div> 
</div>

  <div class="segment-b">

        <h1>Patron-Exclusive Textures</h1>
        <h3><b>Available Tiers:</b> Texture Master, Texture Knight, Texture King</h3>

            <div class="owl-carousel" id="owl4">
                <?php
                foreach ($last_patreons_super as $last_texture) {
                    echo '<div><a title="'.$last_texture['name'].'" class="group" href="/textures/'.$last_texture['slug'].'"><img src="/'.$last_texture['image_featured'].'" width="216px"></a></div>';
                }
               ?>
            </div>  
  
  </div>


</div>  <!-- #landing-page -->

<script type="text/javascript">

    $(document).ready(function() {
       
       
           
        $('.owl-carousel').owlCarousel({
            loop:true,
            margin:20,
            responsiveClass:true,
                autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:5,
                    nav:false
                },
                1000:{
                    items:8,
                    nav:true,
                    loop:false
                }
            }
        })


        $('#owl2').owlCarousel({
            loop:true,
            margin:20,
            responsiveClass:true,
                autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:5,
                    nav:false
                },
                1000:{
                    items:8,
                    nav:true,
                    loop:false
                }
            }
        })
 
 
        $('#owl3').owlCarousel({
            loop:true,
            margin:20,
            responsiveClass:true,
                autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:5,
                    nav:false
                },
                1000:{
                    items:8,
                    nav:true,
                    loop:false
                }
            }
        })
         $('#owl4').owlCarousel({
            loop:true,
            margin:20,
            responsiveClass:true,
                autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:true,
            responsive:{
                0:{
                    items:1,
                    nav:true
                },
                600:{
                    items:5,
                    nav:false
                },
                1000:{
                    items:8,
                    nav:true,
                    loop:false
                }
            }
        })       
    });



</script>
<script type="application/ld+json">
{
    "@context" : "http://schema.org",
    "@type" : "Organization",
    "legalName" : "ShareTextures.com",
	"slogan" : "Free PBR and CC0 Textures ",
    "foundingDate" : "2018-10-01",
    "url" : "https://www.sharetextures.com/",
    "contactPoint" : [{
        "@type" : "ContactPoint",
        "email" : "info@sharetextures.com",
        "contactType" : "e-mail",
        "url" : "https://www.sharetextures.com/about-2/"
    }],
    "logo" : "https://www.sharetextures.com/logo-hover.png",
    "sameAs" : [
    	"https://www.facebook.com/sharetextures",
		"https://www.patreon.com/sharetextures",
		"https://www.twitter.com/sharetextures",
		"https://www.youtube.com/channel/UCAZr-dcqxnZo_7Iu991AnMw",
		"https://www.reddit.com/user/ShareTexture"    	
    ]
}
</script>

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "url": "https://www.sharetextures.com/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://www.sharetextures.com/search/index.php?search={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
