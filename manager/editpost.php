<?php require_once('../Connections/blogconnect.php'); ?>
<?php require_once('menul.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "error.html";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE postblog SET title=%s, anotat=%s, text=%s WHERE id=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['anotat'], "text"),
                       GetSQLValueString($_POST['text'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_blogconnect, $blogconnect);
  $Result1 = mysql_query($updateSQL, $blogconnect) or die(mysql_error());

  $updateGoTo = "editor.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_editpost = "-1";
if (isset($_GET['id'])) {
  $colname_editpost = $_GET['id'];
}
mysql_select_db($database_blogconnect, $blogconnect);
$query_editpost = sprintf("SELECT * FROM postblog WHERE id = %s", GetSQLValueString($colname_editpost, "int"));
$editpost = mysql_query($query_editpost, $blogconnect) or die(mysql_error());
$row_editpost = mysql_fetch_assoc($editpost);
$totalRows_editpost = mysql_num_rows($editpost);
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
          <li><a href="index.html">Blog</a></li>
      </ul>
      <ul class="nav flexContainer flexEnd">
          <li><a href="../index.php">Home</a></li>
          <li><a href="../html/register.html">Register</a></li>
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

<a href="index.php">Log in</a>
<?php
};
?></li>
      </ul>
    </nav>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Id:</td>
      <td><?php echo $row_editpost['id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Title:</td>
      <td><input type="text" name="title" value="<?php echo htmlentities($row_editpost['title'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
     <tr valign="baseline">
      <td nowrap="nowrap" align="right">Anotatie:</td>
      <td><textarea name="anotat" cols="32" rows="15"><?php echo htmlentities($row_editpost['anotat'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Text:</td>
      <td><textarea name="text" cols="32" rows="15"><?php echo htmlentities($row_editpost['text'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="&#1054;&#1073;&#1085;&#1086;&#1074;&#1080;&#1090;&#1100; &#1079;&#1072;&#1087;&#1080;&#1089;&#1100;" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_editpost['id']; ?>" />
</form>
<p>&nbsp;</p>
<footer class="footer">&copy; Cabac Ion</footer>
</body>
</html>
<?php
mysql_free_result($editpost);
?>
