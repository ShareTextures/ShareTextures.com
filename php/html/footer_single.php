</div>  <!-- #push-footer -->
<div id='footer'>

    <div class='footer-patrons'>
        <h2>Patrons</h2>
        <div class="patron-list">
            <?php
            foreach ($GLOBALS['PATRON_LIST'] as $p){
                echo "<span class='patron patron-rank-".$p[1]."'>".$p[0]."</span> ";
            }
            ?>
        </div>
        <a href="https://www.patreon.com/sharetextures">
            <div class="button-red">
                Join Patrons, Support ShareTextures
            </div>
        </a>
    </div>



<?php
if (strlen($_SERVER['REQUEST_URI'])<3){
?>
<div id='landing-page'>

    <div class="segment-b">
        
        <h1>Latest Blogs</h1>
        
                    <div class="owl-carousel">
                        <?php
                        foreach ($last_blogs as $last_blog) {
                            echo '<div><a title="'.$last_blog['name'].'" class="group" href="'.$last_blog['slug'].'"><img src="/'.$last_blog['image_featured'].'"></a></div>';
                        }
                       ?>
                    </div>

        
        
       

 
        
        </div>
    </div>
<?php } ?>


    <div class='social'>
        <a href="https://www.facebook.com/sharetextures"><img src="/files/site_images/icons/facebook.svg"></a>
        <a href="http://twitter.com/sharetextures"><img src="/files/site_images/icons/twitter.svg"></a>
    </div>

    <ul class='footer-links'>
        <li><a href="//<?php echo $_SERVER['HTTP_HOST'] ?>/">Home</a></li>
        <li><a href="//<?php echo $_SERVER['HTTP_HOST'] ?>/textures">Textures</a></li>
        <li><a href="//<?php echo $_SERVER['HTTP_HOST'] ?>/blog">Blog</a></li>
        <li><a href="//<?php echo $_SERVER['HTTP_HOST'] ?>/privacy/">Privacy</a></li>
    </ul>



<?php
if (strlen($_SERVER['REQUEST_URI'])<3){
?>
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
<?php
}
?>

</div>
