<?php
  require "functions.php";
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
    <form method="post" action="post" enctype="multipart/form-data" class="form">
      <div class="form-header">
        <h1>Upload File</h1>
      </div>
      <div class="row clearfix">
        <label for="file-button" class="float-left">Select to Upload:</label>

        <label for="file" id="file-button" class="file-button float-right">
          <input type="file" name="file" id="file">
          Choose
        </label>
      </div>
      <div class="row clearfix">
        <label for="file-button" class="float-left">Key File:</label>

        <label for="pass" id="file-button" class="file-button float-right">
          <input type="file" name="pass" id="pass">
          Choose
        </label>
      </div>
      <div class="row clearfix">
        <label for="uid" class="float-left">User ID:</label>
        <input type="number" name="uid" id="uid" class="float-right">
      </div>
      <div class="row clearfix">
        <label class="float-left">Options</label>
        <div class="float-right">
          <input type="checkbox" name="original" id="original">
          <label for="original" class="checkbox-label">Keep Original Image</label>
        </div>
      </div>
      <div class="row">
        <button type="submit" class="block-button">Upload</button>
      </div>
    </form>
  </div>
</div>
<div class="footer">
  <?php print_footer(); ?>
</div>
</body>
</html>