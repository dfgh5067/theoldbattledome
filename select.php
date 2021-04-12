<?php
session_start();
require('functions.php');
require('layout.php');

$user = "";
if(isset($_SESSION['username'])){
	$user = $_SESSION['username'];
}

?>

<head>
	<style>
	a {
		text-decoration: none;
		color:blue;
		font-weight: bold;
	}
	a:hover {
		color:rgb(246,188,48);
	}
	body {
		background-color:rgb(214,214,214);
		font-family: Verdana,Arial,Helvetica,sans-serif;
		font-size:12;
	}
	.arenaicons {
		height:80px;
	}
	</style>
	
	<script>
		function getPetName(id){
			var pet = document.getElementById('petname');
			var petname = pet.options[pet.selectedIndex].value;
			var wincount = document.getElementById("wincount").value;
			if(isNaN(wincount)){
				wincount = 0;
			}
			
			if(petname){
				document.getElementById('battle' + id).setAttribute('href', 'battle.php?id=' + id + '&pet=' + petname + '&wincount=' + wincount + '&status=start');
			}
			else{
				alert("Please select a pet to fight!");
			}
			return false;
		}
	</script>
</head>


<div id="container" style="padding:10px; background-color:white; max-width:850px; margin-left:auto; margin-right:auto;">

<div id="userbar" style="padding-bottom:10px; width=100%;">
	<?php display_user_bar($user); ?>
</div>

<h2>Sandbox Mode</h2>

<div id="topbanner" style="width:100%; text-align:center; margin-left:auto; margin-right:auto;">
	<a href=""><img class="arenaicons" src="img/arena_icon_default.png"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=""><img class="arenaicons" src="img/arena_icon_stone.png"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=""><img class="arenaicons" src="img/arena_icon_ice.png"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=""><img class="arenaicons" src="img/arena_icon_island.png"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=""><img class="arenaicons" src="img/arena_icon_space.png"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=""><img class="arenaicons" src="img/arena_icon_water.png"></a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=""><img class="arenaicons" src="img/arena_icon_tyrannia.png"></a>
</div>

<div id="menu" style="text-align:center;">
	<b><a href="">Status</a></b> | 
	<b><a href="equipment.php">Equipment</a></b> | 
	<b><a href="">1 Player</a></b> | 
	<b><a href="">Target Pets</a></b> | 
	<b><a href="">QuickFight</a></b> | 
	<b><a href="">Challenge a Pet</a></b>
	<br>
	<b><a href="">Front Page</a></b> | 
	<b><a href="">News</a></b> | 
	<b><a href="">Battlepedia</a></b> | 
	<b><a href="">Extras</a></b>
	<br><br>
	<b><a href="">Opponents Found</a></b> | 
	<b><a href="">Challenge Opponents</a></b> | 
	<b><a href="">One Player Hi-Scores</a></b> | 
	<b><a href="">User Info</a></b>
	<br><br>
</div>

<div id="fillertext" style="text-align:left;">
	<b>Challenge Battledome Opponents</b>
	<br><br>
	Punchbag Bob is now here! Go and test all of your new moves and weapons on him. If you use one-use items though you will not get them back so be careful! <span style="color:red"><b><i>REMEMBER: to withdraw/leave a one-player fight you need to visit the <a href="">status page</a>. If you don't get withdrawn immediately please have patience there are a lot of people on this site. Just try again in a little bit.</i></b></span> 
	<br><br>
	Check out the <a href=""><b>instructions page</b></a> first. Then select your pet and click the name of the challenger to fight them in the Battledome. Not all of the characters are active yet, so keep checking back for new opponents! The Meridell wars are overrated and Curse of Maraqua was a good concept with poor execution.
	<br><br>
	<span style="color:red"><b>IMPORTANT:</b></span> Remember to have your pet fully <a href="">equipped</a> before you click the challenger - clicking will take you straight into the fight!
	<br><br>
	On the 1st day of each month we will be doing scheduled maintenace on the Battledome to help speed things up. This means that you will not be able to challenge anyone between 2.25am and 2.35am (NST).
	<br><br>
	
	<div id="menu" style="text-align:center; font-size:18">
	<b><a href="equipment.php">Equipment</a></b> | 
	<b><a href="resources.php">Resources</a></b>
	</div>
	
<hr width="90%">
</div>


<div id = "challenger_list" style="background-color:white; max-width:600px; margin-left:auto; margin-right:auto">

	<table id="choosepet" border=1; cellspacing="0" width=75%; style="text-align:center; margin-left:auto; margin-right:auto;">
		<tr>
			<td style="background-color:rgb(247,201,137);">
				<b>Select your pet:</b><br>
				<span style="font-size:13px">(only pets that are <b>not hungry</b>, <b>not sick</b> and have <b>more than 0 hp</b> can fight)</span>
			</td>
			<td style="padding:5px">
				<select id="petname">
				<option value="">Select a pet</option>
				<?php get_petlist($user); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td style="background-color:rgb(247,201,137);">
				<b>Win Count:</b><br>
				<span style="font-size:13px">(Optional. Must be between 0 and 100,000)</span>
			</td>
			<td style="padding:5px">
				<textarea id="wincount" rows="1" cols="4" maxlength="6" style="resize:none;"></textarea>
			</td>
		</tr>
	</table>
	
	<br>
	
		
<!--begin generating challenger data here-->		
		<?php fill_challenger_rows($user);?>
</div>

</div>
<?php footer(); ?>