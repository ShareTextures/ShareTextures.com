<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
$yInitial=getYoast();
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

<form action="/admin/yoast_submit.php" method="POST" id="yoast-form">

    <?php
    if(isset($_GET["error"])) {
    echo "<div class=\"form-item error\">";
        echo "<h2>Error: </h2>";
        echo "<p> ".$_GET["error"]."</p>";
    echo "</div>";
    }
    ?>

 
 

    <div class="form-item">
    <h1>TAG / Kategori sayfası</h1>
    
    <h2>Başlık(title) formatı:</h2>
    <input id="form-name" type="text" name="tag_title" value="<?php echo $yInitial['tag_title'] ?>">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Tag ve kategori sayfalarında yer alacak başlık formatı.
        <p>{tagcat}: Kategori / etiket adı</p>
       
    </div>
    
  
    
    <div class="form-item">
    
    <h2>Meta description formatı:</h2>
    <textarea style="width:100%;height: 200px" id="form-name" name="tag_meta"><?php echo $yInitial['tag_meta'] ?></textarea>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Tag ve kategori sayfalarında yer alacak meta description formatı.
        <p>{tagcat}: Kategori / etiket adı</p>
       
    </div>
    
    </div>
    
    
    <h1>Tekil Sayfalar</h1>
   
    
    <h2>Başlık(title) formatı:</h2>
    <input id="form-name" type="text" name="single_title" value="<?php echo $yInitial['single_title'] ?>">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Tekil sayfalarda yer alacak başlık formatı.
        <p>{tag}: etiket adı</p>
        <p>{category}: kategori adı</p>
       <p>{item}: sayfa adı</p>
    </div>
    
  
    
    <div class="form-item">
    
    <h2>Meta description formatı:</h2>
    <textarea style="width:100%;height: 200px" id="form-name" name="single_meta" value=""><?php echo $yInitial['single_meta'] ?></textarea>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Tekil sayfalarda yer alacak başlık formatı.
        <p>{tag}: etiket adı</p>
        <p>{category}: kategori adı</p>
       <p>{item}: sayfa adı</p>
       
    </div>
    
    </div>
    
    
    
</div>
    



    <div>
    <button id='submit' class='button'>Submit<i class="fa fa-chevron-right" aria-hidden="true"></i></button>
    </div>


</form>


</div>
</div>

</body>
</html>
