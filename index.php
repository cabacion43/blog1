<?php require_once('Connections/blogconnect.php'); ?>
<?php require_once('manager/menul.php'); ?>
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
}}

$maxRows_postlist = 3;
$pageNum_postlist = 0;
if (isset($_GET['pageNum_postlist'])) {
  $pageNum_postlist = $_GET['pageNum_postlist'];
}
$startRow_postlist = $pageNum_postlist * $maxRows_postlist;

mysql_select_db($database_blogconnect, $blogconnect);
$query_postlist = "SELECT * FROM postblog";
$query_limit_postlist = sprintf("%s LIMIT %d, %d", $query_postlist, $startRow_postlist, $maxRows_postlist);
$postlist = mysql_query($query_limit_postlist, $blogconnect) or die(mysql_error());
$row_postlist = mysql_fetch_assoc($postlist);

if (isset($_GET['totalRows_postlist'])) {
  $totalRows_postlist = $_GET['totalRows_postlist'];
} else {
  $all_postlist = mysql_query($query_postlist);
  $totalRows_postlist = mysql_num_rows($all_postlist);
}
$totalPages_postlist = ceil($totalRows_postlist/$maxRows_postlist)-1;
?>
<html> /*sdsdssdsdsdsd*/
  <head>
    <title>WebProject</title>
    <meta content="">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style></style>
  </head>
  <body>
    <nav class="flexContainer blueBackground navbar">
      <ul class="nav flexItem flexStart">
           <li><a href="html/index.html">Blog</a></li>
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

<a href="login.php">Log in</a>
<?php
};
?></li>
      </ul>
    </nav><!--basic head, adica bara de meniu care trebuie sa fie obligatoriu pe fiecare pagina-->
    <div class="flexContainer flexItem">
       <aside class="sidebar main">
               <h2>Main sidebar</h2>
              <?php do { ?>
  <h2><a href="post.php?id=<?php echo $row_postlist['id']; ?>"><?php echo $row_postlist['title']; ?></a></h2>
  <?php echo $row_postlist['anotat']; ?>
  <?php } while ($row_postlist = mysql_fetch_assoc($postlist)); ?>
      </aside>
       <aside class=" sidebarLeft">
           <h2>Lorem</h2>
           <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
       </aside>



</div>
   <footer class="footer">&copy; Cabac Ion</footer>
</body>
</html>
<?php
mysql_free_result($postlist);
?>
