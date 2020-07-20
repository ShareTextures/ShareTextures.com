<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("SEARCH");
// Parameters
// Defaults:
$sort = "popular";
 


$slug1='';
$slug2='';
$category='';
$category2='';



// Get params (if they were passed)
$none_set = true;
if (isset($_POST["o"]) && trim($_POST["o"])){
    $sort = $_POST["o"];
    $none_set = false;
}
if (isset($_POST["s"]) && trim($_POST["s"])){
    $search = $_POST["s"]; 
    $none_set = false;
}
if (isset($_POST["c"]) && trim($_POST["c"])){
    $category = $_POST["c"];
    $none_set = false;
}
if (isset($_POST["c2"]) && trim($_POST["c2"])){
    $category2 = $_POST["c2"];
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

 
if (isset($_GET["search"]) && trim($_GET["search"])){
$search = $_GET["search"];
$none_set = false;
}

$sort = htmlspecialchars($sort);
$search = htmlspecialchars($search);
$category = htmlspecialchars($category);
$category2 = htmlspecialchars($category2);

 

$canonical = "https://www.sharetextures.com/search/index.php?";
$canonical .= "c=".$category;
if ($author != "all"){
    $canonical .= "&a=".$author;
}
if ($search != "all"){
    $canonical .= "&s=".$search;
}
include_start_html("Textures: ".nice_name($category, "category"), "", $canonical, "");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');

$conn = db_conn_read_write();

 track_search($search, $category, $reuse_conn=NULL)
?>

<div id="sidebar-toggle"><i class="material-icons">apps</i></div>

<div id="sidebar">
    <div class="sidebar-inner">
        <h3>Categories</h3>
        <?php
        make_category_list_ba($sort, $conn, $category,$category2, true);
        $settings = getGeneralSettings();
         
        echo $settings['left_adsense']; 
        ?>
    </div>
</div>

<div id="item-grid-wrapper">
    <?php

    echo "<div class='title-bar'>";
  


    include ($_SERVER['DOCUMENT_ROOT'].'/textures/grid_options.php');

    echo "</div>";  // .title-bar

    echo "<div id='item-grid'>";
    
    if ($none_set){
       echo make_item_grid_ba_anasayfa();
    } else {
        echo make_item_grid_search_ba($sort, $search, $category, $category2,$mainpage);
    }
    
   
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
