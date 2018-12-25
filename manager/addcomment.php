<?php require_once('../Connections/blogconnect.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO `comment` (id, iduser, idpost, text) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['id'], "int"),
                       GetSQLValueString($_POST['iduser'], "int"),
                       GetSQLValueString($_POST['idpost'], "int"),
                       GetSQLValueString($_POST['text'], "text"));

  mysql_select_db($database_blogconnect, $blogconnect);
  $Result1 = mysql_query($insertSQL, $blogconnect) or die(mysql_error());

  $insertGoTo = "../index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_commentadd = "-1";
if (isset($_GET['idpost'])) {
  $colname_commentadd = $_GET['idpost'];
}
mysql_select_db($database_blogconnect, $blogconnect);
$query_commentadd = sprintf("SELECT * FROM `comment` WHERE idpost = %s", GetSQLValueString($colname_commentadd, "int"));
$commentadd = mysql_query($query_commentadd, $blogconnect) or die(mysql_error());
$row_commentadd = mysql_fetch_assoc($commentadd);
$totalRows_commentadd = mysql_num_rows($commentadd);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>WebProject</title>
    <meta content="">
    <link rel="stylesheet" type="text/css" href="../style.css" >

    <style></style>
  </head>
<body>
    <nav class="flexContainer blueBackground">
      <ul class="nav flexItem flexStart">
           <li><a href="../html/index.html">Blog</a></li>
      </ul>
      <ul class="nav flexContainer flexEnd">
          <li><a href="../index.php">Home</a></li>
          <li><a href="register.php">Register</a></li>
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Id:</td>
      <td><input type="text" name="id" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Iduser:</td>
      <td><input type="text" name="iduser" value="1" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Idpost:</td>
      <td><input type="text" name="idpost" value="<?php echo $_GET['idpost'];?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Text:</td>
      <td><input type="text" name="text" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="&#1042;&#1089;&#1090;&#1072;&#1074;&#1080;&#1090;&#1100; &#1079;&#1072;&#1087;&#1080;&#1089;&#1100;" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
    <footer class="footer">&copy; Cabac Ion</footer>
</body>
</html>
<?php
mysql_free_result($commentadd);
?>
