<?php

/**
 * Accept processing upload files
 */
/* Set cross-domain headers
  header('Access-Control-Allow-Origin:*');
  header('Access-Control-Allow-Methods:PUT,POST,GET,DELETE,OPTIONS');
  header('Access-Control-Allow-Headers:x-requested-with,content-type');
 */

header("Content-type: text/html; charset=utf-8");

//echo "<pre>";
//print_r($_FILES);exit;


$file = isset($_FILES['file_data']) ? $_FILES['file_data'] : null; //Segmented file

$name = isset($_POST['file_name']) ? '../upload/' . $_POST['file_name'] : null; //The name of the file to save

$total = isset($_POST['file_total']) ? $_POST['file_total'] : 0; //Total number of pieces

$index = isset($_POST['file_index']) ? $_POST['file_index'] : 0; //Current number of slices

$md5 = isset($_POST['file_md5']) ? $_POST['file_md5'] : 0; //Md5 value of the file

$size = isset($_POST['file_size']) ? $_POST['file_size'] : null; //File size

echo 'Current number of slices：' . $index . PHP_EOL;

if (!$file || !$name) {
    echo 'failed';
    die();
}

if ($file['error'] == 0) {
    //Check if the file exists
    if (!file_exists($name)) {
        if (!move_uploaded_file($file['tmp_name'], $name)) {
            echo 'success';
        }
    } else {
        $content = file_get_contents($file['tmp_name']);
        if (!file_put_contents($name, $content, FILE_APPEND)) {
            echo 'failed';
        }
        echo 'success';
    }
} else {
    echo 'failed';
}
 