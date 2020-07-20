<?php

//index.php

$connect = new PDO("mysql:host=localhost;dbname=bumshare_wp1", "bumshare_wp1", "B.FcMBakp8zvzeQafpC75");

$query = "SELECT * FROM `textures` where DATE(date_published)<CURDATE() ORDER BY id DESC LIMIT 15"; 

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

header("Content-Type: text/xml;charset=iso-8859-1");

$base_url = "https://www.sharetextures.com/";

echo "<?xml version='1.0' encoding='UTF-8' ?>" . PHP_EOL;
echo "<rss version='2.0'>".PHP_EOL;
echo "<channel>".PHP_EOL;
echo "<title>Free CC0 PBR Textures | RSS</title>".PHP_EOL;
echo "<link>".$base_url."index.php</link>".PHP_EOL;
echo "<description>This is RSS feed of sharetextures.com Sharetextures.com creating free pbr textures.</description>".PHP_EOL;
echo "<language>en-us</language>".PHP_EOL;

foreach($result as $row)
{
 $publish_Date = date("D, d M Y H:i:s T", strtotime($row["date_published"]));
 $image_size_array = get_headers($base_url . "".$row["image_featured"], 1);
 $image_size = $image_size_array["Content-Length"];
 $image_mime_array = getimagesize($base_url . "".$row["image_featured"]);
 $image_mime = $image_mime_array["mime"];
 
 echo "<item xmlns:dc='ns:1'>".PHP_EOL;
 echo "<title>".$row["name"]."</title>".PHP_EOL;
 echo "<link>".$base_url."textures/".$row["slug"]."</link>".PHP_EOL;
 echo "<pubDate>".$publish_Date."</pubDate>".PHP_EOL;
 echo "<dc:creator>".$row["author"]."</dc:creator>".PHP_EOL;
 echo "<description><![CDATA[".substr($row["yoast_metadesc"], 0, 140) ."]]></description>".PHP_EOL;
 echo "<enclosure url='https://www.sharetextures.com/".$row["image_featured"]."' length='".$image_size."' type='".$image_mime."' />".PHP_EOL;
 echo "<category><![CDATA[".substr($row["categories"], 0, 100) ."]]></category>".PHP_EOL;
 echo "</item>".PHP_EOL;
}

echo '</channel>'.PHP_EOL;
echo '</rss>'.PHP_EOL;

?>