<?php
session_start();

$patreon_seviyem = intval($_SESSION['para2'])/100;

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>
<?php
// Parameters
// Defaults:
$slug = "none";
$category = "all";

$slug1 = trim($_GET['slug1']);
$slug2 = trim($_GET['slug2']);

// Get setttings

$settings = $db->selectRow('select * from general limit 1');
 


//die();
// Get params (if they were passed)
/*
if (isset($_GET["t"]) && trim($_GET["t"])){
    $slug = $_GET["t"];
}
if (isset($_GET["c"]) && trim($_GET["c"])){
    $category = $_GET["c"];
}

// Redirect if parameters not received
if (empty($_GET["t"])){
    header("Location: /textures/");
}

$slug = htmlspecialchars($slug);
$category = htmlspecialchars($category);
*/


/*
 * What if its a category??
 * Determine if its a category or a post first!
 * 
 */


if(strlen($slug1)>1 && strlen($slug2)>1) {
    $slug = $slug1.'/'.$slug2.'/';

    
    $info = $db->selectRow(
    'SELECT * FROM textures WHERE slug=?',    
    [$slug]); 

    if(is_null($info)) {
        // aha, category!
        header("Location: /textures/?s=".$slug);
    }
}


// GET GALLERY

$gallery_images = $db->select(
    'SELECT * FROM gallery WHERE post_id=?',    
    [$info['id']]); 

    
/*

if(strlen($slug1)>1 && strlen($slug2)>1) {

$conn = db_conn_read_only();
$info = get_item_from_db($slug1.'/'.$slug2.'/', $conn);    

}

*/


// Redirect to search if the texture is not in the DB.
//if (sizeof($info) <= 1){
  //  header("Location: /textures/?s=".$slug);
//}

$canonical = "https://texturehaven.com/tex/?t=".$slug;
$t1 = [];
$t1 ['name'] = $info['name'];
$t1 ['date_published'] = $info['date_published'];
$t1 ['author'] = $info['author'];
$category_arr = explode(';', $info['categories']);
$tag_arr = explode(';', $info['tags']);
$tags = array_merge($category_arr, $tag_arr);
$t1 ['tags'] = implode(',', array_merge($category_arr, $tag_arr));

generate_texture_meta($info);

//include_start_html($info['name'], $slug, $canonical, $t1);
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');

echo "<div id='item-page'>";
echo "<div id='page-wrapper'>";

echo "<h1>";
echo "<a href='/textures/'>";
echo "Textures";
echo "</a>";
echo " >";
 
    
    $cats = explode(',',$info['real_category']);
    
    foreach ($cats as $cat){
    $catz.='/'.to_slug2(lcfirst($cat));
    echo " ";
    echo "<a href='/textures".$catz."'/>";
    echo nice_name($cat, 'category');

    echo "</a>";
    echo " >";
    }
echo "<br><b>{$info['name']}</b></h1>";


$is_published = is_in_the_past($info['date_published']) || $GLOBALS['WORKING_LOCALLY'];
if ($is_published){
   
    echo "<div id='preview-download'>";

    echo "<img id='mainImg' data-src='//".$_SERVER['HTTP_HOST']."/".$info['image_featured']."' data-replace='//".$_SERVER['HTTP_HOST']."/".$info['image_featured_second']."' src='//".$_SERVER['HTTP_HOST']."/".$info['image_featured']."' width='672' height='672' ";
  

    echo "<div class='download-buttons'>";
   // echo "<h2><a target='_blank' href='".$info['download']."'>Download:</a></h2>";

    echo "</div>";  // #preview-download
    
    
    
/*reklam 

*/

        $settings = getGeneralSettings();
         
        echo $settings['g_bottom_adsense']; 



    /*
     * GALLERY
     * 
     */
    
    echo '<div class="owl-carousel">';
    
    $i=0;

    foreach ($gallery_images as $gallery_image) {
        
        //if($i>0):
            echo '<div><a title="'.$gallery_image['image_caption'].'" class="group" href="/'.$gallery_image['image_url'].'"><img src="/'.$gallery_image['image_url'].'"></a></div>';
       // endif;

       // $i++;

    }
    
    echo '</div>';


    if ($GLOBALS['WORKING_LOCALLY'] && is_in_the_past($info['date_published']) == False){
        echo "<p style='text-align:center;opacity:0.5;'>(working locally on a yet-to-be-published texture)</p>";
    }
    
    
    // DOWNLOAD
    echo "<div id='item-info' style='text-align:center;font-size: 32px;font-weight: bold;'>";




    if($patreon_seviyem) {

              if($patreon_seviyem>=$info['patreon_level']) {
 
                // Seviye yeterli, download linki göster
                echo '<div class="wp-block-buttons aligncenter"><div class="wp-block-button"><a class="wp-block-button__link" target="_blank" href="'.$info['download'].'">Download</a></div>
              </div>';

              } else {


                // Seviye yetersiz; salaksın abi linki

                echo '<div class="wp-block-buttons aligncenter">
<div class="wp-block-button"><a class="wp-block-button__link" href="https://patreon.com/sharetextures" target="_blank" rel="noreferrer noopener nofollow external" data-wpel-link="external">Upgrade your tier and download</a></div>
</div>';


              }

 



    } else {


              if($info['patreon_level']<1) {

                // Uyeliksiz, direk download
                echo '<div data-fid="'.$info['id'].'" id="dl-btn" class="wp-block-buttons aligncenter"><div class="wp-block-button"><a class="wp-block-button__link" target="_blank" href="'.$info['download'].'">Download</a></div>
              </div>';

              } else {


                // Uyelik gerekli, oauth linki

$client_id = 'EzF5bCiUwB8p9wOS4CRGJQAqZ3hL4Gt5GGF-GMN_arbNCn4kDkjb66SsULXvEKT0';      
$client_secret = 'Fz0R60uuYRCiXeZm2qGjlbdN6qRLReLTZxGew9vE8DJ2oWsDp4Vd5p9Pe04cpG8y';   

$redirect_uri = "https://www.sharetextures.com/patreon_logged_ok_v1.php";
                $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

                $state = array('comeback'=> "//".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
                $state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );

                $href .= $state_parameters;

              //  $scope_parameters = '&scope=identity.memberships';

              //  $href .= $scope_parameters;

                echo '<div data-fid="'.$info['id'].'" id="dl-btn" class="wp-block-buttons aligncenter">
<div class="wp-block-button"><a class="wp-block-button__link" href="'.$href.'">Click here to login via Patreon</a></div>
</div>';

              } 



    }

   /*

  if($info['patreon_level']<1 || $patreon_seviyem>=$info['patreon_level']):


    echo 'patreon seviyem:'.$patreon_seviyem;
  echo 'post seviyesi:'.$info['patreon_level'];
 
     echo "<h2><a target='_blank' href='".$info['download']."'>Download</a></h2>";

   else:



    $client_id = '0sAg5WS76UxBeKxHVN2ibWLyPGaZFKi9m6yJ_aSmZX3bTWNHore-ioFCk01anGer';      
    $client_secret = 'xKq960Ur0ydgEKvEooApztPj8Mx2WaRSGn1JzmOKVkvu1Am7Yl3p6UfjM_R06muE';  

    $redirect_uri = "https://test.sharetextures.com/patreon_logged_ok.php";
    $href = 'https://www.patreon.com/oauth2/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

    $state = array('comeback'=> "//".$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]);
    $state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );

    $href .= $state_parameters;

    $scope_parameters = '&scope=identity.memberships';

    $href .= $scope_parameters;

    echo '<a href="'.$href.'">Click here to login via Patreon</a>';


   endif;



*/
    echo "</div>"; 
    
    
    
    echo "<div id='item-info'>";

    echo "<ul class='item-info-list'>";

    echo "<li title='Author: ".$info['author']."'>";
    echo "<b><i class='material-icons'>person</i></b> 
	
	<a>".$info['author']."</a>";
    echo "</li>";

    echo "<li title='Date published'>";
    echo "<b><i class='material-icons'>date_range</i></b> ".date("d F Y", strtotime($info['date_published']))." (".time_ago($info['date_published']).")";
    echo "</li>";

 
    echo "<b><i class='material-icons'>cloud_download</i></b> ".intval($info['download_count']);
    echo "</li>";

    echo "<br><li>";
    $category_str = "";
    $category_arr = explode(';', $info['real_category']);
    sort($category_arr);
    foreach ($category_arr as $category) {
        $category_str .= '<a href="/textures/'.$category.'">'.$category.'</a>, ';
    }
    $category_str = substr($category_str, 0, -2);  // Remove ", " at end
     echo "<b>Categories:</b> {$category_str}";
    echo "</li>";

    echo "<li>";
    $tag_str = "";
    $tag_arr = explode(',', $info['real_tags']);
    
    foreach ($tag_arr as $tag) {
        $tag_str .= '<a href="/tag/'.str_replace(' ', '-', $tag).'">'.$tag.'</a>, ';
      
    }
    $tag_str = rtrim($tag_str,' ,');
   //echo $tag_str;
   
   
    echo "<b>Tags:</b>".$tag_str;
    echo "</li>";
    echo "</ul>";

    echo "</div>";  // .item-info

    /*
     * 
     * SIMILAR TEXTURES
     * 
     */
    
    
    if($settings['similar']==1):
    
    $similar = get_similar($slug);
    if ($similar){
        echo "<h2>";
        echo "Similar Textures";
        echo "</h2>";
        echo "<div id='similar-items'>";
        echo "<div id='tex-grid'>";
        foreach ($similar as $s){
            echo make_grid_item($s);
        }
        echo "</div>";
        echo "</div>";
    }
    
    endif;
    
    /***
     * 
     * SIMILAR TEXTURES END
     * 
     */
 
    
    
    /*
     * ADSENSE SIMILAR ALTI
     *  
     * 
     */
    
    if(strlen(trim($settings['adsense']))>3):
     
        echo $settings['adsense'];
        
    endif;
    /*
     * 
     * ADSENSE SIMILAR ALTI END
     * 
     */
    

}else{
    echo "<h1 class='coming-soon'>Coming soon :)</h1>";
}

/*
TODO:
    User renders
*/
/*
if (!$GLOBALS['WORKING_LOCALLY']){
    echo "<hr class='disqus' />";
    include_disqus('tex_'.$slug);
}
*/
echo "</div>";  // #page-wrapper
echo "</div>";  // #item-page

?>

<div style="visibility:hidden" id="postid"><?php echo $info['id'] ?></div>

<script type="text/javascript">
$(document).ready(function(){
    
$('#item-info a').click(function() {
    $.ajax({
           type: "POST",
           url: 'dl_click.php',
           data: { 'id':$('#postid').html() }, // serializes the form's elements.
           success: function(data)
           {
               
           }
         });
});
    
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
            items:3,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }
    }
})

$(".group").colorbox({rel:'group', slideshow:true});



$("#mainImg").mouseover(function () {
    if($(this).data("replace").length>30) {
        $(this).attr('src', $(this).data("replace"));
    }
}).mouseout(function () {
    if($(this).data("replace").length>30) {
        $(this).attr('src', $(this).data("src"));
    }
    
});


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
<script type="application/ld+json">{
					"@context": "http://schema.org",
					"@type": "ImageObject",
					"author": "ShareTextures.com",
					"contentUrl": "http://www.sharetextures.com/<?php echo $info['image_featured']; ?>",
					"datePublished": "<?php echo $info['date_published']; ?>",
					"description": "PBR <?php echo $info['name']; ?> Textures, <?php echo $info['tags']; ?><?php echo $info['yoast_metadesc']; ?>",
					"name": "<?php echo $info['name']; ?>"
				}</script>
				
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
                },{
                    "@type": "ListItem",
                    "position":3,
                    "item": {
                        "@id": "http://www.sharetextures.com/textures/<?php echo $slug1; ?>",
                        "name": "<?php echo $slug1; ?>"
                    }
                },
				
			{
                "@type": "ListItem",
                "position": 4,
                "item": {
                    "@id": "https://www.sharetextures.com/textures/<?php echo $info['slug']; ?>",
                    "name": "<?php echo $info['name']; ?>"
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
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer_single.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');


?>
