<?php require_once('Connections/blogconnect.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_postview = "-1";
if (isset($_GET['id'])) {
  $colname_postview = $_GET['id'];
}
mysql_select_db($database_blogconnect, $blogconnect);
$query_postview = sprintf("SELECT * FROM postblog WHERE id = %s", GetSQLValueString($colname_postview, "int"));
$postview = mysql_query($query_postview, $blogconnect) or die(mysql_error());
$row_postview = mysql_fetch_assoc($postview);
$totalRows_postview = mysql_num_rows($postview);

$maxRows_coment = 10;
$pageNum_coment = 0;
if (isset($_GET['pageNum_coment'])) {
  $pageNum_coment = $_GET['pageNum_coment'];
}
$startRow_coment = $pageNum_coment * $maxRows_coment;

$colname_coment = "-1";
if (isset($_GET['id'])) {
  $colname_coment = $_GET['id'];
}
mysql_select_db($database_blogconnect, $blogconnect);
$query_coment = sprintf("SELECT * FROM `comment` WHERE idpost = %s", GetSQLValueString($colname_coment, "int"));
$query_limit_coment = sprintf("%s LIMIT %d, %d", $query_coment, $startRow_coment, $maxRows_coment);
$coment = mysql_query($query_limit_coment, $blogconnect) or die(mysql_error());
$row_coment = mysql_fetch_assoc($coment);

if (isset($_GET['totalRows_coment'])) {
  $totalRows_coment = $_GET['totalRows_coment'];
} else {
  $all_coment = mysql_query($query_coment);
  $totalRows_coment = mysql_num_rows($all_coment);
}
$totalPages_coment = ceil($totalRows_coment/$maxRows_coment)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>WebProject</title>
    <meta content="">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style></style>
  </head>

<body>
    <nav class="flexContainer blueBackground navbar">
      <ul class="nav flexItem flexStart">
           <li><a href="html/home.html">Blog</a></li>
      </ul>
      <ul class="nav flexContainer flexEnd">
          <li><a href="index.php">Home</a></li>
          <li><a href="manager/register.php">Register</a></li>
          <li><?php 
if (isset ($_SESSION['MM_Username'])){
	?>

<a href="<?php echo $logoutAction ?>">Log out</a>
<?php
};
?>
<?php 
if (!isset ($_SESSION['MM_Username'])){
	?>

<a href="manager/index.php">Log in</a>
<?php
};
?></li>
      </ul>
    </nav>
    <div class="flexContainer flexItem">
       <aside class="sidebar main">
               <h2><?php echo $row_postview['title']; ?></h2>
<p><?php echo $row_postview['text']; ?></p>
<a href="manager/addcomment.php?idpost=<?php echo $row_postview['id']; ?>">add coment</a>
<?php do { ?>
  <p><?php echo $row_coment['text']; ?></p>
  <?php } while ($row_coment = mysql_fetch_assoc($coment)); ?>
      </aside>
<aside class=" sidebarLeft">
           <h2>Comments</h2>
           <p>Put your content here</p>
       </aside>
        </div>
   <footer class="footer">&copy; Cabac Ion</footer>
</body>
</html>
<?php
mysql_free_result($postview);

mysql_free_result($coment);
?>
