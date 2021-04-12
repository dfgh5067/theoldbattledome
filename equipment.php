<?php
session_start();
require('functions.php');
require('layout.php');

$user = "";
if(isset($_SESSION['username'])){
	$user = $_SESSION['username'];
	
	if(isset($_GET['changeStats'])){ //let user customize pet stats (sandbox only)
		$msg = update_pet_stats($user);
	}
	
	if(isset($_GET['changeName'])){ //change pet name
		$msg = update_pet_name($user);
	}
	
	if(isset($_GET['unequip']) && is_numeric($_GET['unequip'])){ //unequip weap by id
		unequip_item($_GET['unequip'], $user);
	}
	
	if(isset($_GET['equip_weapon']) && is_numeric($_GET['equip_weapon'])){ //equip weap by id
		$msg = add_equipment($_GET['equip_weapon'], $user);
	}
	
	if(isset($_GET['ability']) && is_numeric($_GET['ability'])){ //acitvate ability by id
		$msg = activate_ability($_GET['ability'], $user);
	}
	
	if(isset($_GET['deactivate']) && is_numeric($_GET['deactivate'])){ //deactivate ability by id
		deactivate_ability($_GET['deactivate'], $user);
	}
	
	if($user == 'test'){ //form for adding new weaps to db; only display for personal use
		if(isset($_GET['addweap']) && is_numeric($_GET['wid'])){
			$output = add_new_weapon($_GET['wid']);
			
			if($_GET['flag'] === 'true' && is_numeric($_GET['wid'])){
				$msg = flag_weapon($_GET['wid']);
			}
		}
		else{
			$output = "<p>
							To add a weapon to the database:
							<ol style=\"text-align:left;\">
								<li>Find the weapon on Jellyneo's <a href=\"http://battlepedia.jellyneo.net/\">Battlepedia</a></li>
								<li>Look at the end of the URL to get the item ID ('id=####')</li>
								<li>Enter that ID into the box above, and click 'Add Weapon'</li>
							</ol>
						</p>";
		}
	}
	
	$petData = get_pet_data($user);	
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
</head>


<div id="container" style="padding:10px; background-color:white; max-width:850px; margin-left:auto; margin-right:auto;">

<div id="userbar" style="padding-bottom:10px; width=100%;">
	<?php display_user_bar($user); ?>
</div>

<div id="msg" style="color:red">
	<h3><?php echo $msg; ?></h3>
</div>

<div id="equipment">
	<table border="0" width=100%>
		<tr>
			<td width=50% style="border-right:1px solid black; border-bottom:1px solid black; text-align:center; vertical-align:top;">
				<h2>Equipment <?php equip_count($user); ?></h2>
				<table border="0" cellspacing="10" style="font-size:12; max-width:100%; margin-left:auto; margin-right:auto;">
					<?php display_equipment($user); ?>
				</table>

				<div id="equipmenu">
					<form name="weaponlist" method="get" action="equipment.php">
						<select name="equip_weapon">
							<?php output_equip_menu(); ?>
						</select>
						<button type="submit" name="equip" value="true">Equip Weapon</button>
					</form>
				</div>
			</td>
			
			<td width=50% style=" border-bottom:1px solid black; text-align:center; vertical-align:top;">
				<h2>Abilities</h2>
				<table border="0" cellspacing="10" style="font-size:12; max-width:100%; margin-left:auto; margin-right:auto;">
					<?php display_active_abilities($user); ?>
				</table>

				<div id="abmenu">
					<form name="abilitylist" method="get" action="equipment.php">
						<select name="ability">
							<?php output_abilities_menu(); ?>
						</select>
						<button type="submit" name="activate" value="true">Activate Ability</button><br>
					</form>
				</div>
			</td>
		</tr>
		<tr>
		
			<td width=50% style="text-align:center; vertical-align:top;">
				<h2>Add a Weapon</h2>
				<form name="requestweapon" method="get" action="equipment.php">
					<input type="text" name="wid" size="6" maxlength="6"></input>&nbsp;
					<button type="submit" name="addweap" value="true">Add Weapon</button>
				</form>
			</td>
			<td>
				<div id="addweapontext"  style="text-align:left;">
					<?php echo $output; ?>
				</div>
			</td>
		</tr>
	</table>
</div>

<hr>

<h2>Change Stats</h2>
<form name="statchange" method="get" action="equipment.php">
	<table  border="0">
		<tr>
			<td><label><b>HP: </b></label></td>
			<td><input type="text" name="hp" size="5" maxlength="5" value="<?php echo $petData['hp']; ?>"></td>
			<td>(Max: 25,000)</td>
		</tr>
		<tr>
			<td><label><b>Strength: </b></label></td>
			<td><input type="text" name="str" size="5" maxlength="3" value="<?php echo $petData['str']; ?>"></td>
			<td>(Max: 700)</td>
		</tr>
		<tr>
			<td><label><b>Defense: </b></label></td>
			<td><input type="text" name="def" size="5" maxlength="3" value="<?php echo $petData['def']; ?>"></td>
			<td>(Max: 700)</td>
		</tr>
		<tr>
			<td><label><b>Level: </b></label></td>
			<td><input type="text" name="lvl" size="5" maxlength="2" value="<?php echo $petData['level']; ?>"></td>
			<td>(Max: 50)</td>
		</tr>
		<tr>
			<td><label><b>Intel: </b></label></td>
			<td><input type="text" name="intel" size="5" maxlength="4" value="<?php echo $petData['intel']; ?>"></td>
			<td>(Max: 1,000)</td>
		</tr>
		<tr>
			<td><label><b>Agility: </b></label></td>
			<td><input type="text" name="agil" size="5" maxlength="3" value="<?php echo $petData['spd']; ?>"></td>
			<td>(Max: 201)</td>
		</tr>
		<tr>
			<td><label><b>Species: </b></label></td>
			<td>
				<select name="species">
					<?php output_species_list($petData['species']); ?>
				</select>
			</td>
			<td>
				<select name="colour">
					<?php output_petcolours_list($petData['colour']); ?>
				</select>
			</td>
			<td onclick="showpet()">
				<img height = "50" src = "<?php output_pet_image($petData['species'], $petData['colour']); ?>">
			</td>
		</tr>
		<tr>
			<td colspan="3"><button type="submit" name="changeStats">Submit</button></td>
		</tr>
	</table>
</form>
<hr>
<h2>Change Name</h2>
<form name="namechange" method="get" action="equipment.php">
	Current Name: <b><?php echo $petData['name']; ?></b><br>
	New Name: <input type="text" name="newname" size="16" maxlength="16"><br>
	<button type="submit" name="changeName">Submit</button>
</form>

</div>

<?php footer(); ?>








