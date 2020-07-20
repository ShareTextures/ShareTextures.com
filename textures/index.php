<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

// Parameters
// Defaults:
$sort = "date_published";
$search = "all";
$category = "all";
$author = "all";

$slug1='';
$slug2='';
$category='';
$category2='';

?>
<?php

$none_set = true;
$sort = $_REQUEST["orderby"];

if (isset($_GET["s"]) && trim($_GET["s"])){
    $search = $_GET["s"];
    $none_set = false;
}
if (isset($_GET["c"]) && trim($_GET["c"])){
    $category = $_GET["c"];
    $none_set = false;
}
if (isset($_GET["c2"]) && trim($_GET["c2"])){
    $category2 = $_GET["c2"];
    $none_set = false;
}
if (isset($_GET["a"]) && trim($_GET["a"])){
    $author = $_GET["a"];
    $none_set = false;
}

if (isset($_GET["slug1"]) && trim($_GET["slug1"])){
    $slug1 = $_GET["slug1"];
    $none_set = false;
}

if (isset($_GET["slug2"]) && trim($_GET["slug2"])){
    $slug2 = $_GET["slug2"];
    $none_set = false;
}

if(isset($_GET["singlecat"]) && trim($_GET["singlecat"])) {
    $slug = $_GET['singlecat'];
} else {
    $slug = $slug1.'/'.$slug2;
}

//var_dump($_GET);

$sort = htmlspecialchars($sort);
$search = htmlspecialchars($search);
$category = htmlspecialchars($category);
$category2 = htmlspecialchars($category2);
$author = htmlspecialchars($author);


$category=$slug1;
$category2=$slug2;

if($slug1=='index.php') {
    $none_set='true';
}

$canonical = "https://www.sharetextures.com/textures/?";
$canonical .= "c=".$category;
if ($author != "all"){
    $canonical .= "&a=".$author;
}
if ($search != "all"){
    $canonical .= "&s=".$search;
}
include_start_html("MAINTEXTURES",$slug=$category);

include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');

$conn = db_conn_read_write();

 track_search($search, $category, $reuse_conn=NULL)
?>

<div id="sidebar-toggle"><i class="material-icons">apps</i></div>

<div id="sidebar">
    <div class="sidebar-inner">
          <div class='social'>
        <a href="https://www.facebook.com/sharetextures"><img src="/files/site_images/icons/facebook.svg" width="35px"></a>
        <a href="http://twitter.com/sharetextures"><img src="/files/site_images/icons/twitter.svg"width="35px"></a>
		<a href="http://youtube.com/sharetextures"><img src="/files/site_images/icons/youtube-footer.svg"width="35px"></a>
		<a href="http://patreon.com/sharetextures"><img src="/files/site_images/icons/patreon-footer.svg"width="35px"></a>
    </div>
		 <?php
             
        $settings = getGeneralSettings();
         
        echo $settings['left_adsense']; 

        ?>
		<h3>Categories</h3>
        <?php
        make_category_list_ba($sort, $conn, $category,$category2, true);
        
        $settings = getGeneralSettings();
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
                        "@id": "http://www.sharetextures.com/textures/",
                        "name": "Textures"
                    }
                },
				{
                    "@type": "ListItem",
                    "position":3,
                    "item": {
                        "@id": "http://www.sharetextures.com/textures/<?php echo $slug1; ?>",
                        "name": "<?php echo $slug1; ?>"
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
			
<div id='grid-banner'>
    <div class='segment-a'>
		<div class='segment-inner'>
		<div class='col-3'>
                <h2><strong><?php echo countFreeTextures() ?></strong> Free Textures</h2>
                <p>No Copyright, created for you. Use them wherever you want ! </p>
            </div> 
            <div class='col-3'>
			 <a href="https://www.patreon.com/join/sharetextures" target="_blank">
                <h2>Get more!</h2>
                <p>Become a patron and get <strong><?php echo countPonlyTextures() ?></strong> Premium Textures and <strong><?php echo countPonlypTextures() ?></strong> Texture Sets</p>
				</a> 
            </div> 
		<div class='col-3'>
			 <a href="https://www.patreon.com/join/sharetextures" target="_blank">
                <h2>Join our <strong><?php echo sizeof($GLOBALS['PATRON_LIST']) ?></strong> Patrons</h2>
                <p>Creating free textures is only possible with your support</p>
				</a>
            </div>	
		</div>
	</div> 
</div>
<div id="item-grid-wrapper">
    <?php

    echo "<div class='title-bar'>";
    echo "<h1>";
    if ($search != "all") {
        echo "Search: \"".$search."\"";
        if ($category != "all") {
            echo " in category: ".nice_name($category, "category");
        }
    }else if ($category == "all"){
        echo "All Textures";
    }else{
         if ($none_set){ 
             
         } else {
             if($slug1=='patreon') {
                 
                 echo "Category: ". str_replace('-',' ',ucfirst($slug2));
             }
             else {
                 echo "Category: ".nice_name($category, "category");
             }
            
         }
        
    }
    if ($author != "all") {
        echo " by ".$author;
    }



    if ($none_set){
       echo 'Latest Free Textures';
    } 
       
    echo "</h1>";

    include ($_SERVER['DOCUMENT_ROOT'].'/textures/grid_options.php');

    echo "</div>";  // .title-bar

    echo "<div id='item-grid'>";
    
    if ($none_set){
       echo make_item_grid_ba_anasayfa($sort);
    } else {
        if($slug1=='patreon') {
            $ispatreon=1;
            $patreon_level=$slug2;
        } else {
            $ispatreon=0;
        }
        echo make_item_grid_ba($sort, $search, $category, $category2,$author, $conn, 0,$ispatreon,$patreon_level);
    }
    
    echo "</div>"
    ?>
</div>
<script type="text/javascript">

$(document).ready(function(){

   $('.grid-item').mouseenter(function(){
       
       var t = $(this).find('img').attr('datat-src');
       var n = $(this).find('img').attr('src');
       
       if(t.length>25) {
           $(this).find('img').attr('src',t);
           $(this).find('img').attr('datat-src',n);
       }
       console.log(t);
   });
 
   $('.grid-item').mouseleave(function(){
       
       var t = $(this).find('img').attr('datat-src');
       var n = $(this).find('img').attr('src');
       
       if(t.length>25) {
           $(this).find('img').attr('src',t);
           $(this).find('img').attr('datat-src',n);
       }
       
   });
   
   
});

</script>


<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
