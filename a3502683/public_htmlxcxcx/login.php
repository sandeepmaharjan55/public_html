<?php include 'header.php'; ?><?php
//This page let log in
include('config.php');
if(isset($_SESSION['username']))
{
	unset($_SESSION['username'], $_SESSION['userid']);
	setcookie('username', '', time()-100);
	setcookie('password', '', time()-100);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Login</title>
    </head>
    <body>
    	<center>
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </center>
<div class="message">You have successfully been logged out.<br />
<a href="forum.php">Home</a></div>
<?php
}
else
{
	$ousername = '';
	if(isset($_POST['username'], $_POST['password']))
	{
		if(get_magic_quotes_gpc())
		{
			$ousername = stripslashes($_POST['username']);
			$username = @mysql_real_escape_string(stripslashes($_POST['username']));
			$password = stripslashes($_POST['password']);
		}
		else
		{
			$username = @mysql_real_escape_string($_POST['username']);
			$password = $_POST['password'];
		}
		$req = @mysql_query('select password,id from users where username="'.$username.'"');
		$dn = @mysql_fetch_array($req);
		if($dn['password']==sha1($password) and @mysql_num_rows($req)>0)
		{
			$form = false;
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['userid'] = $dn['id'];
			if(isset($_POST['memorize']) and $_POST['memorize']=='yes')
			{
				$one_year = time()+(60*60*24*365);
				setcookie('username', $_POST['username'], $one_year);
				setcookie('password', sha1($password), $one_year);
			}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Login</title>
    </head>
    <body>
    	<center>
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </center>
<div class="message">You have successfully been logged.<br />
<a href="forum.php">Enter</a></div>
<?php
		}
		else
		{
			$form = true;
			$message = 'The username or password you entered are not good.';
		}
	}
	else
	{
		$form = true;
	}
	if($form)
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Login</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
<?php
if(isset($message))
{
	echo '<div class="message">'.$message.'</div>';
}
?>
<div class="content">
<?php
$nb_new_pm = @mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="forum.php">Forum Index</a> &gt; Login
    </div>
	

    
    <div class="clean"></div>
</div>
    <form action="login.php" method="post">
        Please, type your IDs to log:<br />
        <div class="login">
            <label for="username">Username</label><input type="text" name="username" id="username" value="<?php echo htmlentities($ousername, ENT_QUOTES, 'UTF-8'); ?>" /><br />
            <label for="password">Password</label><input type="password" name="password" id="password" /><br />
            <label for="memorize">Remember</label><input type="checkbox" name="memorize" id="memorize" value="yes" /><br />
            <input type="submit" value="Login" />
		</div>
    </form>
</div>
<?php
	}
}
?>

</html>
<?php include 'footer.php'; ?>