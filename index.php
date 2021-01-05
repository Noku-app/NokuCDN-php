<?php

require "functions.php";

if(isset($_SERVER['REQUEST_URI'])){
  $uri = substr($_SERVER['REQUEST_URI'], 1);
  if(strlen($uri) == 64){
    $hash = preg_replace("/[^A-F0-9]/", '', $uri);

    $conn = getNokuDB();
    $res = quick_select(['data', 'mime_type'], $_CDN['DB_TABLE'], 'hash', $hash, $conn, false);
    if($res->num_rows == 0){
      die("<h1>404: RESOURCE NOT FOUND</h1>");
    } else {
      $data = $res->fetch_assoc();
      header("Content-type:".$data['mime_type']);
      header("Cache-Control: immutable");
      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
      echo gzinflate(base64_decode($data['data']));
      exit();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>NokuCDN</title>
  <link rel="stylesheet" href="noku.css">
</head>
<body>
<div class="header">
  <?php print_header(); ?>
</div>
<div class="content">
  <div class="column">
    <?php print_column(); ?>
  </div>
  <div class="main">
    <h1>What is a <u>CDN</u>, and what does it do?</h1>
    <p>
      A <u>C</u>ontent <u>D</u>elivery <u>N</u>etwork is a service that provides
      content either as a module to a projects, or as a central server for many projects to
      refrence from. Such of an example are <a href="https://bootstrap.com">BootStrap's</a>
      CSS and JS files. When a browser detects that different sites link to the same source,
      the browser can reuse the source since it already knows the contents it cached when a
      different site accessed it earlier.
    </p>
    <h1>What is the purpose of this software?</h1>
    <p>
      This software enables you to manage the content served on your server with this CDN as
      well as manage the CDN itself. With this software you can:
    <ul>
      <li>- View recently uploaded content by user.</li>
      <li>- Directly insert a file into this CDN.<li>
      <li>- Update or even Remove content in this CDN.</li>
      <li>- Create additional users that can sign in and perform tasks.</li>
    </ul>
    </p>
    <h1>Version and About</h1>
    <?php print_version(); ?>
  </div>
</div>
<div class="footer">
  <?php print_footer(); ?>
</div>
</body>
</html>
