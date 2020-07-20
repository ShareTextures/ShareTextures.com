<?php

// Track which texture map was downloaded, at what resolution and file format - for statistical purposes.
// IP addresses are stored (after obfuscation) so that we can count the number of unique downloads of a texture,
// ignoring the same person downloading different maps of the same texture

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

if(isset($_POST['id'])){
    echo 'kk:'.$_POST['id'];
    global $db;
    
    $id = $_POST['id'];
echo 'jjj';
    $dcount = $db->selectValue("select download_count from textures where id=$id");
    
    echo 'kk:'.$dcount;
    
    $db->update(
    'textures',
    [
        // set
        'download_count' => $dcount+1
    ],
    [
        'id' => $id
    ]
    );
    

}

?>
