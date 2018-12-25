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
}
}
?>
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "level";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "error.html";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_blogconnect, $blogconnect);
  	
  $LoginRS__query=sprintf("SELECT `user`, password, level FROM `user` WHERE `user`=%s AND password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $blogconnect) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'level');
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<html>
<head>
    <title>WebProject</title>
    <meta content="">
    <link rel="stylesheet" type="text/css" href="style.css" >

    <style></style>
  </head>
<body>
    <nav class="flexContainer blueBackground">
      <ul class="nav flexItem flexStart">
           <li><a href="html/index.html">Blog</a></li>
      </ul>
      <ul class="nav flexContainer flexEnd">
          <li><a href="index.php">Home</a></li>
          <li><a href="manager/register.php">Register</a></li>
        <li>
      <?php 
if (isset ($_SESSION['MM_Username'])){?>
      <a href="<?php echo $logoutAction ?>">Log out</a>
      <?php };
?>
      <?php 
if (!isset ($_SESSION['MM_Username'])){
	?>
      <a href="manager/index.php">Log in</a>
      <?php
};
?>
    </li>
      </ul>
    </nav>
    <!--basic head, adica bara de meniu care trebuie sa fie obligatoriu pe fiecare pagina-->
    <div class="container">
  <h2>Ask me a question</h2>
  <p>Welcome to my test Blog Web Project.</p>
  <hr>
  <h3>Log in</h3>
<form action="<?php echo $loginFormAction; ?>" method="POST" name="loginform">
  <div class="flexContainer marginBottom">
      <input name="user" class="flexItem">
  </div>
  <div class="flexContainer marginBottom">
    <input name="password" type="password" class="flexItem">
  </div>
  <div class="flexContainer marginBottom">
    <input name="" type="submit">
  </div></form>

  <div class="flexContainer flexCenter itemCenter">
      <a href="index" class="homeButton">Return Home</a>
  </div>

</div>
<footer class="footer">&copy; Cabac Ion</footer>
</body>
</html>
