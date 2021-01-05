<?php
require "functions.php";

if($_FILES['pass']['size'] == 0) die("<h1>403: NULL PASSWORD</h1>");
$pass = file_get_contents($_FILES['pass']['tmp_name']);
if(!str_equal(base64_encode($pass), $_CDN['AUTH_TOKEN'])) die("<h1>403: FORBIDDEN</h1>");

//if($_FILES['file']['size'] == 0) die("<h1>401: NULL FILE</h1>");
if($_FILES['file']['size'] > (15 * 1024 * 1024)) die("<h1>401: FILE TOO LARGE</h1>");

if(!isset($_POST['uid'])) die("<h1>401: UID NOT SET</h1>");
if(!isset($_POST['original'])) $_POST['original'] = false;

$data = null;
if($_FILES['file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['file']['tmp_name'])) {
  $data = file_get_contents($_FILES['file']['tmp_name']);
} else die("<h1>401: FILE READ ERROR</h1>");

// Call outside functions to deal with data before compression and encoding.
// TODO Extra calls to external functions for processing

$hash = strtoupper(hash_file("sha256", $_FILES['file']['tmp_name']));
$store = base64_encode(gzdeflate($data, 8));

$conn = getNokuDB();
$worked = quick_insert([
  'uid' => $_POST['uid'],
  'data' => $store,
  'hash' => $hash,
  'mime_type' => mime_content_type($_FILES['file']['tmp_name'])
], $_CDN['DB_TABLE'], $conn, false);

if($worked){
  header('Location: /'.$hash);
}