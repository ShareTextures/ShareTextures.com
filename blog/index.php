<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("MAINBLOG");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');

$blogs = getAllBlogs();
 
?>

<div id="sidebar-toggle"><i class="material-icons">apps</i></div>



<div id="item-grid-wrapper">
    <?php
    
    
    
    echo "<div class='title-bar'>";
    echo "<h1>Blog";

    echo "</h1>";

  
    echo "</div>";  // .title-bar
    
    
        
    echo "<div id='item-grid'>";
    
    foreach ($blogs as $blog):
?>
 
    <a href="/<?php echo $blog['slug'] ?>">
        <div class="grid-item-grid grid-item grid-item-5">
            <div class="thumbnail-wrapper">
                <img class="thumbnail-proxy" src="//<?php echo $_SERVER['HTTP_HOST'].'/'.$blog['image_featured'] ?>">
            </div>
      
           <!-- <p class="blogdesc"><?php // echo truncateString(strip_tags($blog['blogtext']), 170,true)?> </p> -->
        </div>
    </a>
 
    
    
  <?php
  endforeach;
    echo "</div>";
  
    ?>
</div>

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


<script type="application/ld+json">{"@context": "http://schema.org",
                            "@type": "BreadcrumbList",
                            "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "item": {
                    "@id": "https://www.sharetextures.com",
                    "name": "Homepage"
                }
            },{
                    "@type": "ListItem",
                    "position":2,
                    "item": {
                        "@id": "http://www.sharetextures.com/blog/",
                        "name": "Blog"
                    }
                }]}</script>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
