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



<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
