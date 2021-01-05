<?php
require "functions.php";

$valid = false;
foreach (getallheaders() as $name => $value) {
  if(str_equal($name, "Authorization")){
    $valid = str_equal(trim($value), $_CDN['AUTH_TOKEN']);
    break;
  }
}
if(!$valid) die("<h1>403: FORBIDDEN</h1>");

$_POST = json_decode(file_get_contents('php://input'), true);

if(!isset($_POST['file'])) die("<h1>404: RESOURCE NOT FOUND</h1>");
if(!isset($_POST['uid'])) die("<h1>404: RESOURCE NOT FOUND</h1>");
if(!isset($_POST['mime_type'])) die("<h1>404: RESOURCE NOT FOUND</h1>");
if(!isset($_POST['original'])) $_POST['original'] = false;

$_POST['file'] = base64_decode($_POST['file']);
$data = $_POST['file'];

// Call outside functions to deal with data before compression and encoding.
// TODO Extra calls to external functions for processing

$hash = strtoupper(hash("sha256", $data));
$store = base64_encode(gzdeflate($data, 8));

$conn = getNokuDB();
$worked = quick_insert([
  'uid' => $_POST['uid'],
  'data' => $store,
  'hash' => $hash,
  'mime_type' => $_POST['mime_type']
], $_CDN['DB_TABLE'], $conn, false);

if($worked){
  echo json_encode(['error' => false, 'data' => $hash]);
}

exit();