<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
$gInitial= getGeneralSettings();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Texture</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="new_tex.js"></script>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
<div id="page">

<form action="/admin/general_submit.php" method="POST" id="yoast-form">

    <?php
    if(isset($_GET["error"])) {
    echo "<div class=\"form-item error\">";
        echo "<h2>Error: </h2>";
        echo "<p> ".$_GET["error"]."</p>";
    echo "</div>";
    }
    ?>

 
 

    <div class="form-item">
    <h1>Tekil</h1>
    
    <h2>"Similar Textures"</h2>
    
    <div class="form-item">
    <input type="checkbox" id="ayar1" name="similar" value="1" <?php if( $gInitial['similar']==1 ) { echo "checked='checked'"; } ?>/>
    <label for="ayar1">Similar textures görüntülensin?</label><br>

    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Similar textures gözüksün?
             
    </div>
    </div>
  
    
    
    
    <div class="form-item">
       <label for="ayar1">Similar textures altı adsense kodu</label><br>
       <textarea style="width:80%" name="adsense"><?php echo $gInitial['adsense'] ?></textarea>
         <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
        <div class="tooltip hidden">Herhangi bir HTML de eklenebilir!</div>
    </div>
     
     <div class="form-item">
       <label for="ayar1">Sol bant adsense kodu</label><br>
       <textarea style="width:80%" name="left_adsense"><?php echo $gInitial['left_adsense'] ?></textarea>
         <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
        <div class="tooltip hidden">Herhangi bir HTML de eklenebilir!</div>
    </div>   
    
     
     <div class="form-item">
       <label for="ayar1">Galeri adsense kodu</label><br>
       <textarea style="width:80%" name="g_bottom_adsense"><?php echo $gInitial['g_bottom_adsense'] ?></textarea>
         <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
        <div class="tooltip hidden">Herhangi bir HTML de eklenebilir!</div>
    </div>   
    

    <div class="form-item">
        <h2>Sutun sayısı</h2>
        <select name="columns">
            <option value="4" <?php if($gInitial['columns']==4) { echo 'selected="selected"'; } ?> >4</option>
            <option value="5" <?php if($gInitial['columns']==5) { echo 'selected="selected"'; } ?>>5</option>
            
        </select>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Sutun sayısı
             
    </div>
    </div>
    
    
        <div class="form-item">
        <h2>Similar Textures Sutun sayısı</h2>
        <select name="column_similar">
            <option value="4" <?php if($gInitial['column_similar']==4) { echo 'selected="selected"'; } ?> >4</option>
            <option value="5" <?php if($gInitial['column_similar']==5) { echo 'selected="selected"'; } ?>>5</option>
            <option value="8" <?php if($gInitial['column_similar']==8) { echo 'selected="selected"'; } ?>>8</option>
            <option value="10" <?php if($gInitial['column_similar']==10) { echo 'selected="selected"'; } ?>>10</option>
            
        </select>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Sutun sayısı
             
    </div>
    </div>
    
    
    

    <div>
    <button id='submit' class='button'>Submit<i class="fa fa-chevron-right" aria-hidden="true"></i></button>
    </div>
    <div>
        <a href="clear_cache.php">Cache'i temizle</a>
    </div>

</form>


</div>
</div>

</body>
</html>
