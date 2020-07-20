<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

$blog = getBlogbySlug('blog/'.$_REQUEST['blogurl'].'/');
generate_texture_meta($blog);

include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
 
?>

<div id="sidebar-toggle"><i class="material-icons">apps</i></div>

<?php
        
    echo "<div id='item-grid'><div class='segment-inner'>";
    echo "<h1>  <a href='".$blog['slug']."'>".$blog['name'];
    echo "</a></h1>";

?>
        <div class="grid-item-grid grid-item">
            <div class="page-wrapper">
    <?php 
    $a =  preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"100%\" height=\"600px\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>", $blog['blogtext']);
    $b = add_nofollow_content($a);
    echo $b;
    ?>
            </div>
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
	
<script type="application/ld+json">
{ "@context": "https://schema.org", 
 "@type": "Article",
 "headline": "<?php echo $blog['name']; ?>",
 "image": "http://www.sharetextures.com/<?php echo $blog['image_featured']; ?>",
 "author": "Share Textures", 
 "keywords": "<?php echo $blog['tags']; ?>", 
"publisher": {
    "@type": "Organization",
    "name": "ShareTextures",
    "logo": {
      "@type": "ImageObject",
      "url": "https://www.sharetextures.com/logo-hover.png"
    }
  },
 "url": "http://www.sharetextures.com/<?php echo $blog['slug']; ?>",
   "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "http://www.sharetextures.com/<?php echo $blog['slug']; ?>"
  },
 "datePublished": "<?php echo $blog['date_published']; ?>",
 "dateModified": "<?php echo $blog['date_published']; ?>",
 "description": "<?php echo $blog['yoast_metadesc']; ?>"
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
                },{
                    "@type": "ListItem",
                    "position":3,
                    "item": {
                        "@id": "http://www.sharetextures.com/<?php echo $blog['slug']; ?>",
                        "name": "<?php echo $blog['name']; ?>"
                    }
                }]}</script>
			
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
 
    echo "</div></div>";
  
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
