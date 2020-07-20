<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
$cInitial= getAllMainCategories();
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

<form action="/admin/category_submit.php" method="POST" id="yoast-form">

    <?php
    if(isset($_GET["error"])) {
    echo "<div class=\"form-item error\">";
        echo "<h2>Error: </h2>";
        echo "<p> ".$_GET["error"]."</p>";
    echo "</div>";
    }
    ?>

 
 

    <div class="form-item">
    <h1>Categories</h1>
    
 
  
    
    
 
     
    
    
        
    <div class="form-item">
        <select id="mainCategory" name="mainCategory">
            
        <?php foreach($cInitial as $mCat): ?>
            
            <option value="<?php echo $mCat['id'] ?>"><?php echo $mCat['name'] ?></option>
            
        <?php endforeach; ?>
            
        </select>
        
        <select id="subcat">
            
        </select>
        
        <div id='deletecat' style="display: inline;width:100px;background-color:#eabb89;cursor:pointer;padding:4px 10px">Seçili kategoriyi sil</div>
        <div id='deletesubcat' style="display: inline;width:100px;background-color:#eabb89;cursor:pointer;padding:4px 10px">Seçili altkategoriyi sil</div>
        
 
 
    </div>
    
    
    
    
     <div class="form-item">

         <input type="text" id="newcatname" />
         <input type="checkbox" id="makemaincat" value="1" /> Ana kategori olarak ekle
         <br/><br/>
        <div id='addcat' style="display: inline;width:100px;background-color:#eabb89;cursor:pointer;padding: 4px 10px">Kategori Ekle</div>
        
 
    
    </div>   




</form>

<script type="text/javascript">

$(document).ready(function() {
   
   getV();
    
    $('#deletecat').click(function() {
    
      if($('#mainCategory').val().length>1) {
          
      var txt;
        var r = confirm("Emin misiniz?");
        if (r == true) {
            $.ajax({ url: 'ajax.php',
              data: {action: 'delete_category',main_category_id:$('#mainCategory').val()},
              type: 'post',
              success: function(output) {
                           var t=JSON.parse(output);
                           console.log(t);


                               // Remove from subcat combo
                               $("#mainCategory option[value='"+t.last_id+"']").remove();

                            $('#mainCategory').change();

                       }
             });
        } 
      

        
        }
        
   });
   
    $('#deletesubcat').click(function() {
    
      if($('#subcat').val().length>1) {
          
        var txt;
        var r = confirm("Emin misiniz?");
        if (r == true) {
      
       $.ajax({ url: 'ajax.php',
         data: {action: 'delete_sub_category',subcat_id:$('#subcat').val(),main_category_id:$('#mainCategory').val(),'category_name':$('#newcatname').val(),'is_main':$('#makemaincat').is(":checked")},
         type: 'post',
         success: function(output) {
                      var t=JSON.parse(output);
                      console.log(t);
                
                          
                          // Remove from subcat combo
                          $("#subcat option[value='"+t.last_id+"']").remove();
                     
                       $('#mainCategory').change();
                      
                  }
        });
    }
        }
        
   });
   
   
   
   $('#addcat').click(function() {
    
      if($('#newcatname').val().length>1) {
          
      
      
       $.ajax({ url: 'ajax.php',
         data: {action: 'add_category',main_category_id:$('#mainCategory').val(),'category_name':$('#newcatname').val(),'is_main':$('#makemaincat').is(":checked")},
         type: 'post',
         success: function(output) {
                      var t=JSON.parse(output);
                      console.log(t);
                      if(t.is_main=="true") {
                          
                          // Add to main combo
                          
                          $('#mainCategory').append('<option selected="selected" value=' + t.last_id + '>' +t.category_name+ '</option>');
                          
                      
                        } else {
                          
                          // Add to subcat combo
                          $('#subcat').append('<option selected="selected" value=' + t.last_id + '>' +t.category_name+ '</option>');
                      }
                       $('#mainCategory').change();
                      
                  }
        });
        
        }
        
   });
   
   $('#mainCategory').change(function() {
      
      $('#subcat').empty();
      
      getV();
        
   });
   
});
    
function getV() {
           $.ajax({ url: 'ajax.php',
         data: {action: 'change_category',category_id:$('#mainCategory').val()},
         type: 'post',
         success: function(output) {
                      
                        var t=JSON.parse(output);
                        var c = '';
                        
                        $.each(t,function(i, item) 
                        {
                            $('#subcat').append('<option value=' + t[i].id + '>' + t[i].name + '</option>');
                        });
                        
                         
                  }
        });
}    

</script>
    
    
</div>
</div>

</body>
</html>
