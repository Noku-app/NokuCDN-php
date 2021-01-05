<?php
require "config.php";
$_CDN = $GLOBALS['NokuCDN'];

function str_equal($str1, $str2){
  if(strcmp($str1, $str2) == 0){
    return true;
  } else {
    return false;
  }
}

function getNokuDB(){
  $_CDN = $GLOBALS['NokuCDN'];

  $USER = $_CDN['DB_USER'];
  $PASS = $_CDN['DB_PASS'];

  $HOST = $_CDN['DB_HOST'];
  $NAME = $_CDN['DB_NAME'];

  $conn = mysqli_connect($HOST, $USER, $PASS, $NAME);
  if(!$conn){
    die("Connection failure: ".mysqli_connect_error());
  }
  return $conn;
}

function print_version(){
  echo '
<p>
  <strong>Version:</strong> 1.0.3P Beta<br/>
  <strong>Github:</strong> <a href="https://github.com/Noku-app/NokuCDN-java">https://github.com/Noku-app/NokuCDN-java</a><br/>
  <strong>Author:</strong> The Next Guy</br>
  <strong>Company:</strong> Xemplar Softworks, LLC</br>
  <strong>Made For:</strong> Noku App</br>
  <strong>Licensed Under:</strong> AGPL V3.0</br>
</p>
  ';
}
function print_footer(){
  echo '
<p>
  Copyright &#169; Xemplar Softworks, LLC 2020
</p>
  ';
}
function print_column(){
  echo '
<ul class="col">
  <li><a href="/">Index</a></li>
  <li><a href="/recent">Recent</a></li>
  <li><a href="/upload">Upload</a></li>
  <li><a href="/search">Search</a></li>
</ul>
  ';
}
function print_header(){
  echo '
<div class="clearfix header-content">
  <img src="/B6879EF42B3762EAE937981331BFE4F8A5251EBBB7C7E690510B3244E1023A8F" alt="Noku" class="float-left header-image">
  <div class="float-right">
    <h1> NokuCDN</h1>
    <p>Storage Engine for NokuApp\'s Content</p>
  </div>
</div>
  ';
}

function quick_select($fields, $table, $cond_id, $conn_var, $conn = null, $close_db = false) {
  if (is_null($conn)) {
    $conn = getNokuDB();
    $close_db = true;
  }

  $names = '';
  if(!is_array($fields)){
    if(str_equal($fields, '*')){
      $names = '*';
    } else {
      $fields = array($fields);
    }
  }
  if(str_equal($names, '')){
    foreach ($fields as $value) {
      $names .= "`$value`, ";
    }
    $names = substr($names, 0, strlen($names) - 2);
  }

  $query = "SELECT $names FROM $table WHERE $cond_id = ?";
  if (!($stmt = $conn->prepare($query))) {
    echo $conn->error;
  } else {
    $stmt->bind_param("s", $conn_var);
    $stmt->execute();

    $res = $stmt->get_result();

    $stmt->close();
  }
  if($close_db) $conn->close();

  return $res;
}
function quick_select_desc($fields, $table, $cond_id, $conn_var, $conn = null, $close_db = false) {
  if (is_null($conn)) {
    $conn = getNokuDB();
    $close_db = true;
  }

  $names = '';
  if(!is_array($fields)){
    if(str_equal($fields, '*')){
      $names = '*';
    } else {
      $fields = array($fields);
    }
  }
  if(str_equal($names, '')){
    foreach ($fields as $value) {
      $names .= "`$value`, ";
    }
    $names = substr($names, 0, strlen($names) - 2);
  }

  $query = "SELECT $names FROM $table WHERE $cond_id = ? ORDER BY id DESC";
  if (!($stmt = $conn->prepare($query))) {
    echo $conn->error;
  } else {
    $stmt->bind_param("s", $conn_var);
    $stmt->execute();

    $res = $stmt->get_result();

    $stmt->close();
  }
  if($close_db) $conn->close();

  return $res;
}
function quick_delete($table, $cond_id, $conn_var, $conn = null, $close_db = false) {
  if (is_null($conn)) {
    $conn = getNokuDB();
    $close_db = true;
  }

  $ret = false;
  $query = "DELETE FROM $table WHERE $cond_id = ?";
  if (!($stmt = $conn->prepare($query))) {
    echo $conn->error;
  } else {
    $stmt->bind_param("s", $conn_var);
    $ret = $stmt->execute();

    $stmt->close();
  }
  if($close_db) $conn->close();

  return $ret;
}
function quick_update($fields, $table, $cond_id, $conn_var, $conn = null, $close_db = false){
  if(is_null($conn)){
    $conn = getNokuDB();
    $close_db = true;
  }

  $names = "";
  foreach ($fields as $col => $value){
    $names .= "`$col` = '$value', ";
  }
  $names = substr($names, 0, strlen($names) - 2);

  $query = "UPDATE $table SET $names WHERE $cond_id = ?";
  if(!($stmt = $conn->prepare($query))) {
    echo $conn->error;
  } else {
    $stmt->bind_param("s", $conn_var);
    $res = $stmt->execute();
    $stmt->close();
  }
  if($close_db) $conn->close();

  return $res;
}
function quick_insert($fields, $table, $conn = null, $close_db = false){
  if(is_null($conn)){
    $conn = getNokuDB();
    $close_db = true;
  }

  $holder = "";
  $values = [];
  $names = "";
  $types = "";

  foreach ($fields as $col => $value){
    $values[] = $value;
    $names  .= "$col, ";
    $holder .= "?, ";
    $types  .= "s";
  }
  $names = substr($names, 0, strlen($names) - 2);
  $holder = substr($holder, 0, strlen($holder) - 2);

  $query = "INSERT INTO $table ($names) VALUES ($holder)";
  if(!($stmt = $conn->prepare($query))) {
    echo $conn->error;
  } else {
    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $stmt->close();
  }
  if($close_db) $conn->close();

  return true;
}