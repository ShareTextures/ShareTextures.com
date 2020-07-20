<?php

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

$textures = getLatestTexturesCNOTPATRON(10000);

foreach ($textures as $t){
    
    echo "https://".$_SERVER['HTTP_HOST']."/textures/".$t['slug'].",";
    
}

?>