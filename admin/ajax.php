<?php

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

if(isset($_POST['main_category_id'])) {
    $main_category_id = $_POST['main_category_id'];
}
if(isset($_POST['is_main'])) {
    $is_main= $_POST['is_main'];
}
if(isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];
}

    
    

switch ($_POST['action']) {
    
    case 'change_category':

       $cat_id = $_POST['category_id'];
       
        $subcats = getSubcategories($cat_id);
        echo json_encode($subcats);
        break;

    case 'add_category':
            
        $a = addNewCategory($ismain=$is_main,$main_category_id=$main_category_id,$category_name=$category_name);
        echo json_encode(array('last_id' => $a,'is_main' =>$is_main,'category_name' =>$category_name));
        break;
    
    case 'delete_sub_category':

        $subcat_id = $_POST['subcat_id'];       
        $a = deleteSubCategory($main_category_id,$subcat_id);
        echo json_encode(array('last_id' => $subcat_id,'is_main' =>$is_main,'category_name' =>$category_name));
        break;

    case 'delete_category':

        $a = deleteCategory($main_category_id);
        echo json_encode(array('last_id' => $main_category_id));
        break;
    
    
    default:
        break;
}
