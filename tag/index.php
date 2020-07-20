<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
 
// Parameters
// Defaults:
$sort = "popular";
$search = "all";
$category = "all";
$author = "all";

$slug1='';
$slug2='';
$category='';
$category2='';



// Get params (if they were passed)
$none_set = true;
if (isset($_GET["o"]) && trim($_GET["o"])){
    $sort = $_GET["order"];
    $none_set = false;
}
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

$canonical = "https://texturehaven.com/textures/?";
$canonical .= "c=".$category;
if ($author != "all"){
    $canonical .= "&a=".$author;
}
if ($search != "all"){
    $canonical .= "&s=".$search;
}
include_start_html("MAINTEXTURES",$slug=$category);
include_start_html("Textures: ".nice_name($category, "category"), "", $canonical, "");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');

$conn = db_conn_read_write();

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
            echo "Category: ".nice_name($category, "category");
         }
        
    }
    if ($author != "all") {
        echo " by ".$author;
    }
    echo "</h1>";

    include ($_SERVER['DOCUMENT_ROOT'].'/textures/grid_options.php');

    echo "</div>";  // .title-bar

    echo "<div id='item-grid'>";
    
   
       echo make_item_grid_ba_tag($_GET['tag']);
 
    
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
