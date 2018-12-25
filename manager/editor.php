<?php require_once('../Connections/blogconnect.php'); ?>
<?php require_once('menul.php'); ?>
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

$maxRows_listpost = 15;
$pageNum_listpost = 0;
if (isset($_GET['pageNum_listpost'])) {
  $pageNum_listpost = $_GET['pageNum_listpost'];
}
$startRow_listpost = $pageNum_listpost * $maxRows_listpost;

mysql_select_db($database_blogconnect, $blogconnect);
$query_listpost = "SELECT * FROM postblog";
$query_limit_listpost = sprintf("%s LIMIT %d, %d", $query_listpost, $startRow_listpost, $maxRows_listpost);
$listpost = mysql_query($query_limit_listpost, $blogconnect) or die(mysql_error());
$row_listpost = mysql_fetch_assoc($listpost);

if (isset($_GET['totalRows_listpost'])) {
  $totalRows_listpost = $_GET['totalRows_listpost'];
} else {
  $all_listpost = mysql_query($query_listpost);
  $totalRows_listpost = mysql_num_rows($all_listpost);
}
$totalPages_listpost = ceil($totalRows_listpost/$maxRows_listpost)-1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>WebProject</title>
<meta content="">
<link rel="stylesheet" type="text/css" href="../style.css ">
<style></style>
</head>
<body>
<nav class="flexContainer blueBackground">
  <ul class="nav flexItem flexStart">
    <li><a href="../index.php">Blog</a></li>
  </ul>
  <ul class="nav flexContainer flexEnd">
    <li><a href="../index.php">Home</a></li>
    <li><a href="../html/register.html">Register</a></li>
    <li>
      <?php 
if (isset ($_SESSION['MM_Username'])){?>
      <a href="<?php echo $logoutAction ?>">Log out</a>
      <?php };
?>
      <?php 
if (!isset ($_SESSION['MM_Username'])){
	?>
      <a href="index.php">Log in</a>
      <?php
};
?>
    </li>
  </ul>
</nav>
<h1> <a href="addpost.php">Add postarea </a></h1>
<center>
<?php if ($totalRows_listpost > 0) { // Show if recordset not empty ?>
  <table class="rwd-table" border="1">
    <tr>
      <td width="76">id</td>
      <td width="243">title</td>
      <td width="213">Action</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><?php echo $row_listpost['id']; ?></td>
        <td><?php echo $row_listpost['title']; ?></td>
        <td><p><a href="editpost.php?id=<?php echo $row_listpost['id']; ?>">Edit</a></p>
          <p><a href="delpost.php?id=<?php echo $row_listpost['id']; ?>" onClick="confirm('Esti sigur?')">Delete</a></p></td>
      </tr>
      <?php } while ($row_listpost = mysql_fetch_assoc($listpost)); ?>
  </table>
  </center>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_listpost == 0) { // Show if recordset empty ?>
  <p>Date nu sunt</p>
  <?php } // Show if recordset empty ?>
<footer class="footer">&copy; Cabac Ion</footer>
</body>
</html>
<?php
mysql_free_result($listpost);
?>
