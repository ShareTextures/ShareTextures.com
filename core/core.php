<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/php/secret_config.php');

require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$dataSource = new \Delight\Db\PdoDataSource('mysql'); // see "Available drivers for database systems" below
$dataSource->setHostname('localhost');
$dataSource->setPort(3306);
$dataSource->setDatabaseName('____________');
$dataSource->setCharset('utf8mb4');
$dataSource->setUsername('_____');
$dataSource->setPassword('____');

$db = \Delight\Db\PdoDatabase::fromDataSource($dataSource);

function add_nofollow_content($content) {
$content = preg_replace_callback('/]*href=["|\']([^"|\']*)["|\'][^>]*>([^<]*)<\/a>/i', function($m) {
    if (strpos($m[1], $_SERVER['HTTP_HOST']) === false)
        return ' href="'.$m[1].'" rel="nofollow noopener nofollow" target="_blank">'.$m[2].'</a>';
    else
        return ' href="'.$m[1].'" target="_blank">'.$m[2].'</a>';
    }, $content);
return $content;
}

function taglister() {
    
    global $db;
    
    $tags = $db->select("SELECT distinct(real_tags) as tags from textures");
    
    $utags = array();
    $uni = array();
    
    foreach ($tags as $tag) {
     
          $exploded = explode(',', $tag['tags']);
            
        foreach($exploded as $exp) {
            array_push($utags, str_replace(' ', '-', $exp));
        }
        
        
    }
    
    return array_unique($utags);
    
}

// ============================================================================
// TEXTURES
// ============================================================================

function getLatestTextures($order='date_published') {
    global $db;
/*    
        switch($order) {
        
        default:
        case 'downloads':
            $q = "SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' ORDER BY download_count DESC LIMIT 20";
            break;
        
        case 'date_published':
             $q = "SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' ORDER BY DATE(date_published) DESC LIMIT 20";

            break;
         
    }
*/
    $q = "SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' AND patreon_level<1 ORDER BY DATE(date_published) DESC LIMIT 20";

    return $db->select($q);
}
function countTextures() {
    global $db;
    return $db->selectValue("SELECT COUNT(id) as t FROM textures WHERE date_published<NOW() AND real_category<>'Blog'");
}
function countPonlyTextures() {
    global $db;
    return $db->selectValue("SELECT COUNT(id) as t FROM textures WHERE date_published<NOW() AND real_category<>'Blog' AND patreon_level>1 AND patreon_level<6");
}
function countPonlypTextures() {
    global $db;
    return $db->selectValue("SELECT COUNT(id) as t FROM textures WHERE date_published<NOW() AND real_category<>'Blog' AND patreon_level>6");
}
function getGalleryImage($postid) {
    global $db;
    return $db->select("SELECT * FROM gallery WHERE post_id=$postid");
}
function countFreeTextures() {
    global $db;
    return $db->selectValue("SELECT COUNT(id) as t FROM textures WHERE date_published<NOW() AND real_category<>'Blog' AND patreon_level<1");
}
function getLatestTexturesCANA($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' AND patreon_level<1 order by  DATE(date_published)  DESC LIMIT $count");
}

function getLatestTexturesC($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog'order by DATE(date_published) DESC LIMIT $count");
}
function getLatestTexturesCb($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE real_category<>'Blog'order by DATE(date_published) DESC LIMIT $count");
}
function getSitemapTextures($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' order by DATE(date_published) DESC LIMIT $count");
}
function getLatestTexturesCNOTPATRON($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' and patreon_level=0 order by  DATE(date_published)  DESC LIMIT $count");
}
function getLatestPatreons($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' and patreon_level=5 order by  DATE(date_published)  DESC LIMIT $count");
}
function getLatestPatreonsSUPER($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category<>'Blog' and patreon_level=10 order by  DATE(date_published)  DESC LIMIT $count");
}
// ============================================================================
// BLOG
// ============================================================================

function getAllBlogs() {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category='Blog' ORDER BY DATE(date_published) DESC");
}
function getLatestBlogs($count) {
    global $db;
    return $db->select("SELECT * FROM textures WHERE date_published<NOW() AND real_category='Blog' ORDER BY DATE(date_published) DESC LIMIT $count");
}

function getBlogbySlug($slug) {
    global $db;
    return $db->selectRow("SELECT * FROM textures WHERE date_published<NOW() AND slug='".$slug."'");
}

function truncateString($str, $chars, $to_space, $replacement="...") {
   if($chars > strlen($str)) return $str;

   $str = substr($str, 0, $chars);
   $space_pos = strrpos($str, " ");
   if($to_space && $space_pos >= 0) 
       $str = substr($str, 0, strrpos($str, " "));

   return($str . $replacement);
}
// ============================================================================
// Constants & Utils
// ============================================================================

$WORKING_LOCALLY = substr($_SERVER['DOCUMENT_ROOT'], 0, 3) == "C:/" || substr($_SERVER['DOCUMENT_ROOT'], 0, 3) == "X:/";

$SYSTEM_ROOT = $_SERVER['DOCUMENT_ROOT'];
if ($WORKING_LOCALLY){
    $SYSTEM_ROOT = $GLOBALS['LOCAL_WORKING_FOLDER'];
}

require_once($_SERVER['DOCUMENT_ROOT'].'/vendor/patreon/patreon/src/patreon.php');
use Patreon\API;
use Patreon\OAuth;

$patreon = get_patreon();
$PATRON_LIST = $patreon[0];
$PATREON_GOALS = $patreon[2];
$PATREON_CURRENT_GOAL = null;
foreach ($PATREON_GOALS as $g){
    if ($g['completed_percentage'] < 100 && strpos($g['description'], "[reached") == false){
        $PATREON_CURRENT_GOAL = $g;
        break;
    }
}
$PATREON_EARNINGS = floor(($PATREON_CURRENT_GOAL['amount_cents']*($PATREON_CURRENT_GOAL['completed_percentage']/100))/100);

// Don't cache these pages | GET params ignored | matched to $_SERVER['PHP_SELF']
$NO_CACHE = ["/gallery/do_submit.php",
             "/gallery/moderate.php"
            ];


function getYoast($reuse_conn=NULL) {
            
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $row = 0; // Default incase of SQL error
    $sql = "SELECT * from yoast where id=1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }

    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $row;
}
function getCategoryByID($id) {
    
    global $db;
    
    $mcat='';
    
    $cat = $db->selectRow(
    'SELECT * from categories where id='.$id    
    );
    
    if(intval($cat['main_cat_id'])>0) {
        // thats a subcategory, find main
        
        $mcat = $db->selectValue("SELECT name from categories where id=".$cat['main_cat_id']    
    );
        
    }
    
    if(strlen($mcat)>0) {
        $rcat = $mcat.','.$cat['name'];
    } else {
        $rcat = $cat['name'];
        
    }
    
    return $rcat;
}

function getAllMainCategories() {
    
    global $db;
    
    return $db->select(
    'SELECT * from categories where main_cat_id IS NULL'    
    );
    
}

function getSlugCategory($id) {
    
    global $db;
    
    $cat = $db->selectRow(
    'SELECT * from categories where id='.$id    
    );
    
    if(intval($cat['main_cat_id'])>0) {
        // thats a subcategory, find main
        
        $rcat = $db->selectValue("SELECT name from categories where id=".$cat['main_cat_id']    
    );
        
    } else {
        $rcat =$cat['name']; 
    }
        
    return strtolower($rcat);
    
    
}
function getSubcategories($maincat) {
    
    global $db;
    
    return $db->select(
    'SELECT * from categories where main_cat_id='.$maincat    
    );
    
}

function deleteSubCategory($main_category_id,$subcat_id) {
    
    global $db;
    
        $db->delete(
            'categories',
            [
                'id' => $subcat_id,
                'main_cat_id' => $main_category_id
            ]
        );
    
}
function deleteCategory($main_category_id) {
    
    global $db;
    
        $db->delete(
            'categories',
            [
                'id' => $main_category_id
            ]
        );
    
}

function addNewCategory($ismain,$main_category_id,$category_name) {
    global $db;
    
    if($ismain=="true") {
        $db->insert('categories',
        [
            // set
            'name' => $category_name        
        ]);
        
    } else {
        $db->insert('categories',
        [
            // set
            'name' => $category_name,
            'main_cat_id' =>$main_category_id
        ]);
    }
    
    return $db->getLastInsertId();
    
}

function getGeneralSettings() {
     
    global $db;
    
    $sql = "SELECT * from general where id=1";
 
    return $db->selectRow($sql);
 
}

function nice_name($name, $mode="normal"){
    $str = str_replace('_', ' ', $name);
    if ($mode=="category"){
        // Some categories have a slash in them, but that would ruin URLs so they are stored as a dash instead and then replaced with a slash for display
        $str = implode('/', array_map('ucfirst', explode('-', $str)));
    }
    $str = ucwords($str);
    return $str;
}

function to_slug($name){
    $name = str_replace(' ', '_', $name);
    $name = strtolower($name);
    $name = simple_chars_only($name);
    return $name;
}
function to_slug2($name){
    $name = str_replace(' ', '-', $name);
    $name = strtolower($name);
    $name = simple_chars_only($name);
    return $name;
}

function md_to_html($s){
    require_once($_SERVER['DOCUMENT_ROOT'].'/core/parsedown/Parsedown.php');
    $parsedown = new Parsedown();
    return $parsedown->text($s);
}

function starts_with($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}

function ends_with($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function str_contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

function str_lreplace($search, $replace, $subject) {
    // Replace only last occurance in string
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

function fmoney($i){
    return number_format($i, 2, '.', ' ');
}

function random_hash($length=8){
    $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789";
    $hash = "";
    for ($i=0; $i<$length; $i++){
        $hash .= $chars[rand(0, strlen($chars)-1)];
    }
    return $hash;
}

function simple_hash($str){
    // Simple not-so-secure 8-char hash
    return hash('crc32', $GLOBALS['GEN_HASH_SALT'].$str, FALSE);
}

function simple_chars_only($str){
    return preg_replace("/[^A-Za-z0-9_\- ]/", '', $str);
}

function numbers_only($str){
    return preg_replace("/[^0-9]/", '', $str);
}

function map_range($value, $fromLow, $fromHigh, $toLow, $toHigh) {
    $fromRange = $fromHigh - $fromLow;
    $toRange = $toHigh - $toLow;
    $scaleFactor = $toRange / $fromRange;

    $tmpValue = $value - $fromLow;
    $tmpValue *= $scaleFactor;
    return $tmpValue + $toLow;
}

function time_ago($strtime) {
    // Source: http://goo.gl/LQJWnW

    $time = time() - strtotime($strtime); // to get the time since that moment

    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = round($time / $unit);
        $rstr = $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'')." ago";
        if ($text == 'day'){
            $rstr = "<span class='new-tex'>".$rstr."</span>";
        }
        return $rstr;
    }
    return "<span class='new-".$GLOBALS['CONTENT_TYPE_SHORT']."'>Today</span>";
}

function is_in_the_past($d) {
    return (time() - strtotime($d) > 0);
}

function first_in_array($a){
    // Return first item of array, php is silly
    $a = array_reverse($a);
    return array_pop($a);
}

function array_sort($array, $on, $order=SORT_ASC){

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function resize_image($old_fp, $new_fp, $format, $size_x, $size_y, $quality=85){
    $img = new imagick($old_fp);
    $img->resizeImage($size_x, $size_y, imagick::FILTER_BOX, 1, true);
    $img->setImageFormat($format);
    if ($format == "jpg"){
        $img->setImageCompression(Imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality($quality);
    }
    $img->writeImage($new_fp);
}

function clean_email_string($string) {
    $bad = array("content-type","bcc:","to:","cc:","<script>");
    return str_replace($bad,"",$string);
}

function debug_email($subject, $text){
    $email_to = $GLOBALS['ADMIN_EMAIL'];
    $email_from = "info@".$GLOBALS['SITE_DOMAIN'];
    $headers = 'From: '.$email_from."\r\n".
    'Reply-To: '.$email_from."\r\n" .
    'MIME-Version: 1.0' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    @mail($email_to, $subject, clean_email_string($text), $headers);
}

function debug_console($str){
    echo "<script>";
    echo "console.log(\"".$str."\");";
    echo "</script>";
}

function print_ra($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function join_paths() {
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }
    return preg_replace('#/+#','/',join('/', $paths));
}

function listdir($d, $mode="ALL"){
    // List contents of folder, without hidden files
    $sd = scandir($d);
    $files = [];
    foreach ($sd as $f){
        if (!starts_with($f, '.')){
            $is_file = str_contains($f, '.');  // is_dir doesn't work reliably on windows, so we assume all folders do not contain '.' #YOLO
            if (($mode == "ALL") or ($mode == "FOLDERS" and !$is_file) or ($mode == "FILES" and $is_file)){
                array_push($files, $f);
            }
        }
    }
    return $files;
}

function qmkdir($d) {
    // Quitly mkdir if it doesn't exist aleady, recursively
    if (!file_exists($d)){
        mkdir($d, 0777, true);
    }
}

function clear_cache(){
    $cache_dir = $_SERVER['DOCUMENT_ROOT']."/php/cache/";
    $r = array_map('unlink', glob("$cache_dir*.html"));
    return sizeof($r);  // Number of cache files cleared
}


// ============================================================================
// HTML
// ============================================================================

function generate_index_meta() {

$html='    
<meta property="fb:pages" content="334656700691999" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=yes" />
<meta name="description" content="Free CC0 PBR Texture library include wood, stone, wall, ground etc. textures ready for Vray, Blender, Sketchup, Lumion, Twinmotion softwares"/>
<meta name="robots" content="index, follow">
<meta content="IE=7" http-equiv="X-UA-Compatible">
<meta http-equiv="cache-control" content="max-age=2">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<meta http-equiv="pragma" content="no-cache">
<meta name="author" content="Share Textures">
<meta name="abstract" content="Share Textures creating PBR Textures and sharing them under CC0. All textures are free and avaible with diffuse, displacement, normal maps.">
<meta name="copyright" content="CC0 by ShareTextures ">
<meta name="revisit-after" content="6 day">

<link rel="canonical" href="https://www.sharetextures.com/" />
<link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-32x32.png" sizes="32x32" />
<link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-192x192.png" sizes="192x192" />
<link rel="apple-touch-icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png" />
<meta name="msapplication-TileImage" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-270x270.png" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:description" content="Free CC0 PBR Texture library include wood, stone, wall, ground etc. textures ready for Vray, Blender, Sketchup, Lumion, Twinmotion softwares" />
<meta name="twitter:title" content="Free PBR Textures | CC0 Textures | Share Textures" />
<meta name="twitter:site" content="@sharetextures" />
<meta name="twitter:image" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png" />
<meta name="twitter:creator" content="@sharetextures" />

<meta name="tex1:display-name" content="ShareTextures Free CC0 PBR Textures" >
<meta name="tex1:display-domain" content="Sharetextures.com" >
<meta name="tex1:patreon" content="sharetextures" >
<meta name="tex1:twitter" content="ShareTextures" >
<meta name="tex1:instagram" content="ShareTextures" >
<meta name="tex1:logo" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-logo.png" >
<meta name="tex1:icon" content="https://www.sharetextures.com/wp-content/uploads/2018/08/cropped-logosharetextures-1-2.png" >

<meta name="msvalidate.01" content="8B8C38236C9F19E167785B68FCD97C57" />
<meta name="google-site-verification" content="pEx5wXzdnkT8WGmgR0jveIzWgGoMa5dDQH43DR26atY" />
<meta name="p:domain_verify" content="ca6d3d206f37b699b7d6913f06cbe224" />
<meta name="yandex-verification" content="8e95dabc546aaf58" />
<meta name="theme-color" content="#24323f"> 
';

return $html;

}
function generate_texturesmain_meta() {
    
    $html = '
<meta property="fb:pages" content="334656700691999" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=yes" />
<meta name="description" content="Free CC0 PBR Texture library include wood, stone, wall, ground etc. textures ready for Vray, Blender, Sketchup, Lumion, Twinmotion softwares"/>
<meta name="robots" content="index, follow">
<meta content="IE=7" http-equiv="X-UA-Compatible">
<meta http-equiv="cache-control" content="max-age=2">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<meta http-equiv="pragma" content="no-cache">
<meta name="author" content="Share Textures">
<meta name="abstract" content="Share Textures creating PBR Textures and sharing them under CC0. All textures are free and avaible with diffuse, displacement, normal maps.">
<meta name="copyright" content="CC0 by ShareTextures ">
<meta name="revisit-after" content="6 day">

<link rel="canonical" href="https://www.sharetextures.com/textures/" />
<link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-32x32.png" sizes="32x32" />
<link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-192x192.png" sizes="192x192" />
<link rel="apple-touch-icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png" />
<meta name="msapplication-TileImage" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-270x270.png" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:description" content="Free CC0 PBR Texture library include wood, stone, wall, ground etc. textures ready for Vray, Blender, Sketchup, Lumion, Twinmotion softwares" />
<meta name="twitter:title" content="Free PBR Textures | CC0 Textures | Share Textures" />
<meta name="twitter:site" content="@sharetextures" />
<meta name="twitter:image" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png" />
<meta name="twitter:creator" content="@sharetextures" />

<meta name="tex1:display-name" content="ShareTextures Free CC0 PBR Textures" >
<meta name="tex1:display-domain" content="Sharetextures.com" >
<meta name="tex1:patreon" content="sharetextures" >
<meta name="tex1:twitter" content="ShareTextures" >
<meta name="tex1:instagram" content="ShareTextures" >
<meta name="tex1:logo" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-logo.png" >
<meta name="tex1:icon" content="https://www.sharetextures.com/wp-content/uploads/2018/08/cropped-logosharetextures-1-2.png" >

<meta name="msvalidate.01" content="8B8C38236C9F19E167785B68FCD97C57" />
<meta name="google-site-verification" content="pEx5wXzdnkT8WGmgR0jveIzWgGoMa5dDQH43DR26atY" />
<meta name="p:domain_verify" content="ca6d3d206f37b699b7d6913f06cbe224" />
<meta name="yandex-verification" content="8e95dabc546aaf58" />
<meta name="theme-color" content="#24323f"> 
';
        
   return $html;
    
}
function generate_blogmain_meta() {
    
    $html = '
<meta property="fb:pages" content="334656700691999" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=yes" />
<meta name="description" content="Free CC0 PBR Texture library include wood, stone, wall, ground etc. textures ready for Vray, Blender, Sketchup, Lumion, Twinmotion softwares"/>
<meta name="robots" content="index, follow">
<meta content="IE=7" http-equiv="X-UA-Compatible">
<meta http-equiv="cache-control" content="max-age=2">
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="expires" content="-1">
<meta http-equiv="pragma" content="no-cache">
<meta name="author" content="Share Textures">
<meta name="abstract" content="Share Textures creating PBR Textures and sharing them under CC0. All textures are free and avaible with diffuse, displacement, normal maps.">
<meta name="copyright" content="CC0 by ShareTextures ">
<meta name="revisit-after" content="6 day">

<link rel="canonical" href="https://www.sharetextures.com/textures/" />
<link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-32x32.png" sizes="32x32" />
<link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-192x192.png" sizes="192x192" />
<link rel="apple-touch-icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png" />
<meta name="msapplication-TileImage" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-270x270.png" />

<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:description" content="Free CC0 PBR Texture library include wood, stone, wall, ground etc. textures ready for Vray, Blender, Sketchup, Lumion, Twinmotion softwares" />
<meta name="twitter:title" content="Free PBR Textures | CC0 Textures | Share Textures" />
<meta name="twitter:site" content="@sharetextures" />
<meta name="twitter:image" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png" />
<meta name="twitter:creator" content="@sharetextures" />

<meta name="tex1:display-name" content="ShareTextures Free CC0 PBR Textures" >
<meta name="tex1:display-domain" content="Sharetextures.com" >
<meta name="tex1:patreon" content="sharetextures" >
<meta name="tex1:twitter" content="ShareTextures" >
<meta name="tex1:instagram" content="ShareTextures" >
<meta name="tex1:logo" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-logo.png" >
<meta name="tex1:icon" content="https://www.sharetextures.com/wp-content/uploads/2018/08/cropped-logosharetextures-1-2.png" >

<meta name="msvalidate.01" content="8B8C38236C9F19E167785B68FCD97C57" />
<meta name="google-site-verification" content="pEx5wXzdnkT8WGmgR0jveIzWgGoMa5dDQH43DR26atY" />
<meta name="p:domain_verify" content="ca6d3d206f37b699b7d6913f06cbe224" />
<meta name="yandex-verification" content="8e95dabc546aaf58" />
<meta name="theme-color" content="#24323f">  
';
        
   return $html;
    
}

function generate_texture_meta($info) {
   
    global $db;


    // Yoast title
    
    // Get the "yoast_title" if exists
    // If not, generate from template
    
    if(strlen($info['yoast_title'])<2) {
        
        // Not exists; generate from template! 
        // Get template first:
        
        $template_title = $db->selectValue("select single_title from yoast limit 1");
        
          $skat = explode(',',$info['categories']);
        
        $item_title = str_replace(array('{item}','{category}','{tag}'), array($info['name'], ucfirst($skat[0]),$info['tags']), $template_title);

    } else {
        $item_title = $info['yoast_title'];
    }

    
    // Yoast metadesc
    
    // Get the "yoast_metadesc" if exists
    // If not, generate from template
    
    if(strlen($info['yoast_metadesc'])<2) {
        
        // Not exists; generate from template! 
        // Get template first:
        
        $template = $db->selectValue("select single_meta from yoast limit 1");
        
        $item_metadesc = str_replace(array('{item}','{category}','{tag}'), array($info['name'],$info['categories'],$info['tags']), $template);
        
        
    } else {
        $item_metadesc = $info['yoast_metadesc'];
    }
    
        
$head ="<html>
<head>
       <title>".$item_title."</title>

    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/style_medium.css' rel='stylesheet' media='screen and (max-width: 1299px)' type='text/css' />
    <link href='/css/style_small.css' rel='stylesheet' media='screen and (max-width: 759px)' type='text/css' />
    <link href='/css/colorbox.css' rel='stylesheet' type='text/css' />
	<link href='/css/carousel.css' rel='stylesheet' type='text/css' />
    <link href='/css/carousel_theme.css' rel='stylesheet' type='text/css' />
	 
    <link href='https://fonts.googleapis.com/css?family=Lato:900,400|Roboto:900,500,300&display=swap' rel='stylesheet'>
    <link href='https://fonts.googleapis.com/icon?family=Material+Icons&display=swap' rel='stylesheet'>
    ";

    /* GENERICS */
    
    $generics ='
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index, follow">
	<meta content="IE=7" http-equiv="X-UA-Compatible">
	<meta http-equiv="cache-control" content="max-age=2">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="-1">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="author" content="Share Textures">
	<meta name="abstract" content="Share Textures creating PBR Textures and sharing them under CC0. All textures are free and avaible with diffuse, displacement, normal maps.">
	<meta name="copyright" content="CC0 by ShareTextures ">
	<meta name="revisit-after" content="6 day">
    <meta name="description" content="'.$item_metadesc.'"/>
    <meta name="keywords" content="'.$info['categories'].','.$info['tags'].'">
    <meta name="author" content="'.$info['author'].'">
    
    <meta property="fb:pages" content="334656700691999"/>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="'.$item_metadesc.'"/>
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1"/>
    <meta name="google-site-verification" content="6eVULfPKfA999QcfS0aOZ3o8TsBcc59MRAqvxjgQu5M"/>
    <link rel="canonical" href="//'.$_SERVER['HTTP_HOST'].'/textures/'.$info['slug'].'"/>
    <meta name="theme-color" content="#24323f"> 
     ';
    
    /* TWITTER CARD */
    
    $twittercard=' 
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:creator" content="@sharetextures"/>
    <meta name="twitter:text:title" content="'.$info['name'].'"/>
    <meta name="twitter:description" content="'.$item_metadesc.'"/>
    <meta name="twitter:title" content="'.$info['name'].'| '.$info['categories'].'"/>
    <meta name="twitter:site" content="@sharetextures"/>
    <meta name="twitter:image" content="https://www.sharetextures.com/'.$info['image_featured'].'"/>
    <meta name="twitter:image:alt" content="'.$info['name'].'"/>
    ';
    
    /* OPEN GRAPH */
    
    $og ='
    <meta property="og:site_name" content="Share Textures"/>
    <meta property="og:image" content="https://'.$_SERVER['HTTP_HOST'].'/'.$info['image_featured'].'"/>
    <meta property="og:image:width" content="1024"/>
    <meta property="og:image:height" content="1024"/>
    <meta property="og:image:alt" content="'.$info['alttext'].'"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="product"/>
    <meta property="og:title" content="'.$info['name'].'"/>
    <meta property="og:url" content="https://'.$_SERVER['HTTP_HOST'].'/textures/'.$info['slug'].'"/>
    <meta property="og:description" content="'.$item_metadesc.'"/>
      ';
    
    /* TEX1 */

   if(empty($info['tex1_meta'])){
	$info['tex1_meta'] = 'pbr-approximated';
} else{
	$tex1meta = $info['tex1_meta'];
}
	

    $tex1 ='
    <meta name="tex1:type" content="'.$info['tex1_meta'].'"/>
    <meta name="tex1:display-name" content="ShareTextures Free CC0 PBR Textures">
    <meta name="tex1:display-domain" content="Sharetextures.com">
    <meta name="tex1:patreon" content="sharetextures">
    <meta name="tex1:twitter" content="ShareTextures">
    <meta name="tex1:instagram" content="ShareTextures">
    <meta name="tex1:logo" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-logo.png">
    <meta name="tex1:icon" content="https://www.sharetextures.com/wp-content/uploads/2018/08/cropped-logosharetextures-1-2.png">
    <meta name="tex1:preview-image" content="https://'.$_SERVER['HTTP_HOST'].'/'.$info['image_featured'].'">
    <meta name="tex1:tags" content="'.$info['real_tags'].'">
	<meta name="tex1:resolution" content="4096">
    <meta name="tex1:release-date" content="'.date("Y-m-d", strtotime($info['date_published'])).'">
	<meta name="tex1:license" content="cc0">  
    ';
            
    /* ICONS */
    
    $icons = '
    <link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-32x32.png" sizes="32x32"/>
    <link rel="icon" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-192x192.png" sizes="192x192"/>
    <link rel="apple-touch-icon-precomposed" href="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-180x180.png"/>
    <meta name="msapplication-TileImage" content="https://www.sharetextures.com/wp-content/uploads/2019/01/cropped-cropped-logosharetextures-1-270x270.png"/>
    <meta name="theme-color" content="#24323f"> 
    ';
    
    /* JS */
    
    $js = ' 
    <script src="/js/jquery.min.js"></script>
    <script src="/core/core.js"></script>
    <script src="/js/functions.js"></script>
	<script src="/js/colorbox.js"></script>
	<script src="/js/carousel.js"></script>
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    ';
        
    $html = $head.$generics.$twittercard.$og.$tex1.$icons.$js;
    
echo $html;
    
}


function include_start_html($title, $slug="", $canonical="", $t1="",$info="") {
    ob_start();
    global $db;
    include $_SERVER['DOCUMENT_ROOT']."/php/html/start_html.php";
    $html = ob_get_contents();
            
    $textures_one = "";
    ob_end_clean();

    switch ($title) {
        case 'SEARCH':
            $html2 = generate_index_meta();
            $reptitle="Search | Share Textures";
            break;
        
        case 'MAIN':
            $html2 = generate_index_meta();
            $reptitle="Free PBR Textures | CC0 Textures | Share Textures";
            break;
        case 'BLOG':
            $html2 = generate_texture_meta($slug);
            $reptitle=$html2['name'].' | Sharetextures.com';
            
            break;
        case 'MAINBLOG':
            $html2 = generate_blogmain_meta();
            $reptitle="Blog | PBR Textures | Share Textures";
            break;
        case 'MAINTEXTURES':
            $html2 = generate_texturesmain_meta();
            
            $tag_title = $db->selectValue('select tag_title from yoast');
            
            if($slug=='index.php') {
                $reptitle='Textures | Sharetextures';
            } else {
                $reptitle=str_replace('{tagcat}', ucfirst($slug), $tag_title);
            }

            
            break;
         case 'Privacy Policy':
            $html2 = generate_index_meta();
            $reptitle="Privacy Policy | Share Textures";
            break;       
         case 'About':
            $html2 = generate_index_meta();
            $reptitle="About / Contact | Share Textures";
            break;   
    }

    $html = str_replace('%METAXAS%', $html2, $html);
    $html = str_replace('%TITLE%', $reptitle, $html);
    
    echo $html;
}

function include_disqus($id) {
    ob_start();
    include $_SERVER['DOCUMENT_ROOT']."/php/html/disqus.php";
    $html = ob_get_contents();
    ob_end_clean();

    $id = str_replace("'", "\'", $id);
    echo str_replace('%ID%', $id, $html);
}

function insert_email($text="##email##"){
    $email = "info@".$GLOBALS['SITE_DOMAIN'];
    echo '<a href="mailto:'.$email.'" target="_blank">';
    if ($text == "##email##"){
        echo $email;
    }else{
        echo $text;
    }
    echo "</a>";
}

function make_grid_link_ba($sort="popular", $search="all", $category="all",$category2='', $author="all"){
    $default_sort = "popular";
    $default_search = "all";
    $default_category = "all";
    $default_author = "all";

    $url = "/".$GLOBALS['CONTENT_TYPE']."/";

    $params = [];
    if ($sort != $default_sort){
        array_push($params, "o=".$sort);
    }
        if ($category != $default_category){
        array_push($params, $category);
    }
    if ($search != $default_search){
        array_push($params, str_replace(' ','-',$search));
    }

    if ($author != $default_author){
        array_push($params, "a=".$author);
    }

    if (empty($params)){
        return $url;
    }

            
    $url .= implode("/", $params);
            
    return $url;
}
function make_grid_link($sort="popular", $search="all", $category="all", $author="all"){
    $default_sort = "popular";
    $default_search = "all";
    $default_category = "all";


        
    $url = "/".$GLOBALS['CONTENT_TYPE']."/?";

    $c = $_REQUEST['c'];
    $c2 = $_REQUEST['c2'];
    $orderby = $_REQUEST['orderby'];
   
    
            
    $url .=  'c='.$c.'&c2='.$c2.'&orderby='.$sort; //.'&orderby='.$out.'&';
    return $url;
}


// ============================================================================
// Database functions
// ============================================================================

function db_conn_read_only(){
    $servername = $GLOBALS['DB_SERV'];
    $dbname = $GLOBALS['DB_NAME'];
    $username = $GLOBALS['DB_USER_R'];
    $password = $GLOBALS['DB_PASS_R'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function db_conn_read_write(){
    $servername = $GLOBALS['DB_SERV'];
    $dbname = $GLOBALS['DB_NAME'];
    $username = $GLOBALS['DB_USER'];
    $password = $GLOBALS['DB_PASS'];
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function num_items($search="all", $category="all", $reuse_conn=NULL){
    $size = 0;

    // Create connection
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $search_text = make_search_SQL(mysqli_real_escape_string($conn, $search), $category, "all");

    $sql = "SELECT name FROM ".$GLOBALS['CONTENT_TYPE']." ".$search_text;
    $rows = mysqli_query($conn, $sql)->num_rows;

    if (is_null($reuse_conn)){
        $conn->close();
    }
            
    return $rows;
}

function get_from_db($sort="popular", $search="all", $category="all", $author="all", $reuse_conn=NULL, $limit=0){
    $sort_text = make_sort_SQL($sort);

    // Create connection
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $search_text = make_search_SQL(mysqli_real_escape_string($conn, $search), $category, $author);
            

    $sql = "SELECT * FROM ".$GLOBALS['CONTENT_TYPE']." ".$search_text." ".$sort_text;

    if ($limit > 0){
        $sql .= " LIMIT ".$limit;
    }
    $result = mysqli_query($conn, $sql);

    $array = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $array[$row['name']] = $row;
        }
    }
    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $array;
}

function get_item_from_db($item, $reuse_conn=NULL){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $row = 0; // Default incase of SQL error
    $sql = "SELECT * FROM ".$GLOBALS['CONTENT_TYPE']." WHERE slug='".$item."'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }

    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $row;
}

function track_search($search_term, $category="", $reuse_conn=NULL){
            
    if ($search_term != "all"){
        if (is_null($reuse_conn)){
            $conn = db_conn_read_write();
        }else{
            $conn = $reuse_conn;
        }
        $search_term = mysqli_real_escape_string($conn, $search_term);
        $category = mysqli_real_escape_string($conn, $category);

        $sql = "INSERT INTO searches (`category`, `search_term`) ";
        $sql .= "VALUES (\"".$category."\", \"".$search_term."\")";
        $result = mysqli_query($conn, $sql);
            
        if (is_null($reuse_conn)){
            $conn->close();
        }
    }
}
/*
function get_download_count($item_id, $reuse_conn=NULL){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $num = 0; // Default incase of SQL error
    $sql = "SELECT COUNT(DISTINCT(ip)) as dl FROM `download_counting` ";
    $sql .= "WHERE ".$GLOBALS['CONTENT_TYPE_SHORT']."_id =".$item_id;
    
    var_dump($sql);
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $num = $row['dl'];
    }

    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $num;
}
*/
function get_similar($slug, $reuse_conn=NULL){
    
    $settings = getGeneralSettings();
    
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $items = get_from_db("popular", "all", "all", "all", $conn);
    if (is_null($reuse_conn)){
        $conn->close();
    }

    $this_item = array();
    foreach ($items as $row){
        if ($row['slug'] == $slug){
            $this_item = $row;
            break;
        }
    }
    if (!$this_item){
        // Unpublished items will not be in 'get_from_db', so just don't show their similar items
        return NULL;
    }
    $similarities = array();
    foreach ($items as $row){
        $row_slug = $row['slug'];
        if ($row_slug != $slug){
            $cats = explode(";", $row['categories']);
            foreach ($cats as $cat){
                if (strpos((';'.$this_item['categories'].';'), (';'.$cat.';')) !== FALSE){
                    if (array_key_exists($row_slug, $similarities)){
                        $similarities[$row_slug] = $similarities[$row_slug] + 1;
                    }else{
                        $similarities[$row_slug] = 1;
                    }
                }
            }
            $tags = explode(";", $row['tags']);
            foreach ($tags as $tag){
                if (strpos((';'.$this_item['tags'].';'), (';'.$tag.';')) !== FALSE){
                    if (array_key_exists($row_slug, $similarities)){
                        $similarities[$row_slug] = $similarities[$row_slug] + 1;
                    }else{
                        $similarities[$row_slug] = 1;
                    }
                }
            }
        }
    }
    arsort($similarities);
    $similar_slugs = array_slice(array_keys($similarities), 0, $settings['column_similar']);  // only the first 6 keys

    $similar = array();
    foreach ($similar_slugs as $s){
        foreach ($items as $i){
            if ($i['slug'] == $s){
                array_push($similar, $i);
            }
        }
    }

    return $similar;
}

function most_popular_in_each_category($reuse_conn=NULL){
    // Return array with single most popular item for each category (keys)

    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }

    $a = [];
    $items = get_from_db("popular", "all", "all", "all", $conn);
    if (array_key_exists('STANDARD_CATEGORIES', $GLOBALS)){
        $cats = array_keys($GLOBALS['STANDARD_CATEGORIES']);
    }else{
        $cats = get_all_categories($conn);
    }
    foreach ($cats as $c){
        $found = false;
        foreach ($items as $h){
            $category_arr = explode(';', $h['categories']);
            if (in_array($c, $category_arr) or $c == "all"){
                $last_of_cat = $h;  // In case no unused match is found
                if (!in_array($h['slug'], array_values($a))){
                    $a[$c] = $h['slug'];
                    $found = true;
                    break;
                }
            }
        }
        if (!$found){
            $a[$c] = $last_of_cat['slug'];
        }
    }

    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $a;
}

function get_all_cats_or_tags($mode, $cat="all", $conn=NULL){
    $db = get_from_db("popular", "all", $cat, "all", $conn, 0);
    $all_flags = [];
    foreach ($db as $item){
        $flags = explode(";",  str_replace(',', ';', $item[$mode]));
        foreach ($flags as $t){
            $t = strtolower($t);
            if (!in_array($t, $all_flags)){
                array_push($all_flags, $t);
            }
        }
    }
    sort($all_flags);
    return $all_flags;
}

function get_all_categories($conn=NULL){
    // Convenience function
    return get_all_cats_or_tags("categories", "all", $conn);
}

function get_all_tags($cat="all", $conn=NULL){
    // Convenience function
    return get_all_cats_or_tags("tags", $cat, $conn);
}

function get_gallery_renders($all=false, $reuse_conn=NULL){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $row = 0; // Default incase of SQL error
    $sql = "SELECT * FROM gallery";
    if (!$all){
        $sql .= " WHERE favourite=1 OR TIMESTAMPDIFF(DAY, date_added, now()) < 21";
    }
    $sql .= " ORDER BY POWER(clicks+10*click_weight, 0.7)/POWER(ABS(DATEDIFF(date_added, NOW()))+1, 1.1) DESC, clicks DESC, date_added DESC";
    // $sql = "SELECT * FROM gallery WHERE favourite=1 OR TIMESTAMPDIFF(DAY, date_added, now()) < 21 ORDER BY POWER(clicks+10*click_weight, 0.7)/POWER(ABS(DATEDIFF(date_added, NOW()))+1, 1.1) DESC, clicks DESC, date_added DESC";
    $result = mysqli_query($conn, $sql);

    $array = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }
    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $array;
}

function get_commercial_sponsors($reuse_conn=NULL){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $row = 0; // Default incase of SQL error
    $sql = "SELECT * FROM commercial_sponsors";
    $result = mysqli_query($conn, $sql);

    $array = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }
    if (is_null($reuse_conn)){
        $conn->close();
    }

    $active_sponsors = array();
    foreach ($GLOBALS['PATRON_LIST'] as $p){
        if ($p[1] == 6){
            foreach ($array as $r){
                if ($p[0] == $r['name']){
                    array_push($active_sponsors, $r);
                }
            }
        }
    }

    return $active_sponsors;
}

function get_author_info($name, $reuse_conn=NULL){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    $row = 0; // Default incase of SQL error
    $sql = "SELECT * FROM authors WHERE `name` LIKE \"{$name}\"";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    }

    if (is_null($reuse_conn)){
        $conn->close();
    }

    return $row;
}

function make_faq(){
    $conn = db_conn_read_only();
    $sql = "SELECT * FROM faq ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $anchors = explode('+', $row['anchor']);
            foreach ($anchors as $anchor){
                echo "<div class=\"anchor-wrapper\"><a class=\"anchor\" name=\"{$anchor}\"></a></div>";
            }
            echo "<a href=\"#{$anchors[0]}\"><h2>{$row['title']}</h2></a>";
            echo md_to_html($row['content']);
        }
    }
}


// ============================================================================
// Item Grid
// ============================================================================
function make_category_list_ba($sort, $reuse_conn=NULL, $current="all",$category2='', $show_tags=true){

    global $db;


    
    
    $categories0 = $db->select(
    'SELECT * from categories where main_cat_id IS NULL ORDER BY name ASC'    
    );
    
  
    echo "<div class='category-list-wrapper'>";
    echo "<ul id='category-list'>";

   foreach ($categories0 as $c){
        echo '<a href="/textures/'.slugify($c['name']).'"><li title="'.$c['name'].'" class="current-cat"><i class="material-icons">keyboard_arrow_right</i>'.$c['name'].'<div class="num-in-cat">'.getCountInCategory($c['name']).'</div></li></a>';
        
        // If subcategory, get all subs!
        
        if(ucfirst($current)==$c['name']) {
 
            $qsub = "select * from categories where main_cat_id=?";
            
            $subcats = $db->select($qsub,[$c['id']]);
            if($subcats):
            foreach ($subcats as $subcat) {
                
                echo '<a href="/textures/'.slugify($c['name']).'/'.slugify($subcat['name']).'"><li title="'.$subcat['name'].'" class="tag"><i class="material-icons">keyboard_arrow_right</i>'
                        .$subcat['name'].'<div class="num-in-cat">'.getCountInSubCategory($subcat['name'],$c['name']).'</div></li></a>';
                
            }
            endif;
        }
        
   }
   
   
   echo '<a href="/textures/patreon/texture-master"><li title="Texture Master" class=""><i class="material-icons">keyboard_arrow_right</i>Texture Master<div class="num-in-cat">'.getTexMasCount().'</div></li></a>';
   echo '<a href="/textures/patreon/texture-knight"><li title="Texture Knight" class=""><i class="material-icons">keyboard_arrow_right</i>Texture Knight<div class="num-in-cat">'.getTexKnightCount().'</div></li></a>';
   
    /*
     * <a href="/textures/?c=sandstone&amp;s=rough&amp;o=popular"><a href="/textures/?c=sandstone&amp;s=blocks&amp;o=popular"><li class="tag">
     * <i class="material-icons">keyboard_arrow_right</i>Blocks</li></a><li class="tag"><i class="material-icons">keyboard_arrow_right</i>Rough</li></a>
     */
    echo "</ul>";
    echo "</div>";
}


function getTexMasCount() {
    
    global $db;
    return $db->selectValue("select count(id) as t from textures where patreon_level=5 AND real_category<>'Blog'");
}
function getTexKnightCount() {
    
    global $db;
    return $db->selectValue("select count(id) as t from textures where patreon_level=10 AND real_category<>'Blog'");
}

function getCountInSubCategory($subcategoryName,$categoryName) {
    global $db;
    
    $q = "select count(id) as t from textures where date_published<NOW() AND real_category LIKE '".$categoryName.",".$subcategoryName."%'";
            
    return $db->selectValue($q);
}

function getCountInCategory($categoryName) {
    
    global $db;
    
    $q = "select count(id) as t from textures where date_published<NOW() AND real_category LIKE '$categoryName%'";
    return $db->selectValue($q);
    
}

function slugify($text)
{
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

function make_category_list($sort, $reuse_conn=NULL, $current="all", $show_tags=true){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }
    echo "<div class='category-list-wrapper'>";
    echo "<ul id='category-list'>";
    if (array_key_exists('STANDARD_CATEGORIES', $GLOBALS)){
        $categories = array_keys($GLOBALS['STANDARD_CATEGORIES']);
    }else{
        $categories = get_all_categories($conn);
        array_unshift($categories, "all");
    }

    foreach ($categories as $c){
        if ($c){  // Ignore uncategorized
            $num_in_cat = num_items("all", $c, $conn);
            echo "<a href=\"".make_grid_link($sort, "all", $c, "all")."\">";
            echo "<li";
            if (array_key_exists('STANDARD_CATEGORIES', $GLOBALS)){
                echo " title=\"".$GLOBALS['STANDARD_CATEGORIES'][$c]."\"";
            }
            if ($current != "all" && $c == $current){
                echo " class='current-cat'";
            }
            echo ">";
            echo "<i class=\"material-icons\">keyboard_arrow_right</i>";
            echo nice_name($c, "category");
            echo "<div class='num-in-cat'>".$num_in_cat."</div>";
            echo "</li>";
            echo "</a>";

            if ($show_tags && $c != 'all' && $c == $current){
                $tags_in_cat = get_all_tags($c, $conn);
                $last_tag = end($tags_in_cat);
                foreach ($tags_in_cat as $t){
                    echo "<a href=\"".make_grid_link($sort, $t, $c, "all")."\">";
                    echo "<li class='tag";
                    if ($t == $last_tag){
                        echo " last-tag";
                    }
                    echo "'>";
                    echo "<i class=\"material-icons\">keyboard_arrow_right</i>";
                    echo nice_name($t);
                    echo "</li>";
                    echo "</a>";
                }
            }
        }
    }
    echo "</ul>";
    echo "</div>";
}

function make_item_grid_ba_tag($tag){

    $tag = str_replace(array('/','-'), array('',' '), $tag);
    
    global $db;

    $q = "SELECT * FROM textures where date_published<NOW() AND real_tags LIKE '%".$tag."%' ORDER BY DATE(date_published) DESC";
    $items = $db->select($q);

    $html = "";
    if (!$items) {
        $html .= "<p>Sorry! There are no ".$GLOBALS['CONTENT_TYPE_NAME'];
        if ($search != 'all'){
            $html .= " that match the search \"".htmlspecialchars($search)."\"";
        }
        if ($category != 'all'){
            $html .= " in the category \"".nice_name($category, "category")."\"";
        }
        if ($author != 'all'){
            $html .= " by ".$author;
        }
        $html .= " :(</p>";
    }else{
        if ($search != "all"){
            $html .= "<h2 style='padding: 0; margin: 0'>Tag: $tag (";
            $html .= sizeof($items);
            $html .= " results)";
            $html .= "</h2>";
        }
        foreach ($items as $i){
            $html .= make_grid_item($i, $category);
        }
    }
    return $html;
}


function make_item_grid_ba($sort="date_published", $search="all", $category="all",$category2='', $author="all", $conn=NULL, $limit=0,$ispatreon,$patreon_level){
//    $items = get_from_db($sort, $search, $category, $author, $conn, $limit);
  //  echo $category,$category2,$sort;
    
global $db;

//echo $category;//$category2;

    if($ispatreon==0):

 
            if(strlen($category2)>2) {
                
                
                $cat2 = str_replace('-',' ',$category2);
                
                $searchT =  ucwords($category).','.ucwords($cat2);
            } else {
                $searchT = ucwords($category);
            }

            switch($sort) {

                default:
                case 'downloads':
                    $q = "SELECT * FROM textures where date_published<NOW() AND real_category LIKE '".$searchT."%' ORDER BY DATE(date_published) DESC";
                    break;

                case 'date_published':
                    $q = "SELECT * FROM textures where date_published<NOW() AND real_category LIKE '".$searchT."%' ORDER BY DATE(date_published) DESC"; 
                    break;

            }
    endif;

    if($ispatreon==1):
        
            switch($sort) {

                default:
                case 'downloads':
                    if($patreon_level=='texture-master'):
                    $q = "SELECT * FROM textures where date_published<NOW() AND patreon_level=5 AND real_category<>'Blog' ORDER BY download_count ASC";
                    else:
                      $q = "SELECT * FROM textures where date_published<NOW() AND patreon_level=10  AND real_category<>'Blog' ORDER BY download_count ASC";   
                    endif;
                    
                    break;

                case 'date_published':
                    
                    if($patreon_level=='texture-master'):
                    $q = "SELECT * FROM textures where date_published<NOW() AND patreon_level=5 AND real_category<>'Blog' ORDER BY DATE(date_published) DESC"; 
                    else:
                       $q = "SELECT * FROM textures where date_published<NOW() AND patreon_level=10 AND real_category<>'Blog' ORDER BY DATE(date_published) DESC";  
                    endif;
                    break;

            }
            
    endif;


    
    $items = $db->select($q);


    $html = "";
    if (!$items) {
        $html .= "<p>Sorry! There are no ".$GLOBALS['CONTENT_TYPE_NAME'];
        if ($search != 'all'){
            $html .= " that match the search \"".htmlspecialchars($search)."\"";
        }
        if ($category != 'all'){
            $html .= " in the category \"".nice_name($category, "category")."\"";
        }
        if ($author != 'all'){
            $html .= " by ".$author;
        }
        $html .= " :(</p>";
    }else{
        if ($search != "all"){
            $html .= "<h2 style='padding: 0; margin: 0'>";
            $html .= sizeof($items);
            $html .= " results";
            $html .= "</h2>";
        }
        foreach ($items as $i){
            $html .= make_grid_item($i, $category);
        }
    }
    return $html;
}

function make_item_grid_ba_anasayfa(){

    $items = getLatestTextures();

    $html = "";
    
    if (!$items) {
       // $html .= " :(</p>";
    }else{
       // $html .= "<h2 style='padding: 0; margin: 0'>Latest Textures</h2>";
        
            
        foreach ($items as $i){
            $html .= make_grid_item_anasayfa($i);
        }
    }
    return $html;
}

function make_item_grid_search_ba($sort="date_published", $search, $category,$category2,$mainpage){
//    $items = get_from_db($sort, $search, $category, $author, $conn, $limit);
        
  
    $search= strtolower($search);
    
global $db;

//echo $category;//$category2;

    if(strlen($category2)>2) {
        $searchT = ucfirst($category).','.ucfirst($category2);
    } else {
        $searchT = ucfirst($category);
    }
    
    switch($sort) {
        
        default:
        case 'downloads':
            if($mainpage=='yes') {
                $q = "SELECT * FROM textures where date_published<NOW() AND (LOWER(real_tags) LIKE '%".$search."%' OR LOWER(real_tags) LIKE '".$search."%' OR LOWER(name) LIKE '%".$search."%' OR LOWER(name) LIKE '".$search."%') ORDER BY download_count DESC";
    
            } else {
              //$q = "SELECT * FROM textures where date_published<NOW() AND (LOWER(name) LIKE '%".$search."%' OR LOWER(name) LIKE '".$search."%') AND real_category LIKE '".$searchT."%' ORDER BY download_count DESC";  
                $q = "SELECT * FROM textures where date_published<NOW() AND (LOWER(real_tags) LIKE '%".$search."%' OR LOWER(real_tags) LIKE '".$search."%' OR LOWER(name) LIKE '%".$search."%' OR LOWER(name) LIKE '".$search."%') ORDER BY date_published DESC";  

            }
            
            break;
        
        case 'date_published':
             if($mainpage=='yes') {
                  $q = "SELECT * FROM textures where date_published<NOW() AND (LOWER(real_tags) LIKE '%".$search."%' OR LOWER(real_tags) LIKE '".$search."%' OR LOWER(name) LIKE '%".$search."%' OR LOWER(name) LIKE '".$search."%') ORDER BY DATE(date_published) DESC"; 
             } else {
                  //$q = "SELECT * FROM textures where date_published<NOW() AND (LOWER(name) LIKE '%".$search."%' OR LOWER(name) LIKE '".$search."%') AND real_category LIKE '".$searchT."%' ORDER BY DATE(date_published) DESC"; 
                    $q = "SELECT * FROM textures where date_published<NOW() AND (LOWER(real_tags) LIKE '%".$search."%' OR LOWER(real_tags) LIKE '".$search."%' OR LOWER(name) LIKE '%".$search."%' OR LOWER(name) LIKE '".$search."%') ORDER BY date_published DESC";  
             }
           
            break;
         
    }

        
    $items = $db->select($q);

    $html = "";
    if (!$items) {
        $html .= "<h2>Sorry! There are no ".$GLOBALS['CONTENT_TYPE_NAME'];
            $html .= " that match the search \"".htmlspecialchars($search)."\"</h2></div>";
            
      
    }else{
        if ($search != "all"){
            $html .= "<h2 style='padding: 0; margin: 0'>";
            $html .= sizeof($items);
            $html .= " results";
            $html .= "</h2></div>";
        }
        foreach ($items as $i){
            $html .= make_grid_item($i, $category);
        }
    }
    return $html;
}


function make_item_grid($sort="date_published", $search="all", $category="all",$category2='', $author="all", $conn=NULL, $limit=0){
//    $items = get_from_db($sort, $search, $category, $author, $conn, $limit);

global $db;

    $items = $db->select("SELECT * FROM textures where categories in(?) and tags=?",
            [$category,$category2]
            );

          
    $html = "";
    if (!$items) {
        $html .= "<p>Sorry! There are no ".$GLOBALS['CONTENT_TYPE_NAME'];
        if ($search != 'all'){
            $html .= " that match the search \"".htmlspecialchars($search)."\"";
        }
        if ($category != 'all'){
            $html .= " in the category \"".nice_name($category, "category")."\"";
        }
        if ($author != 'all'){
            $html .= " by ".$author;
        }
        $html .= " :(</p>";
    }else{
        if ($search != "all"){
            $html .= "<h2 style='padding: 0; margin: 0'>";
            $html .= sizeof($items);
            $html .= " results";
            $html .= "</h2>";
        }
        foreach ($items as $i){
            $html .= make_grid_item($i, $category);
        }
    }
    return $html;
}


// ============================================================================
// Patreon
// ============================================================================

function pledge_rank($pledge_amount){
    $pledge_rank = 1;
    if ($pledge_amount >= 5000) {
        $pledge_rank = 6;
    }else if ($pledge_amount >= 2000) {
        $pledge_rank = 5;
    }else if ($pledge_amount >= 1000){
        $pledge_rank = 4;
    }else if ($pledge_amount >= 500){
        $pledge_rank = 3;
    }else if ($pledge_amount >= 300){
        $pledge_rank = 2;
    }
    return $pledge_rank;
}

function get_name_changes($reuse_conn=NULL){
    if (is_null($reuse_conn)){
        $conn = db_conn_read_only();
    }else{
        $conn = $reuse_conn;
    }

    $sql = "SELECT * FROM patron_name_mod";
    $result = mysqli_query($conn, $sql);
    $array = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $array[$row['id']] = $row;
        }
    }
    if (is_null($reuse_conn)){
        $conn->close();
    }

    $name_replacements = [];
    $add_names = [];
    $remove_names = [];
    foreach ($array as $i){
        $n_from = $i['n_from'];
        $n_to = $i['n_to'];
        if ($n_to and $n_from){
            $name_replacements[$n_from] = $n_to;
        }else if($n_to and !$n_from){
            $add_names[$n_to] = $i['rank'];
        }else{
            array_push($remove_names, $n_from);
        }
    }

    return [$name_replacements, $add_names, $remove_names];
}

function get_patreon(){
    $patreoncache = $_SERVER['DOCUMENT_ROOT'].'/php/patreon_data/_latest.json';

    // Some users request name change
    $conn = db_conn_read_only();
    list($name_replacements, $add_names, $remove_names) = get_name_changes($conn);

  
    // Cache to avoid overusing Patreon API
    $cachetime = 200;  // How many minutes before the cache is invalid
    $cachetime *= 60;  // convert to seconds
    if (file_exists($patreoncache)) {
        if (time() - $cachetime < filemtime($patreoncache)){
            // echo "<!-- Patreon cache ".date('H:i', filemtime($patreoncache))." -->\n";
            $str = file_get_contents($patreoncache);
            return json_decode($str, true);
        }else{
            // Keep old cache file for statistical purposes
            rename($patreoncache, $_SERVER['DOCUMENT_ROOT'].'/php/patreon_data/'.time().'.json');
        }
    }
 
    $patreon_tokens = [];
    $patreon_tokens_path = $_SERVER['DOCUMENT_ROOT'].'/php/patreon_tokens.json';
    if (file_exists($patreon_tokens_path)){
        $str = file_get_contents($patreon_tokens_path);

 

        $patreon_tokens = json_decode($str, true);
    }
    $access_token = $patreon_tokens["access_token"];
    $refresh_token = $patreon_tokens["refresh_token"];
    $api_client = new Patreon\API($access_token);
    // Get your campaign data
    $campaign_response = $api_client->fetch_campaign();

    // If the token doesn't work, get a newer one
    if ($campaign_response['errors']) {
        echo "Got an error\n";
        echo "Refreshing tokens\n";
        // Make an OAuth client
        $client_id = $GLOBALS['CLIENT_ID'];
        $client_secret = $GLOBALS['CLIENT_SECRET'];
        $oauth_client = new Patreon\OAuth($client_id, $client_secret);
        // Get a fresher access token
        $tokens = $oauth_client->refresh_token($refresh_token, null);
        debug_email("Patreon Tokens", json_encode($tokens, JSON_PRETTY_PRINT));
        if ($tokens['access_token']) {
            $access_token = $tokens['access_token'];
            $fp = fopen($patreon_tokens_path, 'w');
            fwrite($fp, json_encode($tokens));
            fclose($fp);
            echo "Got a new access_token!";
        } else {
            echo "Can't fetch new tokens. Please debug, or write in to Patreon support.\n";
            print_r($tokens);
        }
        $api_client = new Patreon\API($access_token);
        $campaign_response = $api_client->fetch_campaign();
    }

    // get page after page of pledge data
    $campaign_id = $campaign_response['data'][0]['id'];
    $cursor = null;
    $patron_list = [];
    $total_earnings_c = 0;
    while (true) {
        $pledges_response = $api_client->fetch_page_of_pledges($campaign_id, 25, $cursor);
        // get all the users in an easy-to-lookup way
        $user_data = [];
        foreach ($pledges_response['included'] as $included_data) {
            if ($included_data['type'] == 'user') {
                $user_data[$included_data['id']] = $included_data;
            }
        }
        // loop over the pledges to get e.g. their amount and user name
        foreach ($pledges_response['data'] as $pledge_data) {
            $declined = $pledge_data['attributes']['declined_since'];
            if (!$declined){
                $pledge_amount = $pledge_data['attributes']['amount_cents'];
                $total_earnings_c += $pledge_amount;
                $pledge_rank = pledge_rank($pledge_amount);

                $patron_id = $pledge_data['relationships']['patron']['data']['id'];
                $patron_full_name = $user_data[$patron_id]['attributes']['full_name'];

                if (array_key_exists($patron_full_name, $name_replacements)){
                    $patron_full_name = $name_replacements[$patron_full_name];
                }

                if (!in_array($patron_full_name, $remove_names)){
                    array_push($patron_list, [$patron_full_name, $pledge_rank]);
                }
            }
        }
        // get the link to the next page of pledges
        $next_link = $pledges_response['links']['next'];
        if (!$next_link) {
            // if there's no next page, we're done!
            break;
        }
        // otherwise, parse out the cursor param
        $next_query_params = explode("?", $next_link)[1];
        parse_str($next_query_params, $parsed_next_query_params);
        $cursor = $parsed_next_query_params['page']['cursor'];
    }
    foreach (array_keys($add_names) as $p){
        array_splice($patron_list, rand(0, sizeof($patron_list)-1), 0, [[$p, $add_names[$p]]]);
    }

    $tmp = $campaign_response['included'];
    $goals = [];
    foreach ($tmp as $x){
        if ($x['type'] == 'goal'){
            array_push($goals, $x['attributes']);
        }
    }

    $goals = array_sort($goals, "amount_cents", SORT_ASC);

    $data = [$patron_list, $total_earnings_c/100, $goals];

    // Write to cache
    file_put_contents($patreoncache, json_encode($data, JSON_PRETTY_PRINT));
    return $data;
}


function goal_title($g){
    $d = $g['description'];
    $bits = explode("</strong>", $d);
    $t = $bits[0];
    $t = str_replace("<strong>", "", $t);
    $t = str_replace("<br>", "", $t);
    return $t;
}



function generate_category_sitemap() {
   // header('Content-type: application/xml; charset=utf-8');
    $a.= '<?xml version="1.0" encoding="UTF-8"?>';
    $a.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';


    $categories =getAllMainCategories();

    foreach ($categories as $t){

        $a.= "<url>".PHP_EOL;
        $a.= "<loc>https://".$_SERVER['HTTP_HOST']."/textures/".to_slug($t['name'])."/</loc>".PHP_EOL;
        $a.= "</url>".PHP_EOL;

        // Subcats?

        $subcats = getSubcategories($t['id']);

        foreach ($subcats as $subcat) {
                $a.= "<url>".PHP_EOL;
                $a.= "<loc>https://".$_SERVER['HTTP_HOST']."/textures/".to_slug($t['name'])."/".to_slug($subcat['name'])."/</loc>".PHP_EOL;
                $a.= "</url>".PHP_EOL;
        }
    }

    $a.= '</urlset>';

    file_put_contents('sitemap_categories.xml', $a);
}


function generate_blog_sitemap() {
    
    
    $a.=  '<?xml version="1.0" encoding="UTF-8"?>';
    $a.=  '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

    $blogs = getAllBlogs();
    
    foreach ($blogs as $t){
    
    $timet0 = explode(' ',$t['date_published']);

    $a.=  "<url>".PHP_EOL;
    $a.=  "<loc>https://".$_SERVER['HTTP_HOST']."/".$t['slug']."</loc>".PHP_EOL;
    $a.=  "<lastmod>".$timet0[0]."</lastmod>".PHP_EOL;
    
    $a.=  "<image:image>".PHP_EOL;
    $a.=  "<image:loc>https://".$_SERVER['HTTP_HOST']."/".$t['image_featured']."</image:loc>".PHP_EOL;
    $a.=  "</image:image>".PHP_EOL;
    
    $a.=  "</url>".PHP_EOL;
}

    $a.=  '</urlset>';

    file_put_contents('sitemap_blog.xml', $a);

}

function generate_tag_sitemap() {
    
 $a.= '<?xml version="1.0" encoding="UTF-8"?>';
 $a.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

$textures = taglister();

foreach ($textures as $t){
    
     $a.= "<url>".PHP_EOL;
     $a.= "<loc>https://".$_SERVER['HTTP_HOST']."/tag/".$t."/</loc>".PHP_EOL;
     $a.= "</url>".PHP_EOL;
    
    
    }
    
     $a.= '</urlset>';
    file_put_contents('sitemap_tags.xml',  $a);

}

function generate_texture_sitemap() {

    $a.= '<?xml version="1.0" encoding="UTF-8"?>';
    $a.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

    $textures = getSitemapTextures(10000);

    foreach ($textures as $t){

        $gimages = getGalleryImage($t['id']);
        $timet0 = explode(' ',$t['date_published']);

        $a.= "<url>".PHP_EOL;
        $a.= "<loc>https://".$_SERVER['HTTP_HOST']."/textures/".$t['slug']."</loc>".PHP_EOL;
        $a.= "<lastmod>".$timet0[0]."</lastmod>".PHP_EOL;

        foreach ($gimages as $image) {

        $a.= "<image:image>".PHP_EOL;
        $a.= "<image:loc>https://".$_SERVER['HTTP_HOST']."/".$image['image_url']."</image:loc>".PHP_EOL;
        $a.="<image:title><![CDATA[".$image['image_title']."]]></image:title>".PHP_EOL;
        $a.= "<image:caption><![CDATA[[".$image['image_caption']."]]></image:caption>".PHP_EOL;
        $a.= "</image:image>".PHP_EOL;


        }

        $a.="</url>".PHP_EOL;
    }

    $a.= '</urlset>';

file_put_contents('sitemap_textures.xml', $a);
}

function sitemap() {
    generate_blog_sitemap();
    generate_category_sitemap();
    generate_tag_sitemap();
    generate_texture_sitemap();
}

?>
				