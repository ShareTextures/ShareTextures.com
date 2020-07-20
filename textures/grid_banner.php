			<?php
    $items = getLatestTextures();
 

    echo "<div id='item-grid'>";
    
    
    foreach ($items as $item) {
        ?>
        <a href="<?php echo $item['slug'] ?>">
            <div class="grid-item grid-item-5">
                <div class="thumbnail-wrapper">
                    <img class="thumbnail-proxy" src="//<?php echo $_SERVER['HTTP_HOST'] ?>/<?php echo $item['image_featured']  ?>" datat-src="//<?php echo $_SERVER['HTTP_HOST'] ?>/<?php echo $item['image_featured_second'] ?>" loading="lazy">
                </div>
                
                <div class="description-wrapper">
                    <div class="description">
                        <div class="title-line"><h3><?php echo $item['name'] ?></h3></div>
                        <p class="age">2 years ago</p>
                    </div>
                </div>
            </div>
        </a>
        <?php        
        
    }
    
    
    echo "</div>";
?>
 
