<?php
require_once("../getID3-master/getid3/getid3.php");


function formatSizeUnits($bytes)
{
  if ($bytes >= 1073741824) {
    $bytes = number_format($bytes / 1073741824, 2) . ' GB';
  } elseif ($bytes >= 1048576) {
    $bytes = number_format($bytes / 1048576, 2) . ' MB';
  } elseif ($bytes >= 1024) {
    $bytes = number_format($bytes / 1024, 2) . ' KB';
  } elseif ($bytes > 1) {
    $bytes = $bytes . ' bytes';
  } elseif ($bytes == 1) {
    $bytes = $bytes . ' byte';
  } else {
    $bytes = '0 bytes';
  }

  return $bytes;
}

function getDirContents($dir, &$results = array())
{
  $files = scandir($dir);

  foreach ($files as $key => $value) {
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
    if (!is_dir($path)) {
      $results[] = $path;
    } else if ($value != "." && $value != "..") {
      getDirContents($path, $results);
      $results[] = $path;
    }
  }

  return $results;
}

function checkDomain()
{

  // $referer = parse_url($_SERVER['HTTP_REFERER']);
  // $allowedDomain = 'yourdomain.com';

  // if ($referer['host'] == $allowedDomain) {
  //   //Process your mail script here.
  // }

  $allowedDomains = array('www.abc.com', 'www.xyz.com', 'localhost');
  $referer = $_SERVER['HTTP_REFERER'];
  $domain = parse_url($referer); //If yes, parse referrer
  if (in_array($domain['host'], $allowedDomains)) {
    //Run your code here which will process the $_POST
    echo "allowed";
    exit();
  } else {
    echo "you are not allowed to post at this page";
    exit(); //Stop running the script
  }
}


$getID3 = new getID3;
$allFiles = getDirContents('../upload/');
echo "Total Files : " . count($allFiles);
echo "<br/>";
echo "--------------------------------------";

foreach ($allFiles as $file) {
  $file = $getID3->analyze($file);
  if (isset($file['error'])) {
    continue;
  } else {
    echo "<br/>";
    echo "Time: " . gmdate("H:i:s", $file['playtime_seconds']);
    echo "<br/>";
    echo "mineType: " . $file['mime_type'];
    echo "<br/>";
    echo "Format: " . $file['fileformat'];
    echo "<br/>";
    echo "Name: " . $file['filename'];
    echo "<br/>";
    echo "Size: " . formatSizeUnits($file['filesize']);
    echo "<br/>";
    echo "Resolution: " . $file['video']['resolution_x'] . " x " . $file['video']['resolution_y'];
    echo "<br/>";
  }

  echo "--------------------------------------";
  echo "<br/>";
}