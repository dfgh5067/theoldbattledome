<?php
define('SITE_PREFIX', "/bdsim"); //you'll have to change this to your domain name

//displays nav links, username, bd points, login/logout link
function display_user_bar($user){
	$pages = array("Sandbox" => SITE_PREFIX . "/select.php", "1-Player" => SITE_PREFIX . "/1p/select.php", "2-Player" => SITE_PREFIX . "/2p/select.php", "Updates" => SITE_PREFIX . "/updates.php");
	$navBar = "";
	
	if(empty($user)){
		echo "
		<table style=\"float:right; width:100%\">
			<tr>
				<td width=\"50%\" style=\"text-align:left;\"><a href=\"" . SITE_PREFIX . "/login.php\">Login</a></td>
			</tr>
		</table>
		";

		echo "<br><br>";
	}
	else{
		$currentURL = $_SERVER['PHP_SELF'];
		foreach($pages as $page=>$url){
			if($url != $currentURL){
				$navBar .= "<a href=\"" . $url . "\">" . $page . "</a> | ";
			}
			else{
				$navBar .= "<b>" . $page . "</b> | ";
			}
		}
		$navBar = substr($navBar, 0, -2);

		//output table containg nav links and username
		echo "
		<table style=\"float:right; width:100%\">
			<tr>
				<td width=\"50%\" style=\"text-align:left;\">" . $navBar . "</td>
				<td width=\"50%\" style=\"text-align:right;\">User: " . $user . " | <a href=\"" . SITE_PREFIX . "/logout.php\">Logout</a></td>
			</tr>
		</table>
		";

		echo "<br><br>";
	}
	
	//output mode and page
	$currentDir = (basename(getcwd()) == 'public_html' ? 'sandbox' : basename(getcwd()));
	$currentPage = basename($_SERVER['PHP_SELF'], '.php');
	
	echo "<b>" . $currentDir . " - " . $currentPage . "</b><br><br>";
}




?>