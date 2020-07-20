<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Texture</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">

    <link rel="icon" type="image/png" href="../favicon.png?v=35f36" />
    
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript" language="javascript"></script>
    <script src="new_tex.js"></script>
    <script src="multifile.js" type="text/javascript" language="javascript"></script>

<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>    
<style>
        .MultiFile-label {
            display: block;
        }
        .ck-editor__editable_inline {
    min-height: 400px;
}
.u1 div img {
    max-height:200px;
    display:block;
    margin:0 auto 0;
}
.with-preview {
    display: block;
}
.as {
    display:block;
}
    </style>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
<div id="page">

<form action="/admin/new_tex_submit.php" method="POST" enctype="multipart/form-data" id="new-tex-form">

    <?php
    if(isset($_GET["error"])) {
    echo "<div class=\"form-item error\">";
        echo "<h2>Error: </h2>";
        echo "<p> ".$_GET["error"]."</p>";
    echo "</div>";
    }
    ?>


    <div class="form-item">
    <h2>Upload sphere render:</h2>
    
    <div class="u1">
         <input type="file" name="sphere_render" id="sphere-render" required>
         <div id="sphere-render-preview-wrapper" class="hidden">
            <img src="#" id="sphere-render-preview">
        
         </div>
         <input type="text" name="alttext"/>
    </div>
     <h2>Upload sphere render 2:</h2>
    <div class="u1">
         <input type="file" name="sphere_render2" id="sphere-render2" >
            <div id="sphere-render2-preview-wrapper" class="hidden">
            <img src="#" id="sphere-render2-preview">
            </div>
    </div>
    
    </div>

    <div class="form-item">
    <h2>Galeri:</h2>
    
    <div id="galloader"></div>
    
    <input id="withEvents" name="galleryImage[]" type="file" multiple="multiple" class="with-preview"/>

    </div>
    
    
    
    <div class="form-item">
    <h2>Name:</h2>
    <input id="form-name" type="text" name="name" value="">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">The name of the Texture, as seen on the site (e.g. <q>Red Brick 02</q>).</div>
    </div>

    <div class="form-item">
    <h2>Slug:</h2>
    <input id="form-slug" type="text" name="slug-visible" value="" disabled>
    <input id="form-slug-actual" type="text" name="slug" value="" hidden>  <!-- Duplicate hidden slug since disabled inputs aren't included in the GET parameters -->
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <label><input id="auto-slug" type="checkbox" name="auto-slug" value="Auto" checked>Auto</label><br>
    <div class="tooltip hidden">Unique identifier used for technical purposes. No punctuation or spaces allowed (e.g. <q>red_brick_02</q>).<br>
    <b>Must match the uploaded files.</b></div>
    </div>

 
    <div class="form-item">
    <h2>Download Link:</h2>
    <input id="form-name" type="text" name="download" value="">
    </div>

    <div class="form-item">
    <h2>Patreon Level:</h2>
    
    <select name="patreon_level">
        <option value="0">Deactive</option>
        <option value="1">1$ - Supporter</option>
        <option value="3">3$ - Google Drive</option>
        <option value="5">5$ - Texture Master</option>
        <option value="10">10$ - Texture Knight</option>
        <option value="25">25$ - Texture King</option>
    </select>
    
    </div>
    
     <div class="form-item">
         <textarea name="blogtext" class="texting"></textarea>

</div>
 
    
    <div class="form-item">
    <h2>SEO Title:</h2>
    <input id="form-name" type="text" name="yoast_title" value="">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Zorunlu değil. Boş bırakırsanız, template içeriğine göre kendisi oluşturur.</div>
    </div>
    
    <div class="form-item">
    <h2>SEO Meta:</h2>
    <textarea id="form-name" name="yoast_metadesc" style="width:70%" value=""></textarea>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Zorunlu değil. Boş bırakırsanız, template içeriğine göre kendisi oluşturur.</div>
    </div>
    
    <div class="form-item">
    <h2>TEX1 Meta:</h2>
    <textarea id="form-name" name="tex1_meta" style="width:70%" value=""></textarea>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Zorunlu değil.</div>
    </div>
    
    
    <div class="form-item">
    <h2>Is seamless:</h2>
    <input id="form-seamless" type="checkbox" name="seamless" value="Seamless" checked><br>
    </div>

    <div class="form-item">
    <h2>Real world scale:</h2>
    <input id="form-scale" type="text" name="scale" value="" placeholder="e.g. 2m">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">The size of this texture in the real world. Any text can be shown, e.g. <q>2m</q>, <q>30cm</q> <q>5m x 1m</q>, etc.</div>
    </div>

    <div class="form-item">
    <h2>Author:</h2>
    <input id="form-author" type="text" name="author" value="Share Textures">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">The original creator of this texture. Credit is shown on the texture page.</div>
    </div>

    <div class="form-item">
    <h2>Categories:</h2>
    <select style="padding:10px;font-size: 16px;line-height: 34px" name="cats">
    
    
    <?php 
    
    $mainCategories = getAllMainCategories();
    
    foreach ($mainCategories as $maincat) {
        
      
        
        $subcategories = getSubcategories($maincat['id']);
        
        if(count($subcategories)>0) {
            
            echo "<optgroup label='".$maincat['name']."'>";
            
            foreach ($subcategories as $subcat) {
                echo "<option value='".$subcat['id']."'>".$subcat['name']."</option>";
            }
            
            echo '</optgroup>';
            
        } else {
             echo "<option value='".$maincat['id']."'>".$maincat['name']."</option>";
        }
        
    }
    
    
    
    
    ?>
    </select>
    
    
    
    
    </div>

    <div class="form-item">
    <h2>Tags:</h2>
    <input id="form-tags" type="text" name="tags" value="">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">What someone might search for (e.g. <q>old, dirty, red, damaged</q>).<br>Choose several from below, or type new ones into the box.</div>
    <?php
    echo "<div class='cat-type'>";
    
    $adb = get_from_db("popular", "all", "all", "all", NULL, 15);
    $all_tags = [];
    foreach ($adb as $item){
        $tags = explode(";",  str_replace(',', ';', $item['real_tags']));
        
        foreach ($tags as $t){
            $t = strtolower($t);
            if (array_key_exists($t, $all_tags)){
                $all_tags[$t] = $all_tags[$t] + 1;
            }else{
                $all_tags[$t] = 1;
            }
        }
    }
    arsort($all_tags);
 
    
    
     foreach (array_keys($all_tags) as $tag){
     
        if ($tag){
            $freq = $all_tags[$tag];
            echo "<div class='button tag-option' style='opacity:";
            echo pow(($freq/7), 1)+0.4;
            echo ";font-size:";
            echo min(100, map_range($freq, 1, 3, 75, 100));
            echo "%'>";
            echo $tag;
            echo "</div>";
        }
    }
    echo "</div>";
    ?>
    </div>



    <div class="form-item">
    <h2> Date :</h2> <?php echo $date = date('Y/m/d H:i:s');?> <h2> When to publish:</h2>
    <input id="form-date-published" type="text" name="date_published" value="Immediately">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i><br>
    <div class="tooltip hidden">The date and time (24h format) when this product should be published, in the format: <q>YYYY/MM/DD HH:MM:SS</q>.<br>(e.g. <q>2017/05/22 17:59</q>, or just <q>2017/05/22</q> which will publish at midnight).</div>
    </div>

    <div class="form-item">
    <h2>Facebook:</h2>
    <input id="form-twitface" type="text" name="twitface" value="New Texture - ##name##: ##link## #free #pbr #texture #cc0 #b3d">
    </div>

    <div class="form-item">
    <h2>Reddit:</h2>
    <input id="form-reddit" type="text" name="reddit" value="##name##">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Post to /r/CC0Textures. Leave blank to skip posting to Reddit</div>
    </div>

    <div>
    <button id='submit' class='button'>Submit<i class="fa fa-chevron-right" aria-hidden="true"></i></button>
    </div>


</form>


</div>
</div>

    <script>
 CKEDITOR.replace( 'blogtext' );
 
</script>
    <script type="text/javascript">
    
    $(function(){ // wait for page to load
 


  $('#withEvents').MultiFile({
    max: 20,
    onFileRemove: function(element, value, master_element) {
      $('#F9-Log').append('<li>onFileRemove - ' + value + '</li>')
    },
    afterFileRemove: function(element, value, master_element) {
      $('#F9-Log').append('<li>afterFileRemove - ' + value + '</li>')
    },
    onFileAppend: function(element, value, master_element) {

          //      var a = '<div class="as"><input placeholder="caption" type="text" name="caption[]" id="'+element.id+'_caption'+'"></input><input name="alttext[]" placeholder="Alt text" type="text" id="'+element.id+'_alttext'+'"></input></div>';
        //$('#'+element.id).after(a);
    //$('.MultiFile-preview:last').after(a);   
    //a='';
      $('#F9-Log').append('<li>onFileAppend - ' + value + '</li>')
    },
    afterFileAppend: function(element, value, master_element) {
      $('#F9-Log').append('<li>afterFileAppend - ' + value + '</li>')
    },
    onFileSelect: function(element, value, master_element) {
      $('#F9-Log').append('<li>onFileSelect - ' + value + '</li>')
    },
    afterFileSelect: function(element, value, master_element) {
        console.log(element, value, master_element);
        console.log('id:'+element.id);
        
 //       var a = '<div class="as"><input placeholder="caption" type="text" name="caption[]" id="'+element.id+'_caption'+'"></input><input name="alttext[]" placeholder="Alt text" type="text" id="'+element.id+'_alttext'+'"></input></div>';
        //$('#'+element.id).after(a);
 //   $('.MultiFile-preview:last').after(a);   
 //   a='';
    }

  });
  
  

});

    </script>

</body>
</html>
