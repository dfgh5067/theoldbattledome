<?php
//require 'config.php'; //contains connect_to_server() function
define('EQUIP_LIMIT', 8);


/***select.php functions***/

//output petnames into dropdown menu
function get_petlist($user){
	$con = connect_to_server();
	
	//get pet names from db
	$sql = "SELECT `pets` FROM `playerdb` WHERE `user` = '$user'";
	$result = mysqli_query($con, $sql);

	$petnames = mysqli_fetch_array($result);
	$petnames = $petnames['pets'];
	$petnames = explode(":",$petnames);

	mysqli_free_result($result);
	
	//TODO
	//check each petname for active battles
	//if yes, remove from list

	//output petnames into dropdown menu
	foreach($petnames as $p){
		echo "<option value=\"{$p}\" selected=\"selected\">{$p}</option>\n";
	}
}


//gets challenger info; outputs rows for challenger table
function fill_challenger_rows($user){
	$con = connect_to_server();
	$activeBattle = false;
	
	$withdraw = $_GET['action'];
	if(isset($withdraw) && $withdraw == 'withdraw'){
		$withdrawmsg = withdraw_from_fight($user);
	}
	else{
		$sql = "SELECT `pets` from `playerdb` WHERE `user` = '$user'";
		$result = mysqli_query($con, $sql);
		$petlist = mysqli_fetch_array($result);
		$petlist = explode(":", $petlist['pets']);
		foreach($petlist as $pet){
			$sql = "SELECT * FROM `active_battles` WHERE `pet` = '$pet'";
			$result = mysqli_query($con, $sql);
			if(mysqli_num_rows($result) > 0){
				$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$opponent = $data['opponent'];
				echo "<div style=\"width:75%; margin-left:auto; margin-right:auto;\">";
				echo "<h2>You have a battle in progress!</h2>";
				echo "<h3><a href=\"battle.php?id={$opponent}&pet={$pet}&status=continue\">Continue the Battle</a></h3>";
				echo "To withdraw, <a href=\"?&action=withdraw\">click here</a>.";
				echo "</div>";
				$activeBattle = true;
				break;
			}
		}
	}
	
	if(!$activeBattle){
		if(isset($withdrawmsg)){
			echo $withdrawmsg;
		}
		//start challenger list table
		echo "
		<table id=\"challengerlist\" border=\"1\"; cellspacing=\"0\" cellpadding=\"3\" width=100%; style=\"text-align:center; margin-left:auto; margin-right:auto;\">
			<tr style=\"background-color:rgb(247,201,137);\">
				<td>
				<b>Character</b><br><span style=\"width:20px; font-size:13px\";><i>(Click to challenge)</i></span>
				</td>
				<td>
				<b>Difficulty</b>
				</td>
				<td>
				<b>Played</b>
				</td>
				<td>
				<b>Won</b>
				</td>
				<td>
				<b>Lost</b>
				</td>
				<td>
				<b>Drawn</b>
				</td>
				<td>
				<b>Score</b>
				</td>
			</tr>
		";
		
		$sql = "SELECT `id`,`arena`,`name`,`difficulty` FROM `challengerdb`";
		$result = mysqli_query($con, $sql);
			
		if($result){
			$opp_data = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
				$opp_data[] = $row;
			}
			mysqli_free_result($result);
			
			//print_r($opp_data);
		
			
		}

		/*get challenger data for each id*/
		foreach($opp_data as $data){
			//get user records against that opponent
			$arena = $data['arena'];
			$opp_name = $data['name'];
			$diff = $data['difficulty'];
			$id = $data['id'];
			$oppid = "opp" . $id;
			$sql = "SELECT `$oppid` FROM `playerdb` WHERE `user` = '$user'";
			$result = mysqli_query($con, $sql);
					
			if($result){
				$user_data = mysqli_fetch_array($result);
				$user_data = $user_data[$oppid];
				mysqli_free_result($result);

				if(!empty($user_data)){
					$user_data = explode(":", $user_data);
					$w = $user_data[0];
					$l = $user_data[1];
					$d = $user_data[2];
					$score = $user_data[3];
					$played = $w + $l + $d;
				}
				else{
					$w = 0;
					$l = 0;
					$d = 0;
					$score = 0;
					$played = 0;
				}
			
			}
			$bg_color_dark = get_color_dark($arena);
			$bg_color_light = get_color_light($arena);
			$win_color = get_win_color($w, $l);
			$plusminus = get_plusminus($w);
			

			
			if($played != 0 && $w != 0){
				$row = "
					<tr style=\"background-color:rgb({$bg_color_light});\">
						<td style=\"background-color:rgb({$bg_color_dark});\">
							<b><a id=\"battle{$id}\" href=\"battle.php?id={$id}\" onclick=\"getPetName({$id})\">{$opp_name}</a></b>
						</td>
						<td>
							<b>{$diff} <span style=\"color:{$win_color}\">{$plusminus}{$w}</span></b>
						</td>
						<td>{$played}</td>
						<td >{$w}</td>
						<td>{$l}</td>
						<td>{$d}</td>
						<td>{$score}</td>
					</tr>
					";
			}
			elseif($played !=0){
				$row = "
					<tr style=\"background-color:rgb({$bg_color_light});\">
						<td style=\"background-color:rgb({$bg_color_dark});\">
							<b><a id=\"battle{$id}\" href=\"battle.php?id={$id}\" onclick=\"getPetName({$id})\">{$opp_name}</a></b>
						</td>
						<td>
							<b>{$diff} <span style=\"color:{$win_color}\">{$plusminus}</span></b>
						</td>
						<td>{$played}</td>
						<td >{$w}</td>
						<td>{$l}</td>
						<td>{$d}</td>
						<td>{$score}</td>
					</tr>
					";
			}
			else{
				$row = "
					<tr style=\"background-color:rgb({$bg_color_light});\">
						<td style=\"background-color:rgb({$bg_color_dark});\">
							<b><a id=\"battle{$id}\" href=\"battle.php?id={$id}\" onclick=\"getPetName({$id})\">{$opp_name}</a></b>
						</td>
						<td>
							<b>{$diff} <span style=\"color:{$win_color}\">+0</span></b>
						</td>
						<td colspan=\"5\">
						<i>You have not fought this challenger yet</i>
						</td>
					</tr>
					";
			}
			
			echo $row;
		}
		echo "</table>";
	}
}

//arena colors for challenger list (main body of table)
function get_color_light($arena){
	if($arena == "stone"){
		return "246,217,158";
	}
	elseif($arena == "ice"){
		return "210,229,236";
	}
	elseif($arena == "island"){
		return "127,216,130";
	}
	elseif($arena == "space"){
		return "200,200,201";
	}
	elseif($arena == "water"){
		return "152,192,216";
	}
	elseif($arena == "tyrannia"){
		return "224,200,181";
	}
	else{
		return "232,152,152";
	}
}

//arena colors for challenger list (name column)
function get_color_dark($arena){
	if($arena == "stone"){
		return "248,175,70";
	}
	elseif($arena == "ice"){
		return "167,214,237";
	}
	elseif($arena == "island"){
		return "1,184,8";
	}
	elseif($arena == "space"){
		return "158,160,159";
	}
	elseif($arena == "water"){
		return "65,128,193";
	}
	elseif($arena == "tyrannia"){
		return "191,144,112";
	}
	else{
		return "216,54,55";
	}
}

//text color for difficulty bonus
function get_win_color($w, $l){
	if($w > 0){
		return "red";
	}
	elseif($l > 0){
		return "blue";
	}
	else{
		return "green";
	}
}

//difficulty shows "-1" if you have 0 wins and at least 1 loss
function get_plusminus($w){
	if($w > 0){
		return "+";
	}
	else{
		return "-1";
	}
}
  

  
/***battle.php functions***/
  
//checks that petname given is associated with username
function checkPetName($petname, $user){
	$con = connect_to_server();
	
	$petname = preg_replace('/[^0-9a-zA-Z_]/',"",$petname);
	
	$sql = "SELECT `pets` FROM `playerdb` WHERE `user` = '$user'";
	$result = mysqli_query($con, $sql);
	
	$petlist = mysqli_fetch_array($result);
	$petlist = $petlist['pets'];
	$petlist = explode(":", $petlist);
	
	mysqli_free_result($result);
	
	if(in_array($petname, $petlist)){
		return true;
	}
	else{
		return false;
	}
}


//returns image for opponent with oppID
function get_opp_img($oppID, $con){
	$sql = "SELECT `img` FROM `challengerdb` WHERE `id` = '$oppID'";
	$result = mysqli_query($con, $sql);
	
	$img = mysqli_fetch_array($result);
	$img = $img['img'];
	
	mysqli_free_result($result);
	
	return $img;
}

//returns name of opponent from oppID
function get_opp_name($oppID){
	$con = connect_to_server();
	$sql = "SELECT `name` FROM `challengerdb` WHERE `id` = '$oppID'";
	$result = mysqli_query($con, $sql);
	
	$name = mysqli_fetch_array($result);
	$name = $name['name'];
	
	mysqli_free_result($result);
	
	return $name;
}


//prevent user from starting a fight against a different opponent when they're already in an active battle
//checks submitted oppID against any active battles pet is in
//returns correct oppID
function get_oppID($oppID, $petname){
	$con = connect_to_server();
	
	$sql = "SELECT * FROM `active_battles` WHERE `pet` = '$petname'";
	$result = mysqli_query($con, $sql);
	$resultAlrray = array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$resultArray[] = $row;
	}
	
	if(count($resultArray) > 0){
		$id = $resultArray[0]['opponent'];
	}
	else{
		$id = $oppID;
	}
	return $id;
}


//initialize opponent's data for a new battle
function initialize_opp($oppID, $user, $con){
	global $oppName, $oppCurrHP, $oppMaxHP, $oppStr, $oppDef, $oppAbilities, $oppPower, $oppFrozen;
	global $oppWeaponList;
	
	$sql = "SELECT * FROM `challengerdb` WHERE `id` = '$oppID'";
	$result = mysqli_query($con, $sql);

	$oppData = mysqli_fetch_array($result);
	$oppImage = $oppData['img'];
	$oppName = $oppData['name'];
	$oppBaseHP = $oppData['base_stats'];
	$oppBaseStr = $oppData['base_stats'];
	$oppBaseDef = $oppData['base_stats'];
	$growthStat = $oppData['growth_stat'];
	$oppWeaponList = $oppData['weapon_list'];
	$oppAbilities = $oppData['abil_list'];
	$oppMaxHP = $oppBaseHP;

	mysqli_free_result($result);

	//check for user input win count
	//if not, get user's win count from database
	if(is_numeric($_GET['wincount']) && $_GET['wincount'] >= 0 && $_GET['wincount'] <= 100000){
		$winCount = $_GET['wincount'];
		
		$oppID = "opp" . $oppID;
		$sql = "SELECT `$oppID` FROM `playerdb` WHERE `user` = '$user'";
		$result = mysqli_query($con, $sql);
		$winCount_ = mysqli_fetch_array($result);
		$winCount_ = $winCount_[$oppID];
		$winCount_ = explode(":", $winCount_);
		$winCount_[0] = $winCount;
		
		$newWinCount = implode(":", $winCount_);
		$sql = "UPDATE `playerdb` SET `$oppID` = '$newWinCount' WHERE `user` = '$user'";
		$result = mysqli_query($con, $sql);
	}
	else{
		$oppID = "opp" . $oppID;
		$sql = "SELECT `$oppID` FROM `playerdb` WHERE `user` = '$user'";
		$result = mysqli_query($con, $sql);

		$winCount = mysqli_fetch_array($result);
		$winCount = $winCount[$oppID];
		$winCount = explode(":", $winCount);
		$winCount = $winCount[0];
		
		mysqli_free_result($result);
	}
	

	//adjust hp/str/def based on win count
	$oppMaxHP += floor($winCount * $growthStat);
	$oppCurrHP = $oppMaxHP;
	
	$oppStr = $oppBaseStr + ($winCount * $growthStat);
	$oppDef = $oppBaseDef + ($winCount * $growthStat);
	
	$oppPower = 100;
	$oppFrozen = 0;
}


//initialize pet data for a new battle
function initialize_pet($petname, $con){
	global $petname, $petCurrHP, $petMaxHP, $petPower, $petWeaponList, $petAbilities, $petFrozen;
	
	$sql = "SELECT * FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	
	$petdata = mysqli_fetch_array($result);
	$petMaxHP = $petdata['hp'];
	$petCurrHP = $petMaxHP;
	$petPower = 100;
	//$petWeaponList = explode(":", $petdata['weapons']);
	$petWeaponList = $petdata['weapons'];
	$petAbilities = $petdata['act_abil'];
	
	$petFrozen = 0;
	
	mysqli_free_result($result);
}

//gets weapon list for current battle
function get_pet_weapon_list($petname){
	$con = connect_to_server();
	$sql = "SELECT `pet_set` FROM `active_battles` WHERE `pet` = '$petname'";
	$result = mysqli_query($con, $sql);
	
	$list = mysqli_fetch_array($result);
	$list = $list['pet_set'];
	//$list = explode(":", $list);
	
	mysqli_free_result($result);
	
	return $list;
}


//for battle UI
//output weapons in table
//also outputs ability dropdown and Go! button
function output_weapon_list($petWeaponList){
	global $petname;
	global $petCurrHP, $oppCurrHP;
	
	if($petCurrHP == 0 || $oppCurrHP == 0){ //weaps don't display if battle is over
		output_endbattle_msg();
	}
	else{
		$weapons = explode(":", $petWeaponList);
		
		$count = count($weapons);
		$maxRows = ceil($count / 5); //# of rows needed to display all weapons
		
		//start form and weapons table
		echo '
		<span class="left"><b>Equipment</b> <span style="font-size:10">(select up to two)</span></span>
		<br><br>
		
		<form method="GET" action="javascript:submitMove();" style="max-width:75%; margin-left:auto; margin-right:auto;">
			<table border="0">
		';
		
		for($r=0; $r < $maxRows; $r++){ //row #
			echo "<tr style=\"text-align:center;\">"; //begin row
			
			for($i=0; $i < 5; $i++){ //5 weapons per row
				$n = $r * 5 + $i; //index of $weapons array
				if(isset($weapons[$n])){
					$weapID = $weapons[$n];
					$weapImg = get_weapon_img($weapons[$n]);
					$weapName = get_weapon_name($weapons[$n]);
					
					echo "
						<td style=\"max-width:100px; padding-bottom:10px;\" valign=\"top\">
						<a href=\"javascript:void(0)\" onclick=\"countWeaps({$n})\">
						<label>
						<div style=\"margin:auto; height:80px; width:80px; border: 1px solid black; background: url('{$weapImg}') no-repeat center\">
						</div>
						
						{$weapName}<br>
						<input type=\"checkbox\" value=\"{$weapID}\" id=\"{$n}\"><br>
						</label>
						</a>
						</td>
					";
				}
			}
			echo "</tr>"; //end row
		}
		//end table
		echo '
		</table>
		<br><br>
		';
			
			//output abilities list
			//and Go! button
			echo '
			<div style="text-align:center;">
			<select id="ability">
			';
			echo fill_stances_dropdown($petname);
			echo fill_species_dropdown($petname);
			echo fill_fabilities_dropdown($petname);
			echo '
			</select>
			
			<select id="power">
				<option value="strong">Strong</option>
				<option value="medium">Medium</option>
				<option value="weak">Weak</option>
			</select>
			
			<input type="submit" onClick="process_turn()" action="submitWeapons()" value="Go!">
			</div>
			';
			
			//end form
			echo '
			</form>
			';
	}
}


//use to fill in dropdown menu with stances
//returns string of dropdown options
function fill_stances_dropdown($petname){
	//check level 50 for berserk
	$con = connect_to_server();
	$sql = "SELECT `level` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$lvl = mysqli_fetch_array($result);
	$level = $lvl['level'];
	
	$dropdown = '<option value="0">Select an Ability for ' . $petname . '</option>';
				
	if($level >= 50){
		$dropdown .= '<option value="5">Berserk Attack</option>';
	}
	
	$dropdown .='
				<option value="4">Fierce Attack</option>
				<option value="3">Jump and Attack</option>
				<option value="2">Normal Attack</option>
				<option value="1">Cautious Attack</option>
				<option value="-1">Defend</option>
				<option value="-2">Improved Defend</option>
	';
	
	return $dropdown;
}


//pulls species of pet
//looks up species attacks
//returns string of dropdown options
function fill_species_dropdown($petname){
	//get pet's species and level
	$con = connect_to_server();
	$sql = "SELECT * FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$species = $result['species'];
	$petLevel = $result['level'];
	
	//find species attacks
	$sql = "SELECT `id`,`name`,`level` FROM `species_attacks` WHERE `species` = '$species' ORDER BY `id`";
	$result = mysqli_query($con, $sql);
	$sAttacks = array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$sAttacks[] = $row;
	}
	//print_r($sAttacks);
	
	$dropdown = '';
	foreach($sAttacks as $a){
		if($petLevel >= $a['level']){
			$dropdown .= '<option value="' . $a['id'] . '">' . $a['name'] . '</option>';
		}
	}
	
	return $dropdown;
}


//pulls faerie abilities for pet
//returns string of dropdown options
function fill_fabilities_dropdown($petname){
	//get active abilities list
	$con = connect_to_server();
	$sql = "SELECT `pet_abilities` FROM `active_battles` WHERE `pet` = '$petname'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$abs = $result['pet_abilities'];
	
	//get ability names
	$abs = explode(":", $abs);
	sort($abs);
	$absArray = array();
	foreach($abs as $a=>$id){
		$name = get_ability_name($id);
		$absArray[] = array($id, $name);
	}
	
	//get dropdown options
	$dropdown = '';
	foreach($absArray as $a){
		$dropdown .= '<option value="' . $a[0] . '">' . $a[1] . '</option>';
	}
	return $dropdown;
	
}

//outputs message when battle is over
function output_endbattle_msg(){
	global $petCurrHP, $oppCurrHP;
	global $oppID;
	
	$oppName = get_opp_name($oppID);
	
	//start message
	$msg = '<div style="text-align:center;"><b>';
	
	if($petCurrHP == 0){
		if($oppCurrHP == 0){
			$msg .= '
				It\'s a draw!<br>
				Nobody won this fight!<br>
				';
		}
		else{
			$msg .= 
				$oppName . ' has beaten you!<br>
				You have lost this fight!<br>		
			';
		}
	}
	else{
		$msg .= '
			You have beaten ' . $oppName . '!<br>
			You have won this fight!<br>
		';
	}
	
	//add 'Next' button
	$buttonStuff = '
		<br><br>
		<form method="GET" action="select.php">
		<button type="submit">Next</button>
		</form>
	';
	
	$msg .= $buttonStuff;
	$msg .= '</b></div>';
	
	echo $msg;
}


//returns weapon img url from weap id
function get_weapon_img($id){
	$con = connect_to_server();
	
	$sql = "SELECT `image` FROM `weapons` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	
	$img = mysqli_fetch_array($result);
	$img = $img['image'];
	mysqli_free_result($result);

	return $img;
}


//returns weapon name from weap id
function get_weapon_name($id){
	$con = connect_to_server();
	
	$sql = "SELECT `name` FROM `weapons` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	
	$name = mysqli_fetch_array($result);
	$name = $name['name'];
	mysqli_free_result($result);
	
	//echo $name . "<br>";
	return $name;
}


//returns species attack name from id
function get_species_attack_name($id){
	$con = connect_to_server();
	
	$sql = "SELECT `name` FROM `species_attacks` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	
	$name = mysqli_fetch_array($result);
	$name = $name['name'];
	mysqli_free_result($result);
	
	//echo $name . "<br>";
	return $name;
}


//draws power bar img
//create colored rectangle scaled to $power
function draw_petpower_bar($power){
	$height = 20;
	$baseLength = 200;
	if($power > 0){
		$length = ($power/100) * $baseLength;
	}
	else{
		$length = 1;
	}
	
	$img = imagecreatetruecolor($length, $height);
	$color = get_powerbar_color($power, $img);
	
	imagefilledrectangle($img, 0, 0, $length, $height, $color);
	
	imagepng($img, 'petpowerbar.png');
	imagedestroy($img);
}


//draw power bar for opponent
function draw_opppower_bar($power){
	$height = 20;
	$baseLength = 200;
	if($power > 0){
		$length = ($power/100) * $baseLength;
	}
	else{
		$length = 1;
	}
	
	$img = imagecreatetruecolor($length, $height);
	$color = get_powerbar_color($power, $img);
	
	imagefilledrectangle($img, 0, 0, $length, $height, $color);
	
	imagepng($img, 'opppowerbar.png');
	imagedestroy($img);
}


//returns color for power bar
function get_powerbar_color($power, $img){
	if($power >= 60 && $power < 80){
		$c = imagecolorallocate($img, 0, 0, 249);
	}
	elseif($power >= 40 && $power < 60){
		$c = imagecolorallocate($img, 255, 255, 0);
	}
	elseif($power >= 20 && $power < 40){
		$c = imagecolorallocate($img, 255, 164, 0);
	}
	elseif($power >= 0 && $power < 20){
		$c = imagecolorallocate($img, 252, 3, 0);
	}
	else{
		$c = imagecolorallocate($img, 1, 128, 1);
	}
	
	return $c;
}


//returns color for HP text
function get_hp_text_color($currHP, $maxHP){
	$p = $currHP / $maxHP * 100;
	
	if($p >= 60 && $p < 80){
		$c = "rgb(0, 0, 249)";
	}
	elseif($p >= 40 && $p < 60){
		$c = "rgb(255, 255, 0)";
	}
	elseif($p >= 20 && $p < 40){
		$c = "rgb(255, 164, 0)";
	}
	elseif($p >= 0 && $p < 20){
		$c = "rgb(252, 3, 0)";
	}
	else{
		$c = "rgb(1, 128, 1)";
	}
	
	return $c;
	
}


//returns name of ability from id
function get_ability_name($a){
	$con = connect_to_server();
	$sql = "SELECT `name` FROM `abilities` WHERE `id` = '$a'";
	$result = mysqli_query($con, $sql);
	$ab_name = mysqli_fetch_array($result);
	$ab_name = $ab_name['name'];
	mysqli_free_result($result);
	
	return $ab_name;
}


//returns ability img url from id
function get_ability_img($id){
	$con = connect_to_server();
	
	$sql = "SELECT `image` FROM `abilities` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	
	$img = mysqli_fetch_array($result);
	$img = $img['image'];
	mysqli_free_result($result);

	return $img;
}


//returns name of stance from id
function get_stance_name($s){
	$con = connect_to_server();
	$sql = "SELECT `name` FROM `stances` WHERE `id` = '$s'";
	$result = mysqli_query($con, $sql);
	$stance_name = mysqli_fetch_array($result);
	$stance_name = $stance_name['name'];
	mysqli_free_result($result);
	
	return $stance_name;
}



//MAIN FUNCTION FOR PROCESSING MOVES
//processes round, updates row in db
function process_turn(){
	global $combatLog, $user, $petname;
	global $oppID, $oppName, $oppCurrHP, $oppMaxHP, $oppPower, $oppStr;
	global $petCurrHP, $petMaxHP, $petPower;
	global $petWeaponList, $petAbilities, $oppWeaponList, $oppAbilities;
	global $w1, $w2, $a, $p, $oppW1, $oppW2, $oppA;
	global $p2Dmg, $oppDmg;
	global $petFrozen, $oppFrozen;
	$p2HealTotal = 0;
	$oppHealTotal = 0;
	$oppStanceM = 1;
	$stanceM2 = 1;
	
	$con = connect_to_server();
	
	//check if first round or continuation
	$firstRound = load_fight($petname);
	
	$continueFight = false;
	if($_GET['status'] == 'continue'){
		$continueFight = true;
	}
		
	if($firstRound){
		$combatLog = "<tr><td>The fight commences!</td></tr>";
		save_fight();
	}
	elseif($continueFight){
		//TODO: save combat log somewhere so when player leaves and re-enters battle, last round is still shown
		$combatLog = "<tr><td>The fight continues!</td></tr>";
		save_fight();
	}
	else{
		//get weaps/ability used
		//reverse weapon order (w selected first is applied second)
		if(!is_frozen($petname, $prefix = 'pet')){
			$weapCheckArray = check_weapon_submissions($player = 'p2', $_GET['w2'], $_GET['w1']); //verify submission
			//"pick one" weapons are stuff like Sword of Lameness, Bracelet of Kings, Glowing Cauldron
			//	each possible effect is coded as its own weapon in the database
			$w1 = (has_pick_one($weapCheckArray[0]) ? get_pick_one_new_id($weapCheckArray[0]) : $weapCheckArray[0]);
			$w2 = (has_pick_one($weapCheckArray[1]) ? get_pick_one_new_id($weapCheckArray[1]) : $weapCheckArray[1]);
			$a = $_GET['a'];
			if($petPower > 0){ //can only use stance/abs/spec if power > 0
				if($a < 10){ //stance
					$stanceM2 = get_stance_multiplier($a);
				}
				elseif($a > 10 && $a < 1000){ //faerie ability
					$a = check_ability_submission($player = 'p2', $a);
					$p = get_power_number($a, $petPower, $_GET['p']);
					$petPower -= $p;
				}
				elseif($a > 1000){ //species attack
					$a = check_species_attack_submission($a, $petname);
				}
			}
			else{
				$a = '';
			}
		}
		
		//for dmg calc later
		$p2StrMult = get_pet_str_mult($petname);
		
		
		//get moveset for opp
		if(!is_frozen($petname, $prefix = 'opp')){
			$oppWeaps = roll_opp_moveset($oppID);
			$oppW1 = check_weapon_submission($player = 'p1', $oppWeaps[1]);
			$oppW2 = check_weapon_submission($player = 'p1', $oppWeaps[0]);
			$oppW1Name = get_weapon_name($oppW1);
			$oppW2Name = get_weapon_name($oppW2);
			$oppA = $oppWeaps[2];
			if($oppPower > 0){
				if($oppA < 10){
					$oppStanceM = get_stance_multiplier($oppA);
				}
				else{
					$oppA = check_ability_submission($player = 'p1', $oppA);
					$oppP = get_power_number($oppA, $oppPower, $oppP='strong');
					$oppPower -= $oppP;
				}
			}
			else $oppA = '';
		}
		
		$oppStrMult = get_opp_str_mult($oppStr);
		
		
		//user is p2, opponent is p1
		//TODO: when opp is frozen, move order should be swapped
		//initialize arrays to track icons dealt/blocked
		$p2Dmg = initialize_dmg_arrays();
		$p2IconsBlocked = initialize_dmg_arrays();
		$oppDmg = initialize_dmg_arrays();
		$oppIconsBlocked = initialize_dmg_arrays();

		
		
		//begin combat log
		//lines will be added as each weap gets processed
		$cbLogStart = '
			<tr>
				<td style="text-align:left;"><b>' . $petname . '</b></td>
				<td style="text-align:center;"><b>On the last move...</b></td>
				<td style="text-align:right;"><b>' . $oppName . '</b></td>
			</b></tr>
		';
		
		$cbLogIcons = '';
		$iconsBlocked;
		$p2Offense = array($w1, $w2);
		$oppOffense = array($oppW1, $oppW2);
		
	
	
	//***APPLY WEAPONS/ABILITIES***
	
	//player2 offense
		$side = "R"; //indicates which side of combat log to display icons
		$type = "atk"; //indicates icon img to use
		foreach($p2Offense as $w){
			if($w){
				if(has_drain($w, $tbName='weapons')){
					$result = apply_drain($side, $type, $w, $tbName, $oppCurrHP, $petCurrHP);
					$oppCurrHP = $result[0];
					$p2HealTotal += $result[1];
					$cbLogIcons .= $result[2];
				}
				if(has_atk($w, $tbName='weapons')){
					$result = add_to_dmg_array($p2Dmg, $w, $p, $side, $type, $tbName);
					$p2Dmg = $result[0];
					$cbLogIcons .= $result[1];
				}
				if(has_freeze($w, $player = 'p2', $tbName = 'weapons')){
					$oppFrozen = 2;
					$frzMsg = get_freeze_msg($w, $side, $oppID, $tbName = 'weapons');
					$cbLogIcons .= $frzMsg;
				}
			}
		}
		if($a > 10 && $a < 1000){ //faerie ability
			if(has_drain($a, $tbName='abilities')){
				$result = apply_drain($side, $type, $a, $tbName, $oppCurrHP, $petCurrHP);
				$oppCurrHP = $result[0];
				$p2HealTotal += $result[1];
				$petCurrHP += $result[1];
				$cbLogIcons .= $result[2];
			}
			if(has_atk($a, $tbName='abilities')){
				$result = add_to_dmg_array($p2Dmg, $a, $p, $side, $type, $tbName);
				$p2Dmg = $result[0];
				$cbLogIcons .= $result[1];
			}
			if(has_freeze($a, $player = 'p2', $tbName = 'abilities')){
				$oppFrozen = 2;
				$frzMsg = get_freeze_msg($a, $side, $oppID, $tbName = 'abilities');
				$cbLogIcons .= $frzMsg;
			}
		}
		if($a > 1000){ //species attack
			if(has_atk($a, $tbName='species_attacks')){
				$result = add_to_dmg_array($p2Dmg, $a, $p, $side, $type, $tbName);
				$p2Dmg = $result[0];
				$cbLogIcons .= $result[1];
			}
		}
	
	//player 2 stance icons
	//stance damage is displayed similar to weapons
		if($stanceM2 > 1){
			$tbName = 'stance';
			
			$result = add_to_dmg_array($p2Dmg, $a, $p, $side, $type, $tbName);
			//$p2Dmg = $result[0];
			$cbLogIcons .= $result[1];
		}
		
			
	//opp offense
		$side = "L";
		$type = "atk";
		foreach($oppOffense as $w){
			if($w){
				if(has_drain($w, $tbName='weapons')){
					$result = apply_drain($side, $type, $w, $tbName, $petCurrHP, $oppCurrHP);
					$petCurrHP = $result[0];
					$oppHealTotal += $result[1];
					$cbLogIcons .= $result[2];
				}
				if(has_atk($w, $tbName='weapons')){
					$result = add_to_dmg_array($oppDmg, $w, $oppP, $side, $type, $tbName);
					$oppDmg = $result[0];
					$cbLogIcons .= $result[1];
				}
				if(has_freeze($w, $player = 'p1', $tbName)){
					$petFrozen = 2;
					$frzMsg = get_freeze_msg($w, $side, $petname, $tbName = 'weapons');
					$cbLogIcons .= $frzMsg;
				}
			}
		}
		if($oppA > 10 && $oppA < 1000){
			if(has_drain($oppA, $tbName='abilities')){
				$result = apply_drain($side, $type, $oppA, $tbName, $petCurrHP, $oppCurrHP);
				$petCurrHP = $result[0];
				$oppHealTotal += $result[1];
				$cbLogIcons .= $result[2];
			}
			if(has_atk($oppA, $tbName='abilities')){
				$result = add_to_dmg_array($oppDmg, $oppA, $p, $side, $type, $tbName);
				$oppDmg = $result[0];
				$cbLogIcons .= $result[1];
			}
			if(has_freeze($oppA, $player = 'p1', $tbName = 'abilities')){
				$petFrozen = 2;
				$frzMsg = get_freeze_msg($oppA, $side, $petname, $tbName = 'abilities');
				$cbLogIcons .= $frzMsg;
			}
		}
		
	//opp stance icons
		if($oppStanceM > 1){
			$side = "L";
			$type = "atk";
			$tbName = 'stance';
			
			$result = add_to_dmg_array($oppDmg, $oppA, $oppP, $side, $type, $tbName);
			//$oppDmg = $result[0];
			$cbLogIcons .= $result[1];
		}
		
		

	//player2 defense
		$side = "L";
		$type = "def";
		if($a < 10){ //get def stance icons first; hold for later
			$defStanceIcons = output_def_icons($oppDmg, $w, $side, $type, $tbName='stance');
		}
		
		$p2Defense = array($w1, $w2, $a, $p);
		foreach($p2Defense as $w){
			if(has_def($w, $tbName='weapons')){ //icon defense
				$iconsBlocked = update_def_array_p2($p2IconsBlocked, $oppDmg, $w, $p, $tbName);
				$cbLogIcons .= output_def_icons($iconsBlocked, $w, $side, $type, $tbName);
			}
			if(has_ref($w, $tbName='weapons')){ //reflection
				$iconsBlocked = update_def_array_p2($p2IconsBlocked, $oppDmg, $w, $p, $tbName='reflect');
				$cbLogIcons .= output_ref_icons($iconsBlocked, $w, $oppStanceM, $side, $type, $tbName='weapons');
				
			//add reflected icons to dmg array, but not to combat log
			$result = add_to_dmg_array($p2Dmg, $iconsBlocked, $p, $side, $type, $tbName='reflect');
			$p2Dmg = $result[0];
			}
		}
		
		if($stanceM2 < 1){ //stance
			$cbLogIcons .= $defStanceIcons;
		}
		elseif($a > 10 && $a < 1000){ //faerie ability
			if(has_def($a, $tbName='abilities')){
				$iconsBlocked = update_def_array_p2($p2IconsBlocked, $oppDmg, $a, $p, $tbName);
				$cbLogIcons .= output_def_icons($iconsBlocked, $a, $side, $type, $tbName);
			}
		}
		elseif($a > 1000){ //species attack
			if(has_def($a, $tbName='species_attacks')){
				$iconsBlocked = update_def_array_p2($p2IconsBlocked, $oppDmg, $a, $p, $tbName);
				$cbLogIcons .= output_def_icons($iconsBlocked, $a, $side, $type, $tbName);
			}
		}
		
			
	//opp defense
		$side = "R";
		$type = "def";
		if($oppA < 10){ //get def stance icons first; hold for later
			$defStanceIcons = output_def_icons($p2Dmg, $oppA, $side, $type, $tbName='stance');
		}
		$oppDefense = array($oppW1, $oppW2, $oppA, $oppP);
		foreach($oppDefense as $w){
			if(has_def($w, $tbName='weapons')){
				$iconsBlocked = update_def_array_p1($oppIconsBlocked, $p2Dmg, $w, $oppP, $tbName);
				$cbLogIcons .= output_def_icons($iconsBlocked, $w, $side, $type, $tbName);
			}
			if(has_ref($w, $tbName='weapons')){ //reflection
				$iconsBlocked = update_def_array_p1($oppIconsBlocked, $p2Dmg, $w, $oppP, $tbName='reflect');
				$cbLogIcons .= output_ref_icons($iconsBlocked, $w, $oppStanceM, $side, $type, $tbName='weapons');
					
				//add reflected icons to dmg array, but not to combat log
				$result = add_to_dmg_array($oppDmg, $iconsBlocked, $oppP, $side, $type, $tbName='reflect');
				$oppDmg = $result[0];
			}
		}
		if($oppStanceM < 1){
			$cbLogIcons .= $defStanceIcons;
		}
		elseif($oppA > 10){
			if(has_def($oppA, $tbName='abilities')){
				$iconsBlocked = update_def_array_p1($oppIconsBlocked, $p2Dmg, $oppA, $oppP, $tbName);
				$cbLogIcons .= output_def_icons($iconsBlocked, $oppA, $side, $type, $tbName);
			}
		}
			

			
			
	//heals
	//player2 heal
		$side = "L";
		$type = "heal";
		foreach($p2Offense as $w){
			if(has_heal($w, $tbName='weapons')){
				$heal = get_heal_amount($w, $player='pet', $tbName);
				$petCurrHP += $heal;
				$p2HealTotal += $heal;
			}
		}
		if(has_heal($a, $tbName='abilities')){
			$heal = get_heal_amount($a, $player='pet', $tbName);
				$petCurrHP += $heal;
				$p2HealTotal += $heal;
		}
		if($p2HealTotal != 0){
			$healRow = output_cblog_heal($side, $p2HealTotal);
			$cbLogIcons .= $healRow;
		}
		
		
	//opp heal
		$side = "R";
		$type = "heal";
		foreach($oppOffense as $w){
			if(has_heal($w, $tbName='weapons')){
				$heal = get_heal_amount($w, $player='opp', $tbName);
				$oppCurrHP += $heal;
				$oppHealTotal += $heal;
			}
		}
		if(has_heal($oppA, $tbName='abilities')){
			$heal = get_heal_amount($oppA, $player='opp', $tbName);
				$oppCurrHP += $heal;
				$oppHealTotal += $heal;
		}
		if($oppHealTotal != 0){
			$healRow = output_cblog_heal($side, $oppHealTotal);
			$cbLogIcons .= $healRow;
		}
		
		
	//stealing items
		foreach($p2Offense as $w){
			if(has_steal($w, $tbName = 'weapons')){
				steal_weapon($w, $stealer='pet');
			}
		}
		
	//item generation
		foreach($p2Offense as $w){
			if(has_giveitem($w, $tbName='weapons')){
				generate_item($w, $tbName);
			}
		}
		if($a > 1000 && has_giveitem($a, 'species_attacks')){
			generate_item($a, 'species_attacks');
		}
		
	//TODO: maybe remove; add break checks when applying items/abilities
	//checks for breakable items/abilities
		remove_fragile_items($w1, $w2, $a, $player = 'p2');
		remove_fragile_items($oppW1, $oppW2, $oppA, $player = 'p1');
		
	//countdown freeze
	//db value indicates freeze status
	//2 = just frozen; 1 = cannot move; 0 = unfrozen
		$petFrozen--;
		$oppFrozen--;
			
			
	//calculate final dmg numbers
		$finalDmg = calc_dmg($p2Dmg, $p2StrMult, $oppStrMult, $stanceM2, $oppStanceM, $move = 'second');
		$p2FinalDmgDealt = $finalDmg[0];
		
		$finalDmg = calc_dmg($oppDmg, $oppStrMult, $p2StrMult, $oppStanceM, $stanceM2, $move = 'first');
		$oppFinalDmgDealt = $finalDmg[0];
		if(isset($finalDmg[1][0])){
			$cbLogIcons .= output_species_resist($finalDmg[1]);
		}
			
			
	//update HP values
		$petCurrHP = floor($petCurrHP - $oppFinalDmgDealt);
		if($petCurrHP < 0) {$petCurrHP = 0;}
		if($petCurrHP > $petMaxHP) {$petCurrHP = $petMaxHP;}
		
		$oppCurrHP = floor($oppCurrHP - $p2FinalDmgDealt);
		if($oppCurrHP < 0) {$oppCurrHP = 0;}
		if($oppCurrHP > $oppMaxHP) {$oppCurrHP = $oppMaxHP;}
			
	//final dmg row for combat log
		$cbLogEnd = '
			<tr>
				<td style="text-align:left;"><b>Damage:<br>' . $oppFinalDmgDealt . ' hp</b></td>
				<td style="text-align:left;"></td>
				<td style="text-align:right;"><b>Damage:<br>' . $p2FinalDmgDealt . ' hp</b></td>
			</tr>
		';
		
	//put combat log together
		$combatLog = $cbLogStart . $cbLogIcons . $cbLogEnd;
			
		if($petCurrHP <= 0 || $oppCurrHP <= 0){
			end_fight();
		}
		else{
			save_fight();
		}
	}
}


//checks that weapon generates 'atk' icons
//returns true/false
function has_atk($id, $tbName){
	$hasAtk = false;
	$tableName = $tbName;
	$con = connect_to_server();
	
	$sql = "SELECT `attack` FROM `$tableName` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$atk = $result['attack'];
	if($atk){
		$hasAtk = true;
	}
	
	return $hasAtk;
}

//checks that weapon generates 'def' icons
function has_def($id, $tbName){
	$hasDef = false;
	$tableName = $tbName;
	$con = connect_to_server();
	
	$sql = "SELECT `defense` FROM `$tableName` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$def = $result['defense'];
	if($def){
		$hasDef = true;
	}
	
	return $hasDef;
}


//checks that weapon reflects icons
function has_ref($id, $tbName){
	$hasRef = false;
	$tableName = $tbName;
	$con = connect_to_server();
	
	$sql = "SELECT `reflect` FROM `$tableName` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$ref = $result['reflect'];
	if($ref){
		$hasRef = true;
	}
	
	return $hasRef;
}


//checks for freeze %
//rolls for freeze; returns true only on success
function has_freeze($w, $player, $tbName){
	$con = connect_to_server();
	
	$sql = "SELECT * FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$p = $result['freeze'];
	
	//get useage info (break for battle, break on success, etc.)
	$use = $result['useage'];
	$use = explode(",", $use);
	$breakability = $use[0];
	$pBreak = $use[1];
	
	//roll for freeze success
	$frz = mt_rand() / mt_getrandmax();
	if($frz < $p){
		$frozen = true;
		
		//check useage; remove weapon from player if weap breaks
		if($breakability == 1){ //chance of breaking for battle
			if($pBreak){
				if(roll_for_breakage($pBreak)){
					remove_from_copy($w, $player, $tbName);	
				}
			}
			else{ //not sure what this one's for
				remove_from_copy($w, $player, $tbName);
			}
		}
		elseif($breakability == 2){ //once-per-battle freezers
			remove_from_copy($w, $player, $tbName);
		}
		elseif($breakability == 3){ //chance of breaking permanently
			if($pBreak){
				if(roll_for_breakage($pBreak)){
					remove_from_copy($w, $player, $tbName);
					//remove_from_main($w, $player, $tbName); //perm break disabled for sandbox mode
				}
			}
			else{
				remove_from_copy($w, $player, $tbName);
				//remove_from_main($w, $player, $tbName);
			}
		}
		elseif($breakability == 4){ //single-use weap
			remove_from_copy($w, $player, $tbName);
			//remove_from_main($w, $player, $tbName);
		}
	}
	else{
		$frozen = false;
	}
	
	return $frozen;
}


//checks for freeze mechanic
//returns true/false
function can_freeze($w, $tbName){
	$con = connect_to_server();
	
	$sql = "SELECT `freeze` FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$freezeCheck = $result['freeze'];
	if($freezeCheck){
		$freeze = true;
	}
	else{
		$freeze = false;
	}
	
	return $freeze;
}


//checks for drain
//returns true/false
function has_drain($w, $tbName){
	$con = connect_to_server();
	
	$sql = "SELECT `drain` FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$drainCheck = $result['drain'];
	if($drainCheck){
		$drain = true;
	}
	else{
		$drain = false;
	}
	
	return $drain;
}



//checks for heal
//returns true/false
function has_heal($w, $tbName){
	$con = connect_to_server();
	
	$sql = "SELECT `heal` FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$healCheck = $result['heal'];
	if($healCheck){
		$heal = true;
	}
	else{
		$heal = false;
	}
	
	return $heal;
}


//checks if weapon is a stealer
//returns true/false
function has_steal($w, $tbName){
	$con = connect_to_server();
	
	$sql = "SELECT `steal` FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$stealCheck = $result['steal'];
	if($stealCheck){
		$steal = true;
	}
	else{
		$steal = false;
	}
	
	return $steal;
}


//checks if weapon generates another item
//returns true/false
function has_giveitem($w, $tbName){
	$con = connect_to_server();
	
	$sql = "SELECT `item_spawn` FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$genCheck = $result['item_spawn'];
	if($genCheck){
		$gen = true;
	}
	else{
		$gen = false;
	}
	
	return $gen;
}


//checks for pick_one (sword of lameness, etc.)
//returns true/false
function has_pick_one($w){
	$con = connect_to_server();
	$sql = "SELECT `pick_one` FROM `weapons` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$pick_one = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	return $pick_one['pick_one'];
}



//calculates heal, returns amount healed
function get_heal_amount($w, $player, $tbName){
	global $petMaxHP, $petCurrHP, $oppMaxHP, $oppCurrHP;
	$con = connect_to_server();
	
	if($player == 'pet'){
		$maxHP = $petMaxHP;
		$currHP = $petCurrHP;
	}
	elseif($player == 'opp'){
		$maxHP = $oppMaxHP;
		$currHP = $oppCurrHP;
	}
	
	$percentHealLimit = $maxHP - $currHP; //prevents percent-based healers from overhealing
	
	$sql = "SELECT * FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$useage = $result['useage'];
	$heal = $result['heal'];
	
	//check for conditional healing (cond,heal_true,heal_false) (istaff/wodf)
	if(substr($heal, 0, 1) == '('){
		$heal = str_replace("(", "", $heal);
		$heal = str_replace(")", "", $heal);
		$heal = explode(",", $heal);
		$cond = $heal[0];
		if($cond > 1000){ //% condition
			$cond = ($cond - 1000) / 100;
			$hp = $currHP / $maxHP;
		}
		else{
			$hp = $currHP;
		}
		
		//set heal amount
		if($hp <= $cond){
			$heal = $heal[1];
		}
		else{
			$heal = $heal[2];
		}
	}
	elseif(strpos($heal, ',') !== false){
		$getFlag = explode(',', $heal);
		$heal = $getFlag[0];
		$flag = $getFlag[1];
	}
	
	if($heal > 1000){ //indicates percent healer
		if(!isset($flag)){
			$healPercent = ($heal - 1000) / 100;
			$healAmt = $maxHP * $healPercent;
			if($healAmt > $percentHealLimit){ //percent healers can't overheal
				$healAmt = $percentHealLimit;
			}
		}
		elseif($flag == 'm'){ //heal based on missing health
			$healPercent = ($heal - 1000) / 100;
			$healAmt = ($maxHP - $currHP) * $healPercent;
			if($healAmt > $percentHealLimit){ //percent healers can't overheal
				$healAmt = $percentHealLimit;
			}
		}
	}
	//heal range
	elseif(strpos($heal, ":") !== false){
		$healRange = explode(":", $heal);
		$healMin = $healRange[0];
		$healMax = $healRange[1];
		$healAmt = rand($healMin, $healMax);
	}
	else{
		$healAmt = $heal;
	}
	
	//break
	
	
	return $healAmt;
}


//takes in weapon id, left/right output, and type (atk/def/ref)
//returns a formatted row of icons to be added to the combat log
function output_icons($id, $iconsRolled, $side, $type, $tbName){
	global $oppName, $petname;

		$newRow;
		
		$iconNames = array('fire', 'water', 'air', 'earth', 'light', 'dark', 'phys');
		$bgColor = get_combat_log_color($type);
		$side = strtoupper($side);
		$iconArray = $iconsRolled;
		if($tbName == 'weapons'){
			$weaponName = get_weapon_name($id);
		}
		elseif($tbName == 'species_attacks'){
			$weaponName = get_species_attack_name($id);
		}
		elseif($tbName == 'abilities'){
			$weaponName = get_ability_name($id);
		}
		elseif($tbName == 'stance'){
			$weaponName = get_stance_name($id);
		}
		
		if(($side=="L" && $type=="atk") || ($side=="R" && $type=="def")){
			$flavorText = "<b>" . $oppName . "</b> attacks with their <b>" . $weaponName . "</b>!";
		}
		else{
			$flavorText = "You attack with your <b>" . $weaponName . "</b>!";
		}
		
		
		
		
		//start output row
			if($side=="R"){
				$newRow = '
					<tr style="background-color:rgb(' . $bgColor . ')">
						<td style="text-align:left;background-color:rgb(170,171,254);"></td>
						<td style="text-align:left; width:50%;">' . $flavorText . '</td>
						<td style="text-align:right;">
				';
				
				$newRow .= draw_icons($iconArray, $type);
				
				$newRow .= '</td>
					</tr>';
			}
			else{
				$newRow = '
					<tr style="background-color:rgb(' . $bgColor . ')">
						<td style="text-align:left;">
						';
						
				$newRow .= draw_icons($iconArray, $type);
						
				$newRow .= '
						</td>
						<td style="text-align:left;">' . $flavorText . '</td>
						<td style="text-align:right;background-color:rgb(170,171,254);"></td>
					</tr>
				';
			}
		
		return $newRow;
}


//returns defensive icons scaled by boost
//formatted for combat log
function output_def_icons($iconsBlocked, $id, $side, $type, $tbName){
	global $oppName, $petname;
	
	//var to store stuff to add to combat log
	$newRow;
	
	$iconNames = array('fire', 'water', 'air', 'earth', 'light', 'dark', 'phys');
	$bgColor = get_combat_log_color($type);
	$side = strtoupper($side);
	$iconArray = $iconsBlocked;
	if($tbName == 'weapons'){
		$weaponName = get_weapon_name($id);
	}
	elseif($tbName == 'species_attacks'){
		$weaponName = get_species_attack_name($id);
	}
	elseif($tbName == 'abilities'){
		$weaponName = get_ability_name($id);
	}
	elseif($tbName == 'stance'){
		$iconArray[1] = $iconsBlocked[1];
		foreach($iconArray[0] as $icon=>$amt){ //defensive stances only display 1 icon for each type blocked
			if($amt > 0){
				$iconArray[1][$icon] = 1;
			}
		}
	}
	
	if($side=="R" && $type=="def"){
		if($tbName == 'stance'){
			$flavorText = "<b>" . $oppName . "</b> <i>defends</i> themself against your attack!";
		}
		else{
			$flavorText = "<b>" . $oppName . "</b> defends with their <b>" . $weaponName . "</b>!";
		}
	}
	else{
		if($tbName == 'stance'){
			$flavorText = "You <i>defend</i> yourself against <b>" . $oppName . "</b>!";
		}
		else{
			$flavorText = "You defend with your <b>" . $weaponName . "</b>!";
		}
	}
	
	
	
	
	//start output row
		if($side=="R"){
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;background-color:rgb(170,171,254);"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;">
			';
			
			$newRow .= draw_icons($iconArray, $type);
			
			$newRow .= '</td>
				</tr>';
		}
		else{
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;">
					';
					
			$newRow .= draw_icons($iconArray, $type);
					
			$newRow .= '
					</td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
		}
	
	return $newRow;
}


//returns reflected icons
//formatted for combat log
function output_ref_icons($iconsBlocked, $id, $stanceM, $side, $type, $tbName){
	global $oppName, $petname;
	
	$pref = false;
	
	//var to store stuff to add to combat log
	$newRow;
	
	$iconNames = array('fire', 'water', 'air', 'earth', 'light', 'dark', 'phys');
	$bgColor = get_combat_log_color($type);
	$side = strtoupper($side);
	$s = $stanceM;
	$iconArray = $iconsBlocked;
	
	//copy reg-reflected icons from def[1] to ref[2]
	foreach($iconArray[1] as $icon=>$amt){
		$iconArray[2][$icon] = $amt;
	}
	
	if($tbName == 'weapons'){
		$weaponName = get_weapon_name($id);
	}
	elseif($tbName == 'abilities'){
		$weaponName = get_ability_name($id);
	}
	
	//which side to put the icons on
	if(($side=="L" && $type=="atk") || ($side=="R" && $type=="def")){
		$flavorText = "<b>" . $oppName . "</b> reflects your attack with their <b>" . $weaponName . "</b>!";
	}
	else{
		$flavorText = "You reflect <b>" . $oppName . "'s</b> attack with your <b>" . $weaponName . "</b>!";
	}
	
	//check for regular ref or pref
	//	'pref' = pseudo-reflected icons like with prickly potion
	foreach($iconArray[3] as $icon=>$amt){
		if($amt){
			$pref = true;
		}
	}
	
	//set display stuff based on ref/pref
	if($pref){
		$flavorText = "You attack <b>" . $oppName . "</b> with your <b>" . $weaponName . "</b>!";
		foreach($iconArray[3] as $icon=>$amt){ //transfer pref to def for output in combat log
			$iconArray[1][$icon] = $amt;
		}
	}
	else{
		//include stance icons in reflection display
		if($s < 1){ //offensive/neutral stances  only
			$s = 1;
		}
		
		foreach($iconArray[1] as $icon=>$amt){
				$iconArray[1][$icon] = $amt * $s;
		}
	}
	
	
	
	//loop through all ref-related subarrays; output any non-empty subarray
	//draw_icons() only pulls from subarray[1], so index to be output needs to be copied to [1]
	for($i=2;$i<6;$i++){
		foreach($iconArray[$i] as $icon=>$amt){
			if($amt){
				$outputArray[1][$icon] += $amt;
			}
		}
	}

	//start output row
	if($side=="R"){
		$newRow .= '
			<tr style="background-color:rgb(' . $bgColor . ')">
				<td style="text-align:left;background-color:rgb(170,171,254);">';
				
			if(!$pref){$newRow .= draw_icons($outputArray, $type);}
				
		$newRow .= '
				</td>
				<td style="text-align:left;">' . $flavorText . '</td>
				<td style="text-align:right;">';
				
		$newRow .= draw_icons($outputArray, $type);
				
		$newRow .= '</td>
			</tr>';
	}
	else{
		$newRow .= '
			<tr style="background-color:rgb(' . $bgColor . ')">
				<td style="text-align:left;">
				';
							
		if(!$pref){$newRow .= draw_icons($outputArray, $type);} //output for ref, not for pref
						
		$newRow .= '
				</td>
				<td style="text-align:left;">' . $flavorText . '</td>
				<td style="text-align:right;background-color:rgb(170,171,254);">';
						
		$newRow .= draw_icons($outputArray, $type);
						
		$newRow .= '
				</td>
			</tr>
		';
	}
	
	return $newRow;
}


//returns up to 1 line each for resist/weakness
//(the real bd did not display this)
function output_species_resist($iconsResisted){
	global $oppName, $petname;
	
	//var to store stuff to add to combat log
	$newRow = '';
	$iconNames = array('fire', 'water', 'air', 'earth', 'light', 'dark', 'phys');
	$rIcons = $iconsResisted[0]; //icon types resisted
	$wIcons = $iconsResisted[1]; //icon types weaknessed
	$iconArray = initialize_dmg_arrays();
	
	
	//output line for weakness icons
	if($wIcons != NULL){
		$bgColor = get_combat_log_color($type = 'atk');
		$side = strtoupper($side = 'left');
	
		foreach($wIcons as $icon){
			$iconArray[0][$icon] = 1;
		}
		
		$flavorText = "You take extra damage from <b>" . $oppName . "'s</b> "; //begin line
		$count = count($wIcons);
		if($count > 1){
			for($i=0;$i<$count-1;$i++){
				$flavorText .= $wIcons[$i] . ", ";
			}
			$flavorText .= "and " . $wIcons[$i];
		}
		else{
			$flavorText .= $wIcons[0];
		}
		
		$flavorText .= " attack!";
		
		$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;">
					';
					
		$newRow .= draw_icons($iconArray, $type);
					
		$newRow .= '
					</td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
	}
	
	
	
	//output line for resisted icons
	if($rIcons != NULL){
		$bgColor = get_combat_log_color($type = 'def');
		$side = strtoupper($side = 'left');
	
		foreach($rIcons as $icon){ //display 1 icon for each type resisted
			$iconArray[1][$icon] = 1;
		}
		
		$flavorText = "You resist some of <b>" . $oppName . "'s</b> "; //begin line
		$count = count($rIcons);
		if($count > 1){
			for($i=0;$i<$count-1;$i++){
				$flavorText .= $rIcons[$i] . ", ";
			}
			$flavorText .= "and " . $rIcons[$i];
		}
		else{
			$flavorText .= $rIcons[0];
		}
		
		$flavorText .= " attack!";
		
		$newRow .= '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;">
					';
					
		$newRow .= draw_icons($iconArray, $type);
					
		$newRow .= '
					</td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
	}
	
	$newRow = preg_replace('/phys\ /', 'physical ', $newRow);
	return $newRow;
	
}

//returns row for healing
function output_cblog_heal($side, $healAmt){
	global $oppName, $petname;
	
	//var to store stuff to add to combat log
	$newRow;
	
	$bgColor = get_combat_log_color($type='heal');
	$side = strtoupper($side);
	$heartIcon = '<img src="img/heart.png">';

	
	if($side=="R"){
		$flavorText = "<b>" . $oppName . "</b> heals!";
	}
	else{
		$flavorText = $petname . " heals!";
	}
	
	
	
	
	//start output row
		if($side=="R"){
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;background-color:rgb(170,171,254);"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;">' . $heartIcon . '<b>(' . $healAmt .  ' hp)</b></td>
				</tr>';
		}
		else{
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;">' . $heartIcon . '<b>(' . $healAmt .  ' hp)</td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
		}
	
	return $newRow;
}

//takes in assoc array of (icon=>amt)
//	and type (atk/def)
//outputs wall of icons formatted for combat log
function draw_icons($iconArray, $type){
	$iconsDrawn = '';
	
	$def = "";
	$i = 0; //0 = atk icons, 1 = def icons
	
	if($type=="def"){
		$def = "_def";
		$i = 1;
	}
	
	$row='';
	$count = 0;
	foreach($iconArray[$i] as $icon=>$amt){
		$firstTwo = 0; //real bd always put a linebreak after the first 2 icons, dunno why
		for($n=0; $n<$amt; $n++){
			$iconsDrawn .= '<img src="img/' . $icon . $def . '.png">';
			$count++;
			$firstTwo++;
			if(($count==10) || ($firstTwo==2)){
				$iconsDrawn .= "<br>";
				$count = 0;
				$firstTwo = -100;
			}
		}
	}
	return $iconsDrawn;
}


//for combat log background color
//returns rgb values based on type
function get_combat_log_color($type){
	/* -div bg (170,171,254)
	-offense (220,170,169)
	-defense (171,221,170)
	-reflect (none)
	-heal (240,220,170) */
	
	$type = strtolower($type);
	if($type=="atk"){
		$color = "220,170,169";
	}
	elseif($type=="def"){
		$color = "171,221,170";
	}
	elseif($type=="ref"){
		$color = "170,171,254";
	}
	elseif($type=="heal"){
		$color = "240,220,170";
	}
	else{
		$color = "170,171,254";
	}
	
	return $color;
}



//returns row for freeze message in combat log
function get_freeze_msg($weaponID, $side, $name, $tbName){
	global $oppName, $petname;

	$newRow;
	
	$bgColor = get_combat_log_color($type = 'atk');
	$side = strtoupper($side);
	$weaponName = ($tbName == 'weapons' ? get_weapon_name($weaponID) : get_ability_name($weaponID));
	
	if($side=="L"){
		$flavorText = "<b>" . $oppName . "</b> freezes you with their <b>" . $weaponName . "</b>!";
	}
	else{
		$flavorText = "You freeze <b>" . $oppName . "</b> with your <b>" . $weaponName . "</b>!";
	}
	
	
	
	
	//start output row
		if($side=="R"){
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;background-color:rgb(170,171,254);"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;"></td>
				</tr>';
		}
		else{
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
		}
	
	return $newRow;
}


//returns table row for steal message in combat log
function get_steal_msg($weaponID, $side, $stolen){
	global $oppName, $petname;

	$newRow;
	
	$bgColor = get_combat_log_color($type = 'atk');
	$side = strtoupper($side);
	$weaponName = get_weapon_name($weaponID);
	
	if($side=="L"){
		$flavorText = "<b>" . $oppName . "</b> steals one of your weapons with their <b>" . $weaponName . "</b>!";
	}
	else{
		if(!$stolen){
			$flavorText = "You steal one of <b>" . $oppName . "'s</b> weapons, but they have nothing left to steal!";
		}
		else{
			$flavorText = "You steal one of <b>" . $oppName . "'s</b> weapons with your <b>" . $weaponName . "</b>!";
		}
	}
	
	
	
	
	//start output row
		if($side=="R"){
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;background-color:rgb(170,171,254);"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;"></td>
				</tr>';
		}
		else{
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
		}
	
	return $newRow;
}


//returns table row for drain message in combat log
function get_drain_msg($id, $side, $drainAmt, $tbName){
	global $oppName, $petname;

	$newRow;
	
	$bgColor = get_combat_log_color($type = 'atk');
	$side = strtoupper($side);
	
	if($tbName == 'weapons'){
		$weaponName = get_weapon_name($id);
	}
	elseif($tbName == 'abilities'){
		$weaponName = get_ability_name($id);
	}
	
	if($side=="L"){
		$flavorText = "<b>" . $oppName . "</b> drains <b>" . $drainAmt . "</b> of your hitpoints with their <b>" . $weaponName . "</b>!";
	}
	else{
		$flavorText = "You drain <b>" . $drainAmt . "</b> of <b>" . $oppName . "'s</b> hitpoints with your <b>" . $weaponName . "</b>!";
	}
	
	
	
	
	//start output row
		if($side=="R"){
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;background-color:rgb(170,171,254);"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;"></td>
				</tr>';
		}
		else{
			$newRow = '
				<tr style="background-color:rgb(' . $bgColor . ')">
					<td style="text-align:left;"></td>
					<td style="text-align:left;">' . $flavorText . '</td>
					<td style="text-align:right;background-color:rgb(170,171,254);"></td>
				</tr>
			';
		}
	
	return $newRow;
}


//uses weapon id to pull info from db
//returns array(atk[...], def[...]) of icons rolled
function get_weapon_data($weapon, $tbName){
	global $oppDmg;
	$array = initialize_dmg_arrays();
	$con = connect_to_server();
		
	$sql = "SELECT * FROM `$tbName` WHERE `id` = '$weapon'";
	$result = mysqli_query($con, $sql);
	
	if($result){
		$data = mysqli_fetch_array($result);
		$atk = $data['attack'];
		$def = $data['defense'];
		$ref = $data['reflect'];
		
		//parse data, add to return array
		if($atk){ //attack
			if(substr($atk, 0, 1) == "["){ //check for weak hit/strong hit
				$atk = substr_replace($atk, "", 0, 1);
				$atk = substr_replace($atk, "", -1, 1);
				$atk = explode("][", $atk);
				$probs = array();
				$sum = 0;
				foreach($atk as $hit){ //collect probabilities for each hit
					$getP = explode(":", $hit);
					$p = end($getP);
					$index = count($probs);
					$probs[$index] = $p + $sum;
					$sum += $p;
				}
				$probs = array_reverse($probs, TRUE);
				$roll = mt_rand() / mt_getrandmax();
				foreach($probs as $i => $p){
					if($roll <= $p){
						$indexRolled = $i;
					}					
				}
				$atk = $atk[$indexRolled]; //get icons for whichever hit was rolled
			}
			
			$atk = explode(":", $atk); //array of (icon,#) pairs
			foreach($atk as $a){
				$a = explode(",", $a); //mini array [icon,#]
				$icon = $a[0];
				$num = $a[1];
				if(substr($num, 0, 1) == "("){ //indicates variable icon amount
					$num = str_replace("(", '', $num);
					$num = str_replace(")", '', $num);
					$num = explode(";", $num);
					$num = roll_for_icons($num[0], $num[1], $num[2], $num[3], $num[4]);
				}
				$array[0][$icon] += $num;
			}
		}

		if($def){ //defense
			$def = explode(":", $def);
			foreach($def as $d){
				$d = explode(",", $d);
				$icon = $d[0];
				$num = $d[1];
				$array[1][$icon] += $num;
			}
		}
		
		if($ref){ //reflection
			if(substr($ref,0,1) == "["){
				$ref = str_replace("[", "", $ref);
				$ref = str_replace("]", "", $ref);
				$iconToReflect = roll_for_icon_type();
				while($iconToReflect == 'phys'){
					$iconToReflect = roll_for_icon_type();
				}
				$getIconRange = explode(":", $ref);
				foreach($getIconRange as $i){
					if(strpos($i, $iconToReflect) !== false){
						$ref = $i;
					}
				}
			}
			$ref = explode(":", $ref);
			foreach($ref as $r){
				$r = explode(",", $r);
				$icon = $r[0];
				$num = $r[1];
				if(count($r) > 2){ //either variable ref, or weird ref
					if(is_numeric($r[2])){ //variable; roll for %; assign to $num
						$min = $r[1];
						$max = $r[2];
						$num = roll_for_icons($min, $max, 1, -1, 0);
					}
					else{ //weird ref (istaff, jwand); r[2] is fake-icon to reflect
						//TODO: weird reflect only when $icon is present (gets blocked by the first call)
						//TODO: add unreflected $icon in a way that it won't be blocked
						$fakeIcon = $r[2];
						$fakeIconCount = $oppDmg[0][$icon];
						$array[2][$fakeIcon] += (($r[1] - 1000) / 100) * $oppDmg[0][$icon];
						$num = 0;
					}
				}
				
				$array[2][$icon] += $num;
			}
		}
	}
	return $array;
}


//gets ability info by id
//returns dmg array with info
function get_ability_data($a, $p){
	$array = initialize_dmg_arrays();
	$con = connect_to_server();
	
	$sql = "SELECT * FROM `abilities` WHERE `id` = '$a'";
	$result = mysqli_query($con, $sql);
	
	
	if($result){
		$data = mysqli_fetch_array($result);
		$atk = $data['attack'];
		$def = $data['defense'];
		$req = $data['power_req'];
		$useage = $data['useage'];
		$scale = scale_to_power($p, $req); //some abilities don't grant full effect at <40% power
		
		//parse data, add to return array
		if($atk){
			$atk = explode(":", $atk); //array of (icon,#) pairs
			foreach($atk as $att){
				$att = explode(",", $att); //mini array [icon,#]
				$icon = $att[0];
				$num = $att[1];
				if(substr($num, 0, 1) == "("){ //indicates variable icon amount
					$num = str_replace("(", '', $num);
					$num = str_replace(")", '', $num);
					$num = explode(";", $num);
					$num = roll_for_icons($num[0], $num[1], $num[2], $num[3], $num[4]);
				}
				$array[0][$icon] += $num;
			}
		}
		if($def){
			$def = explode(":", $def);
			foreach($def as $d){
				$d = explode(",", $d);
				$icon = $d[0];
				$num = $d[1];
				//vector means variable icons
				//roll for icon amt
				if(substr($num, 0, 1) == "("){
					$num = str_replace("(", '', $num);
					$num = str_replace(")", '', $num);
					$num = explode(";", $num);
					$num = roll_for_icons($num[0], $num[1], $num[2], $num[3], $num[4]);
				}
				$array[1][$icon] += $num;
			}
		}
	}
	$array[] = array('scale' => $scale);
	$array[] = array('useage' => $useage);
	return $array;
}


//adds icons/effects to master array for combat log
//and updates dmg array
function add_to_dmg_array($dmgArray, $w, $p, $side, $type, $tbName){
	$iconsRolled = initialize_dmg_arrays();
	$wIcons = initialize_dmg_arrays();
	$sIcons = initialize_dmg_arrays();
	$rIcons = initialize_dmg_arrays();
	$cbLogIcons = initialize_dmg_arrays();
	$result = array();
		
	if($tbName == 'weapons'){
		$wIcons = get_weapon_data($w, $tbName);
		$i = 0;
	}
	elseif($tbName == 'species_attacks'){
		$wIcons = get_weapon_data($w, $tbName);
		$i = 0;
	}
	elseif($tbName == 'abilities'){
		$wIcons = get_ability_data($w, $p);
		$i = 0;
	}
	elseif($tbName == 'stance'){ //stance icons are displayed, but not counted for damage
		$m = get_stance_multiplier($w);
		$sIcons = initialize_dmg_arrays();
		
		foreach($sIcons[0] as $icon=>$amt){
			$sIcons[0][$icon] = ($m - 1) * $dmgArray[0][$icon];
		}
	}
	elseif($tbName == 'reflect'){ //reflected icons are added to dmg array, but not output to cblog
		//$w contains icon array in this case
		$wIcons = $w; //to get p-ref icons
		foreach($wIcons[2] as $icon=>$amt){ //pull from def array and add to ref array
			$wIcons[2][$icon] = $w[1][$icon];
		}
		
		for($i=2;$i<6;$i++){ //runs through the different types of reflected icons (I think)
			foreach($dmgArray[$i] as $icon=>$amt){
				$dmgArray[$i][$icon] += $wIcons[$i][$icon]; //for dmg calc
				$iconsRolled[$i][$icon] += $wIcons[$i][$icon] + $sIcons[$i][$icon]; //for combat log
			}
		}
	}
	
	if($tbName != 'reflect' && $tbName != 'stance'){
		foreach($dmgArray[$i] as $icon=>$amt){
			$dmgArray[$i][$icon] += $wIcons[$i][$icon]; //for dmg calc
			$iconsRolled[$i][$icon] += $wIcons[$i][$icon] + $sIcons[$i][$icon]; //for combat log
		}
	}
	
	if($tbName == 'stance'){
		$iconsRolled = $sIcons;
	}
	
	if($tbName != 'reflect'){
		$cbLogIcons = output_icons($w, $iconsRolled, $side, $type, $tbName);
	}
	
	$result[] = $dmgArray;
	$result[] = $cbLogIcons;
		
	return $result;
}



//applies drain/heal
//returns updated hp values
function apply_drain($side, $type, $id, $tbName, $hpToDrain, $hpToHeal){
	$con = connect_to_server();
	
	$sql = "SELECT * FROM `$tbName` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$drainData = $result['drain'];
	$useage = $result['useage'];
	
	if(is_numeric($drainData)){ //fixed drain amount
		$drainMin = $drainData;
		$drainMax = $drainData;
	}
	elseif(strpos($drainData, ":") !== false){ //variable drain
		$drainArray = explode(":", $drainData);
		$drainMin = $drainArray[0];
		$drainMax = $drainArray[1];
	}
	elseif(strpos($drainData, ",") !== false){ //has flags indicating type of drain
		$drainArray = explode(",", $drainData);
		$drain = $drainArray[0];
		$drainMax = (is_numeric($drainArray[1]) ? $drainArray[1] : '');
		$c = (in_array('c', $drainArray) ? true : false); //drain from current hp
		$heal = (in_array('h', $drainArray) ? true : false); //drain-heal
	}

	
	$amtDrained = 0;
	$amtHealed = 0;
	
	//TODO: scale drain to power for abilities
	if($drain > 1000){ //percent drain
		$drainPercent = ($drain - 1000) / 100;
		$amtDrained = $hpToDrain * $drainPercent;
		if($drainMax && $amtDrained > $drainMax){
			$amtDrained = $drainMax;
		}
		$newHPAmt = $hpToDrain - $amtDrained;
	}
	else{ //flat drain range
		$amtDrained = ($drainMin ? rand($drainMin, $drainMax) : $drain);
		$newHPAmt = $hpToDrain - $amtDrained;
	}
	
	$amtDrained = ceil($amtDrained);
	
	//apply heal
	if($heal){
		$amtHealed = $amtDrained;
	}
	
	$iconArray = initialize_dmg_arrays();
	$cbLog = get_drain_msg($id, $side, $amtDrained, $tbName);
	//echo "tbName1: " . $tbName . "<br>";
	
	$result = array();
	$result[] = $newHPAmt;
	$result[] = $amtHealed;
	$result[] = $cbLog;
	
	return $result;
}


//calculates icons from stances
//returns dmg array of stance icons to be displayed
function get_stance_icons($dmgArray, $stance){
	$result = array();
	
	$m = $stance;
	$stanceIcons = initialize_dmg_arrays();
		
	foreach($stanceIcons[0] as $icon=>$amt){
		$stanceIcons[0][$icon] = ($m - 1) * $dmgArray[0][$icon];
	}
}


//subtracts icons defended from atk icons
//returns array of actual icons blocked scaled by def:str boost ratio
function update_def_array_p1($defArray, $dmgArray, $w, $p, $tbName){
	global $oppStr, $oppDef, $petname;
	global $p2Dmg;
	
	$dmgBlocked = 0;
	$dmgBlockedInIcons = initialize_dmg_arrays();
	$i = 1; //index for damage array; 1=def, 2=ref, 3=p-ref
	$scale = 1; //to scale ability effect to power used
	
	$atkM = get_pet_str_mult($petname);
	$defM = get_opp_def_mult($oppDef);
	
	
	if($tbName == 'weapons'){
		$wDefIcons = get_weapon_data($w, $tbName);
	}
	elseif($tbName == 'reflect'){
		$wDefIcons = get_weapon_data($w, 'weapons');
		$i = 2;
	}
	elseif($tbName == 'abilities'){
		$wDefIcons = get_ability_data($w, $p);
		$scale = $wDefIcons[6]['scale'];
		remove_ability($w, $player='p1');
	}

	foreach($wDefIcons[$i] as $icon=>$defAmt){
		$dmgBlockedScaled = 0;
		if($defAmt > 0){
			if($defAmt > 1000){ //%-based defense/reflection
				//regular atk icons
				$atkIconAmt = $dmgArray[0][$icon];
				$percentDef = convert_percent_based_def($defAmt, $atkIconAmt, $scale);
				$dmgArray[0][$icon] = $atkIconAmt - $percentDef;
				$dmgBlocked = $percentDef * $atkM;
				
				//reflected icons ([2] -> [4] for reflection)
				$atkIconAmt = $dmgArray[2][$icon];
				$percentDef2 = convert_percent_based_def($defAmt, $atkIconAmt, $scale);
				$dmgArray[2][$icon] = $atkIconAmt - $percentDef2;
				$dmgBlocked2 = $percentDef2 * $atkM;
				$dmgBlockedInIcons[4][$icon] += $percentDef2;
				
				//p-ref icons ([3] -> [5] for reflection)
				$atkIconAmt = $dmgArray[3][$icon];
				$percentDef3 = convert_percent_based_def($defAmt, $atkIconAmt, $scale);
				$dmgArray[3][$icon] = $atkIconAmt - $percentDef3;
				$dmgBlocked3 = $percentDef3 * $atkM;
				$dmgBlockedInIcons[5][$icon] += $percentDef3;
				
				//scale display icons for mismatched str/def boosts
				if($tbName == 'reflect'){ //ref icons don't get scaled
					$dmgBlockedScaled = $percentDef;
				}
				else{ //for regular defense
					$dmgBlockedScaled = $dmgBlocked / $defM;
				}
			}
			else{ //icon-based defense
				if($tbName == 'reflect'){ //pref icons
					$dmgBlockedInIcons[3][$icon] += $defAmt;
				}
				elseif(!empty($dmgArray[0][$icon])){ //regular icon-based defense
					$dmg = $dmgArray[0][$icon] * $atkM; //raw dmg recieved
					$dmgAdjusted = $dmg - $defAmt * $defM; //dmg after defense
					
					if($dmgAdjusted < 0){ //dmg dealt can't go below 0
						$dmgArray[0][$icon] = 0;
					}
					else{
						$dmgBlocked = $defAmt * $defM;
						$dmgArray[0][$icon] = $dmgAdjusted / $atkM;
					}
					//# of def icons to show in combat log
					$dmgBlocked = $defAmt * $defM; //for uncapped defense
					$dmgBlockedScaled = $dmgBlocked / $atkM;
				}
			}
				
			$dmgBlockedInIcons[1][$icon] += $dmgBlockedScaled;
		}
	}

	$p2Dmg = $dmgArray;
	return $dmgBlockedInIcons;
}

function update_def_array_p2($defArray, $dmgArray, $w, $p, $tbName){
	global $oppStr, $oppDef, $petname;
	global $oppDmg;
	
	$dmgBlocked = 0;
	$dmgBlockedInIcons = initialize_dmg_arrays();
	$i = 1; //index for damage array; 1=def, 2=ref, 3=pref
	$scale = 1; //to scale ability effect to power used
	
	$atkM = get_opp_str_mult($oppStr);
	$defM = get_pet_def_mult($petname);
	
	
		if($tbName == 'weapons' || $tbName == 'species_attacks'){
			$wDefIcons = get_weapon_data($w, $tbName);
		}
		elseif($tbName == 'reflect'){
			$wDefIcons = get_weapon_data($w, 'weapons');
			$i = 2;
		}
		elseif($tbName == 'abilities'){
			$wDefIcons = get_ability_data($w, $p);
			$scale = $wDefIcons[6]['scale'];
			remove_ability($w, $player='p2');
		}
			
		foreach($wDefIcons[$i] as $icon=>$defAmt){
			$dmgBlockedScaled = 0;
				if($defAmt > 1000){ //%-based defense/reflection
					$atkIconAmt = $dmgArray[0][$icon];
					$percentDef = convert_percent_based_def($defAmt, $atkIconAmt, $scale);
					$dmgArray[0][$icon] = $atkIconAmt - $percentDef;
					$dmgBlocked = $percentDef * $atkM;
					
					//scale display icons for mismatched str/def boosts
					if($tbName == 'reflect'){ //ref icons don't get scaled
						$dmgBlockedScaled = $percentDef;
					}
					else{ //for regular defense
						$dmgBlockedScaled = $dmgBlocked / $defM;
					}
				}
				else{ //icon-based defense
					if($tbName == 'reflect'){ //pref icons
						$dmgBlockedInIcons[3][$icon] += $defAmt;
					}
					elseif($dmgArray[0][$icon] > 0){ //regular icon-based defense
						$dmg = $dmgArray[0][$icon] * $atkM; //raw dmg recieved
						$dmgAdjusted = $dmg - $defAmt * $defM; //dmg after defense
						
						if($dmgAdjusted < 0){ //dmg dealt can't go below 0
							$dmgArray[0][$icon] = 0;
						}
						else{
							$dmgBlocked = $defAmt * $defM;
							$dmgArray[0][$icon] = $dmgAdjusted / $atkM;
						}
						//# of def icons to show in combat log
						$dmgBlocked = $defAmt * $defM; //for uncapped defense
						$dmgBlockedScaled = $dmgBlocked / $atkM;
					}
				}
					
			$dmgBlockedInIcons[1][$icon] += $dmgBlockedScaled;
		}

	$oppDmg = $dmgArray;
	//echo "adjusted dmg array: <br>";
	//print_r($dmgArray);
	return $dmgBlockedInIcons;
}


//converts # stored in db to %
//returns # of icons blocked
function convert_percent_based_def($defAmt, $atkIconAmt, $scale){
	$pDef = $defAmt - 1000;
	$pDef = $pDef / 100 * $scale;
	
	$iconsBlocked = $atkIconAmt * $pDef;
	
	return $iconsBlocked;
}


//pulls opp moveset from database
//rolls for move and returns array containing (w1, w2, a)
function roll_opp_moveset($oppID){
	$con = connect_to_server();
	$sql = "SELECT `moveset` FROM `challengerdb` WHERE `id` = '$oppID'";
	
	$result = mysqli_query($con, $sql);
	$moves = mysqli_fetch_array($result);
	$moveset = $moves['moveset'];
	mysqli_free_result($result);
	
	$moveset = explode(":", $moveset);
	
	$max = count($moveset) - 1;
	$i = rand(0, $max);
	
	$move = $moveset[$i];
	$move = explode(",", $move);
	
	return $move;
}


//returns an initialized array with full set of icons for atk/def/ref
//array structure: [atk][icon=>#]..., [def][icon=>#]..., [ref][icon=>#]..., [heal], [freeze]
function initialize_dmg_arrays(){
	$array = array(
				array( //offense 0
					'fire' => 0,
					'water' => 0,
					'air' => 0,
					'earth' => 0,
					'light' => 0,
					'dark' => 0,
					'phys' => 0),
				array( //defense 1
					'fire' => 0,
					'water' => 0,
					'air' => 0,
					'earth' => 0,
					'light' => 0,
					'dark' => 0,
					'phys' => 0),
				array( //reflect 2
					'fire' => 0,
					'water' => 0,
					'air' => 0,
					'earth' => 0,
					'light' => 0,
					'dark' => 0,
					'phys' => 0),
				array( //pseudoreflect 3
					'fire' => 0,
					'water' => 0,
					'air' => 0,
					'earth' => 0,
					'light' => 0,
					'dark' => 0,
					'phys' => 0),
				array( //ref-ref 4
					'fire' => 0,
					'water' => 0,
					'air' => 0,
					'earth' => 0,
					'light' => 0,
					'dark' => 0,
					'phys' => 0),
				array( //ref-pref 5
					'fire' => 0,
					'water' => 0,
					'air' => 0,
					'earth' => 0,
					'light' => 0,
					'dark' => 0,
					'phys' => 0)
				);
				
	return $array;
}


//finds saved battle in db; loads data into pet/opp stats
//if no battle found, initializes new battle
//called at beginning of each round
function load_fight($petname){
	global $combatLog, $user, $petname;
	global $oppID, $oppName, $oppCurrHP, $oppMaxHP, $oppWeaponList, $oppAbilities, $oppPower, $oppStr, $oppDef;
	global $petCurrHP, $petMaxHP, $petPower, $petWeaponList, $petAbilities;
	global $petFrozen, $oppFrozen;
	
	$con = connect_to_server();
	
	$sql = "SELECT * FROM `active_battles` WHERE `pet` = '$petname'";
	$result = mysqli_query($con, $sql);
	
	if(mysqli_num_rows($result) === 0){
		initialize_opp($oppID, $user, $con);
		initialize_pet($petname, $con);
		$firstRound = true;
	}
	else{
		$firstRound = false;
		$data = mysqli_fetch_array($result);
		$oppID = $data['opponent'];
		$oppName = get_opp_name($oppID);
		$oppHP = $data['opp_hp'];
		$oppHP = explode("/", $oppHP);
		$oppCurrHP = $oppHP[0];
		$oppMaxHP = $oppHP[1];
		$oppPower = $data['opp_power'];
		$oppStr = $data['opp_str'];
		$oppDef = $data['opp_def'];
		$oppWeaponList = $data['opp_set'];
		$oppAbilities = $data['opp_abilities'];
		$petHP = $data['pet_hp'];
		$petHP = explode("/", $petHP);
		$petCurrHP = $petHP[0];
		$petMaxHP = $petHP[1];
		$petPower = $data['pet_power'];
		$petWeaponList = $data['pet_set'];
		$petAbilities = $data['pet_abilities'];
		$petFrozen = $data['pet_frozen'];
		$oppFrozen = $data['opp_frozen'];
		//$combatLog = $data['combat_log'];
	}
	
	return $firstRound;
}


//updates db with current state of battle
//called at initiation and then at the end of each round
function save_fight(){
	global $combatLog, $user, $petname;
	global $oppID, $oppName, $oppCurrHP, $oppMaxHP, $oppPower, $oppStr, $oppDef;
	global $petCurrHP, $petMaxHP, $petPower;
	global $petWeaponList, $petAbilities, $oppWeaponList, $oppAbilities;
	global $petFrozen, $oppFrozen;
	
	$dbCombatLog = preg_replace('/\'/', "\'", $combatLog);
	
	$con = connect_to_server();
	$petHP = $petCurrHP . "/" . $petMaxHP;
	$oppHP = $oppCurrHP . "/" . $oppMaxHP;
	
	$dbCols = array('pet', 'opponent', 'pet_hp', 'pet_power', 'opp_hp', 'opp_power', 'opp_str', 'opp_def', 'pet_set', 'pet_abilities', 'opp_set', 'opp_abilities', 'pet_frozen', 'opp_frozen');
	$vars = array($petname, $oppID, $petHP, $petPower, $oppHP, $oppPower, $oppStr, $oppDef, $petWeaponList, $petAbilities, $oppWeaponList, $oppAbilities, $petFrozen, $oppFrozen);
	
	$sql = "SELECT * FROM `active_battles` WHERE `pet` = '$petname'";
	$result = mysqli_query($con, $sql);
	
	if(!mysqli_num_rows($result)){ //new battle; create new row
		$sql = "INSERT INTO `active_battles` (pet, opponent, pet_hp, pet_power, opp_hp, opp_power, opp_str, opp_def, pet_set, pet_abilities, opp_set, opp_abilities, pet_frozen, opp_frozen)
		VALUES ('$petname', '$oppID', '$petHP', '$petPower', '$oppHP', '$oppPower', '$oppStr', '$oppDef', '$petWeaponList', '$petAbilities', '$oppWeaponList', '$oppAbilities', '$petFrozen', '$oppFrozen')";
		
		if(mysqli_query($con, $sql)){
		}
		else{
			echo "error: " . mysqli_error($con);
		}
	}
	else{ //update battle status
		$sql = "UPDATE `active_battles` SET "; //begin UPDATE satement
		
		for($i=0; $i<count($dbCols); $i++){ //loop through to add col[i] = var[i] to UPDATE statement
			$new = $dbCols[$i] . " = '" . $vars[$i] . "', ";
			$sql .= $new;
		}
		
		$sql = substr($sql, 0, -2); //remove floating comma
		$sql .= " WHERE `active_battles`.`pet` = '$petname'"; //finish UPDATE statment

		if(mysqli_query($con, $sql)){ //updated db row
		}
		else{
			echo "error: " . mysqli_error($con);
		}
	}
}


//records fight in user's records
//removes fight from active_battles
function end_fight(){
	global $petname, $oppID, $user, $petCurrHP, $petMaxHP, $oppCurrHP;
	
	$oppCol = "opp" . $oppID;
	
	$con = connect_to_server();
	
	$sql = "SELECT `$oppCol` FROM `playerdb` WHERE `user` = '$user'";
	$result = mysqli_query($con, $sql);
	
	$record = mysqli_fetch_array($result);
	$record = $record[0];
		
	if(!empty($record)){
		$record = explode(":", $record);
	}
	else{
		$record = "0:0:0:0:0:";
		$record = explode(":", $record);
	}
	
	if($petCurrHP == 0){
		if($oppCurrHP == 0){
			$record[2]++; //draw
		}
		else{
			$record[1]++; //loss
		}
	}
	else{ //win
		$record[0]++;
		
		//update score
		$diff = get_opp_difficulty($oppID);
		$points = ceil($diff + 100 * ($petCurrHP / $petMaxHP));
		$record[3] += $points;
	}
	
	$record = implode(":", $record);
	
	//update player win/loss record
	$sql = "UPDATE `playerdb` SET `$oppCol` = '$record' WHERE `user` = '$user'";
	if(mysqli_query($con, $sql)){
		//echo "updated record<br>";
	}
	else{
		//echo "failed to update record<br>";
	}
	
	//remove battle from active_battles
	$sql = "DELETE FROM `active_battles` WHERE `pet` = '$petname'";
	//echo $sql . "<br>";
	if(mysqli_query($con, $sql)){
		//echo "removed from active battles<br>";
	}
	else{
		//echo "failed to remove from active battles<br>";
	}
}


//returns base difficulty of oppID
function get_opp_difficulty($oppID){
	$con = connect_to_server();
	
	$sql = "SELECT `difficulty` FROM `challengerdb` WHERE `id` = '$oppID'";
	
	$result = mysqli_query($con, $sql);
	$diff = mysqli_fetch_array($result);
	$diff = $diff['difficulty'];
	
	mysqli_free_result($result);
	
	return $diff;
}


//uses array of icons to calculate damage dealt
//returns final damage number
function calc_dmg($dmgArray, $str1, $str2, $stance1, $stance2, $move){
	global $petname;
	
	//array to hold weakness/resistance multipliers
	$speciesResist = get_species_resistance($petname);
	$r = 1; //default weak/res multiplier
	$resistanceOutput = array();
	$resistanceCBLog = array();
	
	$sum1 = 0;
	$sum2 = 0;
	$sum3 = 0;
	$sum4 = 0;
	$sum5 = 0;
	
	if($move == 'second'){
		foreach($dmgArray[0] as $icon=>$amt){ //normal icons
			$sum1 += $amt * $str1 * $stance1 * $stance2 * $r;
		}
		foreach($dmgArray[2] as $icon=>$amt){ //reflected icons
			$sum2 += $amt * $str2 * $stance2 * $stance2;
		}
		//echo "ref dmg, move 2nd: " . $sum2 . "<br>";
		
		foreach($dmgArray[3] as $icon=>$amt){ //pseudoreflected icons
			$sum3 += $amt * $str2 * $stance2;
		}
	}
	
	if($move == 'first'){
		foreach($dmgArray[0] as $icon=>$amt){ //normal icons
			$r = 1;
			if(!empty($speciesResist) && $speciesResist[0][$icon] != NULL){
				$r = $speciesResist[0][$icon];
			}
			
			$iSum1 = $amt * $str1 * $stance1 * $stance2 * $r; //dmg subtotal
			
			if($iSum1 != 0 && $r < 1){ //record icon types if resist/weakness present
					$resistanceOutput[0][] = $icon;
				}
			elseif($iSum1 != 0 && $r > 1){
					$resistanceOutput[1][] = $icon;
				}
				
			$sum1 += $iSum1; //add dmg to final sum for this section
		}
		
		foreach($dmgArray[2] as $icon=>$amt){ //reflected icons
			$r = 1;
			if(!empty($speciesResist) && $speciesResist[0][$icon] != NULL){
				$r = $speciesResist[0][$icon];
			}
			
			$iSum2 = $amt * $str2 * $stance2 * $r;
			
			if($iSum2 != 0 && $r < 1){
					$resistanceOutput[0][] = $icon;
				}
			elseif($iSum2 != 0 && $r > 1){
					$resistanceOutput[1][] = $icon;
				}
				
			$sum2 += $iSum2;
		}
		
		foreach($dmgArray[3] as $icon=>$amt){ //pseudoreflected icons
			$r = 1;
			if(!empty($speciesResist) && $speciesResist[0][$icon] != NULL){
				$r = $speciesResist[0][$icon];
			}
			
			$iSum3 = $amt * $str1 * $r;
			
			if($iSum3 != 0 && $r < 1){
					$resistanceOutput[0][] = $icon;
				}
			elseif($iSum3 != 0 && $r > 1){
					$resistanceOutput[1][] = $icon;
				}
			
			$sum3 += $iSum3;
		}
		
		foreach($dmgArray[4] as $icon=>$amt){ //double-reflected icons
			$r = 1;
			if(!empty($speciesResist) && $speciesResist[0][$icon] != NULL){
				$r = $speciesResist[0][$icon];
			}
			
			$iSum4 = $amt * $str1 * $stance1 * $r;
			
			if($iSum4 != 0 && $r < 1){
					$resistanceOutput[0][] = $icon;
				}
			elseif($iSum4 != 0 && $r > 1){
					$resistanceOutput[1][] = $icon;
				}
			
			$sum4 += $iSum4;
		}
		
		foreach($dmgArray[5] as $icon=>$amt){ //reflected pseudo-reflected icons
			$r = 1;
			if(!empty($speciesResist) && $speciesResist[0][$icon] != NULL){
				$r = $speciesResist[0][$icon];
			}
			
			$iSum5 = $amt * $str1 * $r;
			
			if($iSum5 != 0 && $r < 1){
					$resistanceOutput[0][] = $icon;
				}
			elseif($iSum5 != 0 && $r > 1){
					$resistanceOutput[1][] = $icon;
				}
			
			$sum5 += $iSum5;
		}
		
		//remove duplicate entries for icon resist/weakness arrays
		if(isset($resistanceOutput[0])){
			$resistanceCBLog[] = array_unique($resistanceOutput[0]);
		}
		if(isset($resistanceOutput[1])){
			$resistanceCBLog[] = array_unique($resistanceOutput[1]);
		}
	}
	
	$dmg = ceil($sum1 + $sum2 + $sum3 + $sum4 + $sum5);
	$return[] = $dmg;
	$return[] = $resistanceCBLog;
	
	return $return;
}

//returns pet's str boost multiplier
function get_pet_str_mult($petname){
	$con = connect_to_server();
	
	$sql = "SELECT `str` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	
	$str = mysqli_fetch_array($result);
	$str = $str['str'];
	
	if($str < 8){
		$m = 0.5;
	}
	elseif($str < 13){
		$m = 0.75;
	}
	elseif($str < 20){
		$m = 1;
	}
	elseif($str < 35){
		$m = 1.25;
	}
	elseif($str < 55){
		$m = 1.5;
	}
	elseif($str < 85){
		$m = 2;
	}
	elseif($str < 125){
		$m = 2.5;
	}
	elseif($str < 200){
		$m = 3;
	}
	elseif($str < 250){
		$m = 4.5;
	}
	elseif($str < 300){
		$m = 5.5;
	}
	elseif($str < 350){
		$m = 6.5;
	}
	elseif($str < 400){
		$m = 7.5;
	}
	elseif($str < 450){
		$m = 8.5;
	}
	elseif($str < 500){
		$m = 9.75;
	}
	elseif($str < 550){
		$m = 11;
	}
	elseif($str < 600){
		$m = 12;
	}
	elseif($str < 650){
		$m = 13;
	}
	elseif($str < 700){
		$m = 14;
	}
	elseif($str < 750){
		$m = 15;
	}
	else{
		$m = 15;
	}
	
	return $m;
}

//returns pet's def boost multiplier
function get_pet_def_mult($petname){
	$con = connect_to_server();
	
	$sql = "SELECT `def` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	
	$def = mysqli_fetch_array($result);
	$def = $def['def'];
	
	if($def < 8){
		$m = 0.5;
	}
	elseif($def < 13){
		$m = 0.75;
	}
	elseif($def < 20){
		$m = 1;
	}
	elseif($def < 35){
		$m = 1.25;
	}
	elseif($def < 55){
		$m = 1.5;
	}
	elseif($def < 85){
		$m = 2;
	}
	elseif($def < 125){
		$m = 2.5;
	}
	elseif($def < 200){
		$m = 3;
	}
	elseif($def < 250){
		$m = 4.5;
	}
	elseif($def < 300){
		$m = 5.5;
	}
	elseif($def < 350){
		$m = 6.5;
	}
	elseif($def < 400){
		$m = 7.5;
	}
	elseif($def < 450){
		$m = 8.5;
	}
	elseif($def < 500){
		$m = 9.75;
	}
	elseif($def < 550){
		$m = 11;
	}
	elseif($def < 600){
		$m = 12;
	}
	elseif($def < 650){
		$m = 13;
	}
	elseif($def < 700){
		$m = 14;
	}
	elseif($def < 750){
		$m = 15;
	}
	else{
		$m = 15;
	}
	
	return $m;
}



//returns opp str multiplier
function get_opp_str_mult($oppStr){
	$str = $oppStr;
	
	if($str < 8){
		$m = 0.5;
	}
	elseif($str < 13){
		$m = 0.75;
	}
	elseif($str < 20){
		$m = 1;
	}
	elseif($str < 35){
		$m = 1.25;
	}
	elseif($str < 55){
		$m = 1.5;
	}
	elseif($str < 85){
		$m = 2;
	}
	elseif($str < 125){
		$m = 2.5;
	}
	elseif($str < 200){
		$m = 3;
	}
	elseif($str < 250){
		$m = 4.5;
	}
	elseif($str < 300){
		$m = 5.5;
	}
	elseif($str < 350){
		$m = 6.5;
	}
	elseif($str < 400){
		$m = 7.5;
	}
	elseif($str < 450){
		$m = 8.5;
	}
	elseif($str < 500){
		$m = 9.75;
	}
	elseif($str < 550){
		$m = 11;
	}
	elseif($str < 600){
		$m = 12;
	}
	elseif($str < 650){
		$m = 13;
	}
	elseif($str < 700){
		$m = 14;
	}
	elseif($str < 750){
		$m = 15;
	}
	else{
		$m = 15;
	}
	
	return $m;
}


//returns opp def multiplier
function get_opp_def_mult($oppDef){
	$def = $oppDef;
	
	if($def < 8){
		$m = 0.5;
	}
	elseif($def < 13){
		$m = 0.75;
	}
	elseif($def < 20){
		$m = 1;
	}
	elseif($def < 35){
		$m = 1.25;
	}
	elseif($def < 55){
		$m = 1.5;
	}
	elseif($def < 85){
		$m = 2;
	}
	elseif($def < 125){
		$m = 2.5;
	}
	elseif($def < 200){
		$m = 3;
	}
	elseif($def < 250){
		$m = 4.5;
	}
	elseif($def < 300){
		$m = 5.5;
	}
	elseif($def < 350){
		$m = 6.5;
	}
	elseif($def < 400){
		$m = 7.5;
	}
	elseif($def < 450){
		$m = 8.5;
	}
	elseif($def < 500){
		$m = 9.75;
	}
	elseif($def < 550){
		$m = 11;
	}
	elseif($def < 600){
		$m = 12;
	}
	elseif($def < 650){
		$m = 13;
	}
	elseif($def < 700){
		$m = 14;
	}
	elseif($def < 750){
		$m = 15;
	}
	else{
		$m = 15;
	}
	
	return $m;
}


//returns stance multiplier
function get_stance_multiplier($a){
	$M = 1;
	
	if($a == 5){
		$M = 1.5;
	}
	elseif($a == 4){
		$M = 1.4;
	}
	elseif($a == 3){
		$M = 1.3;
	}
	elseif($a == 2){
		$M = 1.2;
	}
	elseif($a == 1){
		$M = 1.0;
	}
	elseif($a == -1){
		$M = 0.8;
	}
	elseif($a == -2){
		$M = 0.7;
	}
	else{
		$M = 1;
	}
	
	return $M;
}


//checks for fragile items ($i > 0)
function remove_fragile_items($w1, $w2, $a, $player){
	$con = connect_to_server();
	
	$weapons = array($w1, $w2);
	foreach($weapons as $w){
		if($w){
			$tbName='weapons';
			$sql = "SELECT `useage` FROM `weapons` WHERE `id` = '$w'";
			$result = mysqli_query($con, $sql);
			$useage = mysqli_fetch_array($result);
			$useage = $useage['useage'];
			
			$check = substr($useage, 0, 1);
		if($check == 1 || $check == 3){
				$info = explode(",", $useage);
				$useage = $info[0];
				$p = $info[1]; //chance of breaking
			}
			
			if($useage > 0){
				if($useage == 1){
					if(roll_for_breakage($p)){
						remove_from_copy($w, $player, $tbName);
					}
				}
				elseif($useage == 2){
					remove_from_copy($w, $player, $tbName);
				}
				elseif($useage == 3){
					if(roll_for_breakage($p)){
						remove_from_copy($w, $player, $tbName);
						//remove_from_main($w, $player, $tbName); //permanent breakage disabled for sandbox mode
					}
				}
				elseif($useage == 4){
					remove_from_copy($w, $player, $tbName);
					//remove_from_main($w, $player, $tbName);
				}
			}
		}
	}
	
	//ability
	if($a){
		$tbName='abilities';
		$sql = "SELECT `useage` FROM `abilities` WHERE `id` = '$a'";
		$result = mysqli_query($con, $sql);
		$useage = mysqli_fetch_array($result);
		$useage = $useage['useage'];
		$check = substr($useage, 9, 1);
		
		if($check == 1 || $check == 3){
			$info = explode(",", $useage);
			$useage = $info[0];
			$p = $info[1]; //chance of breaking
		}
		
		if($useage > 0){
				if($useage == 1){
					if(roll_for_breakage($p)){
						remove_from_copy($a, $player, $tbName);
					}
				}
				elseif($useage == 2){
					remove_from_copy($a, $player, $tbName);
				}
				elseif($useage == 3){
					if(roll_for_breakage($p)){
						remove_from_copy($a, $player, $tbName);
						//remove_from_main($a, $player, $tbName);
					}
				}
				elseif($useage == 4){
					remove_from_copy($a, $player, $tbName);
					//remove_from_main($a, $player, $tbName);
				}
			}
	}
}


//rolls for successful steal
//modifies weapon lists
//TODO: stolen item gets added to front/back of weapon list based on move order
//	not really relavent for 1P
function steal_weapon($w, $stealer='pet'){
	global $petWeaponList, $oppWeaponList;
	$con = connect_to_server();
	if($stealer == 'pet'){
		$stealFrom = $oppWeaponList;
		$addTo = $petWeaponList;
		$side = "R";
	}
	elseif($stealer == 'opp'){
		$stealFrom = $petWeaponList;
		$addTo = $oppWeaponList;
		$side = "L";
	}
	
	$sql = "SELECT * FROM `weapons` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$pSteal = $result['steal'];
	$useage = $result['useage'];
	
	//roll for steal success
	$p = ($pSteal - 1000) / 100;
	$steal = mt_rand() / mt_getrandmax();
	if($steal < $p){ //success; move stolen item  over
		$stealFrom = explode(":", $stealFrom);
		$addTo = explode(":", $addTo);
		
		
		if(!empty($stealFrom[0])){
			$stolenW = $stealFrom[0];
			array_splice($stealFrom, 0, 1);
			$addTo[] = $stolenW;
		}
		
		//remove stealer if breaks-on-success (1)
		if($useage == '1'){
			$i = array_search($w, $addTo);
			array_splice($addTo, $i, 1);
		}
		
		//put everything back into weap arrays
		$stealFrom = implode(":", $stealFrom);
		$addTo = implode(":", $addTo);
		
		if($stealer == 'pet'){
			$oppWeaponList = $stealFrom;
			$petWeaponList = $addTo;
		}
		elseif($stealer == 'opp'){
			$petWeaponList = $stealFrom;
			$oppWeaponList = $addTo;
		}
	}
	
	//return $cbLog;
}


//adds generated item to weapon list
function generate_item($w, $tbName){
	global $petWeaponList, $oppWeaponList;
	
	$con = connect_to_server();
	$sql = "SELECT * FROM `$tbName` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$genItem = $result['item_spawn'];
	$itemCount = 1;
	$newItem = $genItem;
	$useage = $result['useage'];
	
	//number of items to spawn
	if(strpos($genItem, ',') !== false){
		$genItem = explode(",", $genItem);
		$newItem = $genItem[0];
		$itemCount = $genItem[1];
	}
	
	//add items to set
	for($i=0;$i<$itemCount;$i++){
		$petWeaponList .= ":" . $newItem;
	}
}



//for once-per-battle and semi-fragile items
//removes item from active_battle array
function remove_from_copy($w, $player, $tbName){
	global $petWeaponList, $oppWeaponList;
	global $petAbilities, $oppAbilities;
	
	$wArray = $oppWeaponList;
	
	if($tbName == 'weapons'){
		if($player == 'p1'){
			$wArray = $oppWeaponList;
		}
		elseif($player == 'p2'){
			$wArray = $petWeaponList;
		}
	}
	elseif($tbName == 'abilities'){
		if($player == 'p1'){
			$wArray = $oppAbilities;
		}
		elseif($player == 'p2'){
			$wArray = $petAbilities;
		}
	}
	
	//delete item/ability
	$wArray = explode(":", $wArray);
	if(in_array($w, $wArray)){
		$i = array_search($w, $wArray);
		array_splice($wArray, $i, 1);
	}
	
	//update weapon/ability array
	$wArray = implode(":", $wArray);
	if($tbName == 'weapons'){
		if($player == 'p1'){
			$oppWeaponList = $wArray;
		}
		elseif($player == 'p2'){
			$petWeaponList = $wArray;
		}
	}
	elseif($tbName == 'abilities'){
		//print_r($wArray);
		if($player == 'p1'){
			$oppAbilities = $wArray;
		}
		elseif($player == 'p2'){
			$petAbilities = $wArray;
		}
	}
}


//for fragile/single-use items
//removes item from main set
//only used for pets (disable for sandbox mode)
function remove_from_main($w, $player){
	global $petname;
	$con = connect_to_server();
	$sql = "SELECT `weapons` FROM `petdb` WHERE `name` = '$petname'";
	
	if($player == 'p2'){
		$con = connect_to_server();
		$sql = "SELECT `weapons` FROM `petdb` WHERE `name` = '$petname'";
		$result = mysqli_query($con, $sql);
		$result = mysqli_fetch_array($result);
		$wArray = $result['weapons'];
		$wArray = explode(":", $wArray);
		
		if(in_array($w, $wArray)){
			$i = array_search($w, $wArray);
			array_splice($wArray, $i, 1);
			
			$updatedWeaponList = implode(":", $wArray);			
			$sql = "UPDATE `petdb` SET weapons = '$updatedWeaponList' WHERE `petdb`.`name` = '$petname'";
			mysqli_query($con, $sql);
		}
	}
}


//removes abilities for rest of battle
function remove_ability($a, $player){
	global $petAbilities, $oppAbilities;
	
	$aArray = $oppAbilities;
	if($player == 'p2'){
		$aArray = $petAbilities;
	}
	
	$aArray = explode(":", $aArray);
	if(in_array($a, $aArray)){
		$i = array_search($a, $aArray);
		array_splice($aArray, $i, 1);
	}
	
	$aArray = implode(":", $aArray);
	if($player == 'p2'){
		$petAbilities = $aArray;
	}
	else{
		$oppAbilities = $aArray;
	}
}


//rolls for breakage with chance p
//returns true if item broke
function roll_for_breakage($p){
	$break = false;
	
	$roll = mt_rand() / mt_getrandmax();
	if($roll < $p){
		$break = true;
	}
	
	return $break;
}

//picks proper distribution
//rolls for icons
//returns # of icons rolled
function roll_for_icons($min, $max, $inc, $avg, $sd){
	$result = 0;
	
	if($avg == -1){ //equal distribution
		$roll = $min + mt_rand() / mt_getrandmax() * ($max - $min);
		$result = round($roll / $inc) * $inc;		
	}
	else{ //normal distribution
		$roll1 = mt_rand() / mt_getrandmax();
		$roll2 = mt_rand() / mt_getrandmax();
		$gaussian = sqrt(-2 * log($roll1)) * cos(2 * M_PI * $roll2);
		$result = $gaussian * $sd + $avg;
		if($inc <= 1){
			$result = round($result / $inc) * $inc;
		}
		else{
			$offset = $min % $inc;
			$result = floor($result / $inc) * $inc + $offset;
		}
	}
	
	//reroll until result falls within [min, max]
	if($result < $min || $result > $max){
		$result = roll_for_icons($min, $max, $inc, $avg, $sd);
	}

	return $result;
}


//rolls for icon type
//returns name of icon rolled
function roll_for_icon_type(){
	$icons = array('fire', 'water', 'air', 'earth', 'light', 'dark', 'phys');
	$i = mt_rand(0, 6);
	return $icons[$i];
}


//rolls for pick_one effect
//returns id for resulting weapons
function get_pick_one_new_id($w){
	$con = connect_to_server();
	$sql = "SELECT `pick_one` FROM `weapons` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$pick_one = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$pick_one = $pick_one['pick_one'];
	
	$idArray = array();
	$pTotal = 0;
	$pArray = explode(":", $pick_one);
	foreach($pArray as $p){
		$pid = explode(",", $p);
		$pTotal += $pid[1];
		$idArray[$pid[0]] = $pTotal;
	}
	
	arsort($idArray);
	
	$newID = $w;
	$rand = mt_rand() / mt_getrandmax();
	foreach($idArray as $id => $p){
		if($rand < $p){
			$newID = $id;
		}
	}
	
	return $newID;
}


//NOTE: this is only used to check 1p opponent's weapons now (I think)
//checks that weapons being submitted are part of pet's set
function check_weapon_submission($player, $w){
	global $petname;
	global $petWeaponList, $oppWeaponList;
	
	$check = '';
	$checkSpecies = true;

	if($player == 'p1'){
		$weapList = $oppWeaponList;
	}
	elseif($player == 'p2'){
		$weapList = $petWeaponList;
		$checkSpecies = check_species_weapon($w); //this will always return true in sandbox mode
	}
	
	$weapList = explode(":", $weapList);
	
	//verify that pet actually has the weapon equipped
	if(in_array($w, $weapList) && $checkSpecies){
		$check = $w;
	}
	
	return $check;
}


//for user-submitted weapons only
//checks that weapons being submitted are part of pet's set
//returns array of weap ids or empty value if weap was rejected
function check_weapon_submissions($player, $w1, $w2){
	global $petname;
	global $petWeaponList, $oppWeaponList;
	
	$check = array();
	$weapList = ($player == 'p1' ? $oppWeaponList : $petWeaponList);
	
	$weapList = explode(":", $weapList);
	$weapsSubmitted = array($w1, $w2);
	
	foreach($weapsSubmitted as $w){
		if(in_array($w, $weapList) && check_species_weapon($w)){ //species check will always return true in sandbox mode
			$check[] = $w;
		}
		else{
			$check[] = "";
		}
	}
	
	//if user doubles up on a weapon, make sure they actually have 2 of them
	if($check[0] == $check[1]){
		$count = array_count_values($weapList);
		if($count[$check[0]] < 2){
			$check[1] = "";
		}
	}
	
	return $check;
}

//checks that species attack matches pet species
//returns $a if match; returns empty if not
function check_species_attack_submission($a, $petname){
	$con = connect_to_server();
	
	$sql = "SELECT `species` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$petSpecies = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$petSpecies = $petSpecies['species'];
	
	$sql = "SELECT `species` FROM `species_attacks` WHERE `id` = '$a'";
	$result = mysqli_query($con, $sql);
	$aSpecies = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$aSpecies = $aSpecies['species'];
	
	if($petSpecies != $aSpecies){
		$a = "";
	}
	
	return $a;
}


//NOTE: sandbox mode allows any pet to use any weapon
//checks that pet species matches species weap
//returns true/false
function check_species_weapon($w){
	global $petname;
	
	$con = connect_to_server();
	$sql = "SELECT `species` from `weapons` WHERE `id` = '$w'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$weapSpecies = $result['species'];
	
	
	$sql = "SELECT * FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$petSpecies = $result['species'];
	
	//1P weaps (like chiazilla shield) are coded as special "species" weaps in the db
	if($weapSpecies == NULL || $weapSpecies == '1-Player' || $weapSpecies == $petSpecies){
		$check = true;
	}
	else{
		$check = false;
	}
	
	$check = true; //CHECK DISABLED FOR SANDBOX MODE
	return $check;
}


//checks that abilities being submitted are part of ab set
function check_ability_submission($player, $a){
	global $petAbilities, $oppAbilities;
	
	$check = '';

	if($player == 'p1'){
		$aList = $oppAbilities;
	}
	elseif($player == 'p2'){
		$aList = $petAbilities;
	}
	
	$aList = explode(":", $aList);
	if(in_array($a, $aList)){
		$check = $a;
	}
	
	return $check;
}


//translates power setting to number
//checks that pet doesn't use more power than it has
function get_power_number($a, $petPower, $p){
	$p = strtolower($p);
	$num = 0;
	
	if($a > 99 && $a < 1000){ //id of [100 - 999] is a faerie ability
		if($p == 'strong'){
			$num = 39;
		}
		elseif($p == 'medium'){
			$num = 20;
		}
		elseif($p == 'weak'){
			$num = 10;
		}
	}
	
	//check that pet actually has enough power
	//else use whatever power is left
	if($num > $petPower){
		$num = $petPower;
	}
	
	return $num;
}


//checks power req for full ability effect
//returns scaling multiplier
function scale_to_power($p, $req){
	$scale = 1;
	
	if($req > 0){
		$scale = $p / $req;
	}
	
	return $scale;
}


//returns true if player is frozen; false otherwise
//2 = frozen; 1 = was frozen; 0 = default
function is_frozen($petname, $prefix){
	$con = connect_to_server();
	$colName = $prefix . "_frozen";
	$froz = false;
	
	$sql = "SELECT `$colName` from `active_battles` WHERE `pet` = '$petname'";
	$result = mysqli_query($con, $sql);
	$frozen = mysqli_fetch_array($result);
	$frozen = $frozen[$colName];

	if($frozen > 0){
		$froz = true;
	}
	
	return $froz;
}


//outputs snowflake img for frozen player
function display_frozen_img($petname, $prefix){
	$output = '';
	
	if(is_frozen($petname, $prefix)){
		$output = '<img src="img/frozen.png"><br>';
	}
	
	return $output;
}


//returns assoc. array of ([0]icon=>mult...[7]resistance for cblog, [8]weakness for cblog)
//NOTE: I think the cb log outputs got moved elsewhere
function get_species_resistance($petname){
	$resistanceOutput = '';
	$weaknessOutput = '';
	
	$con = connect_to_server();
	$sql = "SELECT `species` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$species = $result['species'];
	
	$sql = "SELECT * FROM `species_resist` WHERE `species` = '$species'";
	$result = mysqli_query($con, $sql);
	$multipliers = array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$multipliers[] = $row;
	}
		
	return $multipliers;
}

//returns pet's species
function get_pet_species($petname){
	$con = connect_to_server();
	$sql = "SELECT `species` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$species = $result['species'];
	
	return $species;
}

//returns paint brush color of pet
function get_pet_colour($petname){
	$con = connect_to_server();
	$sql = "SELECT `colour` FROM `petdb` WHERE `name` = '$petname'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$colour = $result['colour'];
	
	return $colour;
}





/*****SELECT.PHP*****/

//deletes battle from active_battles
function withdraw_from_fight($user){
	$return = "";
	$con = connect_to_server();
	$sql = "SELECT `pets` FROM `playerdb` WHERE `user` = '$user'";
	$result = mysqli_query($con, $sql);
	$petlist = mysqli_fetch_array($result, MYSQLI_ASSOC);
	//print_r($petlist);
	//$petlist = $petlist['pets'];
	
	foreach($petlist as $name){
		$sql = "DELETE FROM `active_battles` WHERE `pet` = '$name'";
		mysqli_query($con, $sql);
		$return .= $name . " withdrawn from battle.<br>";
	}
	
	return $return;
}

/*****EQUIPMENT.PHP*****/

//find's pet belonging to user
//returns array containing pet's data
function get_pet_data($user){
	$con = connect_to_server();				
	$sql = "SELECT * FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$petData = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	return $petData;
}

//gets all species names from db
//outputs dropdown menu
//default selected is current species
function output_species_list($petSpecies){
	$con = connect_to_server();
	$sql = "SELECT `species` FROM `species_attacks`";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)){
		$speciesList[] = $row['species'];
	}
	$speciesList = array_unique($speciesList);
	sort($speciesList);
	
	foreach($speciesList as $species){
		echo "<option value=\"{$species}\"";
		if($species == $petSpecies){
			echo " selected";
		}
		echo ">{$species}</option>";
	}
}

//gets all paint brush colors from txt file
//outputs dropdown menu of colors
function output_petcolours_list($petColour){
	$file = "petcolours.txt";
	$list = file_get_contents($file);
	$list = explode("\n", $list);
	
	foreach($list as $colour){
		echo "<option value=\"{$colour}\"";
		if($colour == $petColour){
			echo " selected";
		}
		echo ">{$colour}</option>";
	}
}


//finds url for color/species combo
//outputs url
function output_pet_image($species, $colour){
	$con = connect_to_server();
	$url1 = "http://images.neopets.com/pets/rangedattack/{$species}_{$colour}_right.gif";
	$url2 = "http://images.neopets.com/images/nf/{$species}_{$colour}_happy.png";
	$url3 = "http://images.neopets.com/homepage/marquee/{$colour}_{$species}.png";
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
	
	if($result !== false){ //use URL1
		$imgURL = $url1;
	}
	else{ //check URL2
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url2);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$result2 = curl_exec($ch);
		curl_close($ch);
		
		if($result2 !== false){ //use URL2
			$imgURL = $url2;
		}
		else{ //default to URL3
			$imgURL = $url3;
		}
	}
	
	echo $imgURL;
}


//gets stats entered by user
//saves new stats to petdb
//returns msg
function update_pet_stats($user){
	$con = connect_to_server();
	$vars = array('hp' => 25000, 'str' => 700, 'def' => 700, 'lvl' => 50, 'intel' => 1000, 'agil' => 201);
	
	$sql = "SELECT `species` FROM `species_attacks`";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)){
		$speciesList[] = $row['species'];
	}
	
	//get list of colours
	$file = "petcolours.txt";
	$colourList = file_get_contents($file);
	$colourList = explode("\n", $colourList);
	
	foreach($vars as $v => $max){
		if(!is_numeric($_GET[$v]) || ($_GET[$v] < 1 || $_GET[$v] > $max)){
			$_GET[$v] = 1;
		}
	}
	
	$hp = $_GET['hp'];
	$str = $_GET['str'];
	$def = $_GET['def'];
	$lvl = $_GET['lvl'];
	$intel = $_GET['intel'];
	$agil = $_GET['agil'];
	$species = (in_array($_GET['species'], $speciesList) ? $_GET['species'] : 'acara');
	$colour = (in_array($_GET['colour'], $colourList) ? $_GET['colour'] : 'blue');
	
	$sql = "UPDATE `petdb` 
	SET `hp` = '$hp', `str` = '$str', `def` = '$def', `level` = '$lvl', `intel` = '$intel', `spd` = '$agil', `species` = '$species', `colour` = '$colour' 
	WHERE `owner` = '$user'";
	
	if(mysqli_query($con, $sql)){
		$msg = "Stats updated successfully!";
	}
	else{
		$msg = mysqli_error($con);
	}
	
	return $msg;
}


//checks that new name is available; updates petname in petdb+userdb for both sandbox+1p tables
//returns msg with success/failure
//TODO: update or clear any active battles associated with old name
function update_pet_name($user){
	$con = connect_to_server();
	$sql = "SELECT `name` FROM `petdb`";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result)){
		$nameList[] = $row['name'];
	}
	
	$newName = trim(mysqli_real_escape_string($con, $_GET['newname']));
	$newName = preg_replace('/[^0-9a-zA-Z_]/',"",$newName);
	if(strlen($newName) > 16){
		$newName = subStr($newName, 0, 16);
	}
	
	if(in_array($newName, $nameList)){
		$msg = "Error: <b>" . $newName . "</b> is already taken.";
	}
	else{
		//update petname in petdb
		$sql = "UPDATE `petdb` SET `name` = '$newName' WHERE `owner` = '$user'";
		if(mysqli_query($con, $sql)){
			$msg = "Petname changed to <b>" . $newName . "</b>";
		}
		else{
			$msg = mysqli_error($con);
		}
		
		//update petname in playerdb
		$sql = "UPDATE `playerdb` SET `pets` = '$newName' WHERE `user` = '$user'";
		if(!mysqli_query($con, $sql)){
			$msg = mysqli_error($con);
		}
		
		//update petname in 1ppetdb
		$sql = "UPDATE `1ppetdb` SET `name` = '$newName' WHERE `owner` = '$user'";
		if(mysqli_query($con, $sql)){
			$msg = "Petname changed to <b>" . $newName . "</b>";
		}
		else{
			$msg = mysqli_error($con);
		}
		
		//update petname in 1pplayerdb
		$sql = "UPDATE `1pplayerdb` SET `pets` = '$newName' WHERE `user` = '$user'";
		if(!mysqli_query($con, $sql)){
			$msg = mysqli_error($con);
		}
	}
	
	return $msg;
}


//gets current equip list
//displays weapons for unequipping
function display_equipment($user){
	$con = connect_to_server();
	$sql = "SELECT `weapons` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$weapList = mysqli_fetch_array($result, MYSQLI_ASSOC);

	$weapList = explode(":", $weapList['weapons']);
	//print_r($weapList);
	$count = count($weapList) / 2;
	$n = 0;
	
	for($i=0; $i<$count; $i++){
		echo "<tr style=\"text-align:center;\">";
		for($r=0; $r<2; $r++){
			if(isset($weapList[$n])){
				$weapID = $weapList[$n];
				$weapImg = get_weapon_img($weapList[$n]);
				$weapName = get_weapon_name($weapList[$n]);
				echo "<td width=\"50%\"><a href=\"?unequip={$weapID}\"><img src=\"{$weapImg}\"><br>{$weapName}</a></td>";
				$n++;
			}
		}
		echo "</tr>";
	}
}


//counts number of items equipped
function equip_count($user){
	$con = connect_to_server();
	$sql = "SELECT `weapons` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$weaps = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	$count = 0;
	if(!empty($weaps['weapons'])){
		$weaps = explode(":", $weaps['weapons']);
		$count = count($weaps);
	}
	echo "(" . $count . "/8)";
}


//unequips item
//updates petdb with new weapon list
function unequip_item($w, $user){
	$con = connect_to_server();
	$sql = "SELECT `weapons` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$wArray = $result['weapons'];
	$wArray = explode(":", $wArray);
	
	if(in_array($w, $wArray)){
		$i = array_search($w, $wArray);
		array_splice($wArray, $i, 1);
		
		$updatedWeaponList = implode(":", $wArray);
		//echo "<br>updated weapon list: " . $updatedWeaponList . "<br>";
		
		$sql = "UPDATE `petdb` SET weapons = '$updatedWeaponList' WHERE `owner` = '$user'";
		mysqli_query($con, $sql);
	}
}


//adds item to pet's equipment list
//updates petdb
function add_equipment($w, $user){
	$msg = "";
	$tbName = 'weapons';
	$con = connect_to_server();
	$sql = "SELECT `weapons` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$equipList = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	if(!empty($equipList['weapons'])){
		$equipList = explode(":", $equipList['weapons']);
	}
	else{
		$equipList = array();
	}
	
	if(is_numeric($w)){
		$count = count($equipList);
		if($count < EQUIP_LIMIT){ //limit to 8 weaps
			//DISABLED FOR SANDBOX MODE
			/* foreach($equipList as $weap){ //checks for weaps that cannot be equipped together (2 healers, 2 freezers, etc)
				if((has_limit_one($weap) && $weap == $w) || (has_limit_one($weap) && can_freeze($weap, $tbName) && has_limit_one($w) && can_freeze($w, $tbName)) || (has_limit_one($weap) && has_heal($weap, $tbName) && has_limit_one($w) && has_heal($w, $tbName)) || (has_limit_one($weap) && has_steal($weap, $tbName) && has_limit_one($w) && has_steal($w, $tbName))){
					$msg = "Error: You cannot equip more than one of that type of weapon.<br>You will need to unequip the following weapon(s) first: <br><b>" . get_weapon_name($weap) . "</b>";					
				}
			} */
		}
		else{
			$msg = "Error: Your pet already has the maximum 8 items equiped.";
		}
		
		if(empty($msg)){
			$equipList[] = $w;
		}
	}
	
	$newList = implode(":", $equipList);
	$sql = "UPDATE `petdb` SET `weapons` = '$newList' WHERE `owner` = '$user'";
	if(!mysqli_query($con, $sql)){
		$msg = "Error: could  not equip weapon.";
	}
	
	return $msg;
}


//checks if weapon has limit-one restriction
//returns true if restricted, false otherwise
function has_limit_one($id){
	$check = array();
	$con = connect_to_server();
	$sql = "SELECT `equip_one` FROM `weapons` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$check = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	//TODO: figure out why this isn't working
	if(!$check['equip_one']){
		$limitOne = false;
	}
	else{
		$limitOne = true;
	}
	
	return $limitOne;
}


//gets equipment list from db
//fills dropdown menu with equipment
function output_equip_menu(){
	$con = connect_to_server();
	
	$sql = "SELECT `id`, `name`, `species`, `tier` FROM `weapons`";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$data[$row['id']] = array('name' => $row['name'], 'species' => $row['species'], 'tier' => $row['tier']);
	}
	asort($data);
	foreach($data as $id => $info){
		if($info['species'] != "X" && $info['tier'] < 9){
			echo "<option value=\"{$id}\">{$info['name']}</option>";
		}
	}
}


//gets current ability list
//displays abilities for unequipping
function display_active_abilities($user){
	$con = connect_to_server();
	$sql = "SELECT `act_abil` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$abList = mysqli_fetch_array($result, MYSQLI_ASSOC);

	$abList = explode(":", $abList['act_abil']);
	//print_r($abList);
	$count = count($abList) / 2;
	$n = 0;
	
	for($i=0; $i<$count; $i++){
		echo "<tr style=\"text-align:center;\">";
		for($r=0; $r<2; $r++){
			if(isset($abList[$n])){
				$abID = $abList[$n];
				$abImg = get_ability_img($abList[$n]);
				$abName = get_ability_name($abList[$n]);
				echo "<td width=\"50%\"><a href=\"?deactivate={$abID}\"><img src=\"{$abImg}\"><br>{$abName}</a></td>";
				$n++;
			}
		}
		echo "</tr>";
	}
}


//gets abilities list from db
//fills dropdown menu with abilities
function output_abilities_menu(){
	$con = connect_to_server();
	
	$sql = "SELECT `id`, `name` FROM `abilities`";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$data[$row['id']] = array('name' => $row['name']);
	}
	asort($data);
	foreach($data as $id => $name){
		if($id < 111 || $id > 122){
			echo "<option value=\"{$id}\">{$name['name']}</option>";
		}
	}
}


//adds ability to pet's active abilities list
//updates petdb
function activate_ability($a, $user){
	$msg = "";
	$tbName = 'abilities';
	$con = connect_to_server();
	$sql = "SELECT `act_abil` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$equipList = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	if(!empty($equipList['act_abil'])){
		$equipList = explode(":", $equipList['act_abil']);
	}
	else{
		$equipList = array();
	}
	
	if(is_numeric($a) && $a > 100 && $a < 125){
		$count = count($equipList);
		if($count >= EQUIP_LIMIT){ //limit to 8 abilities
			$msg = "Error: Your pet already has the maximum 8 abilities active.";
		}
		if(in_array($a, $equipList)){
			$msg = "Error: That ability is already active.";
		}
		
		if(empty($msg)){ //if no errors, add new ability to abilities list
			$equipList[] = $a;
			sort($equipList);
		}
	}
	
	$newList = implode(":", $equipList);
	$sql = "UPDATE `petdb` SET `act_abil` = '$newList' WHERE `owner` = '$user'";
	if(!mysqli_query($con, $sql)){
		$msg = "Error: could  not activate ability.";
	}
	
	return $msg;
}


//deactivates ability
//updates petdb with new ability list
function deactivate_ability($a, $user){
	$con = connect_to_server();
	$sql = "SELECT `act_abil` FROM `petdb` WHERE `owner` = '$user'";
	$result = mysqli_query($con, $sql);
	$result = mysqli_fetch_array($result);
	$aArray = $result['act_abil'];
	$aArray = explode(":", $aArray);
	
	if(in_array($a, $aArray)){
		$i = array_search($a, $aArray);
		array_splice($aArray, $i, 1);
		
		$updatedAbilityList = implode(":", $aArray);
		//echo "<br>updated weapon list: " . $updatedAbilityList . "<br>";
		
		$sql = "UPDATE `petdb` SET act_abil = '$updatedAbilityList' WHERE `owner` = '$user'";
		mysqli_query($con, $sql);
	}
}

//takes idb weapon id
//checks db to see if id already exists
//if not, scrapes data from idb
//outputs data, asks user to flag if incorrect
function add_new_weapon($id){
	$con = connect_to_server();
	
	if($id < 0){
		$id = 0;
	}
	
	$sql = "SELECT * FROM `weapons` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$data = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if(!empty($data['id'])){
		$output = "";
		$output .= "<b>" . $data['name'] . "</b> (id: " . $data['id'] . ")<br>";
		$output .= "<img src=\"" . $data['image'] . "\"><br>";
		
		$output .= "<b>Attack:</b> ";
		
		if($data['attack']){
			$atk = explode(":", $data['attack']);
			foreach($atk as $a){
				$a = explode(",", $a);
				if(strpos($a[1], ";")){
					$minmax = explode(";", $a[1]);
					$min = substr($minmax[0], 1);
					$max = $minmax[1];
					
					$output .= "<img src=\"img/" . $a[0] . ".png\">x" . $min . "-" . $max . " ";
				}
				else{
					$output .= "<img src=\"img/" . $a[0] . ".png\">x" . $a[1] . " ";
				}
			}
		}
		
		$output .= "<br>";
		
		$output .= "<b>Defense:</b> ";
		
		if($data['defense']){
			$def = explode(":", $data['defense']);
			foreach($def as $d){
				$d = explode(",", $d);
				if($d[1] > 1000){
					$d[1] = ($d[1] - 1000) . "%";
				}
				$output .= "<img src=\"img/" . $d[0] . "_def.png\">x" . $d[1] . " ";
			}
		}
		
		$output .= "<br>";
		
		$output .= "<b>Reflect:</b> ";
		
		if($data['reflect']){
			$ref = explode(":", $data['reflect']);
			foreach($ref as $r){
				$r = explode(",", $r);
				if(count($r) > 2){
					$output .= "<img src=\"img/" . $r[0] . "_def.png\">x" . ($r[1] - 1000) . "-" .($r[2] - 1000) . "%  ";
				}
				else{
					$output .= "<img src=\"img/" . $r[0] . "_def.png\">x" . ($r[1] - 1000) . " ";
				}
			}
		}
		
		$output .= "<br>";
		
		if($data['heal']){
			if($data['heal'] > 1000){
				$heal = ($data['heal'] - 1000) . "%";
			}
			else{
				$heal = $data['heal'];
			}
		}
		$output .= "<b>Heal:</b> " . $heal . "<br>";
		
		if($data['drain']){
			if(strpos($data['drain'], ":")){
				$minmax = explode(":", $data['drain']);
				$drain = $minmax[0] . "-" . $minmax[1];
			}
			elseif(strpos($data['drain'], ",")){
				$d = explode(",", $data['drain']);
				$drain = ($d[0] - 1000) . "%";
			}
			else{
				$drain = $data['drain'];
			}
		}
		$output .= "<b>Drain:</b> " . $drain . "<br>";
		
		if($data['steal']){
			$steal = ($data['steal'] - 1000) . "%";
		}
		$output .= "<b>Steal:</b> " . $steal . "<br>";
		
		if($data['freeze']){
			$freeze = ($data['freeze'] * 100) . "%";
		}
		$output .= "<b>Freeze:</b> " . $freeze . "<br>";
		
		if($data['useage'] == 1){
			$useage = 'Semi-fragile';
		}
		elseif($data['useage'] == 2){
			$useage = 'Once per battle';
		}
		elseif($data['useage'] == 3){
			$useage = 'Fragile';
		}
		elseif($data['useage'] == 4){
			$useage = 'Single use';
		}
		else{
			$useage = 'Multiuse';
		}
		$output .= "<b>Useage:</b> " . $useage . "<br>";
		
		if($data['equip_one']){
			$equipLimit = "Yes";
		}
		$output .= "<b>Equip One:</b> " . $data['equip_one'] . "<br>";
		$output .= "<b>Item Spawn:</b> " . $data['item_spawn'] . "<br>";
		$output .= "<b>Species:</b> " . $data['species'] . "<br>";
		$output .= "<b>Tier:</b> " . $data['tier'] . "<br>";
		
		$output .= "<br>If these stats are incorrect, click <a href=\"?wid={$id}&addweap=true&flag=true\">here</a> to flag this weapon for manual review.";
	}
	else{
		$check = add_weapon($id);
		$output = $check[0];
		if($check[1]){
			$output = "Added new weapon: <br><br>";
			$output .= add_new_weapon($id);
		}
	}
	
	return $output;
}


//increments flag in db
//if flag is -1, then does nothing
//returns msg confirming flag
function flag_weapon($id){
	$con = connect_to_server();
	$sql = "SELECT `flag` FROM `weapons` WHERE `id` = '$id'";
	$result = mysqli_query($con, $sql);
	$flag = mysqli_fetch_array($result, MYSQLI_ASSOC);
	if($flag['flag'] != -1){
		if(!empty($flag['flag'])){
			$newflag = $flag['flag'] + 1;
		}
		else{
			$newflag = 1;
		}
	}
	
	$sql = "UPDATE `weapons` SET `flag` = '$newflag' WHERE `id` = '$id'";
	if(mysqli_query($con, $sql)){
		$msg = "Item {$id} has been flagged for manual review.";
	}
	else{
		$msg = "Error: could not flag item.";
	}
	
	return $msg;
}


//called by add_new_weapon to do the actual scraping
//checks if weapon already exists in sim db
//if not, scrapes data from idb and adds weapon to sim
//returns weapon data if succesful, error message if not
function add_weapon($id){
	$weapid = $id;
	$pageurl = 'http://battlepedia.jellyneo.net/?go=showweapon&id=' . $weapid;
	$iconlist = array('earth', 'air', 'fire', 'water', 'light', 'darkness', 'physical');
	$iconlist_def_alt = array('earth', 'air', 'fire', 'water', 'light', 'dark', 'physical');
	$iconCountArray = initialize_dmg_arrays();
	define('ICON_URL_BASE', 'http://theoldbattledome.com/img/');
	$limitEntry = "";

	$pagedata = file_get_contents($pageurl);


	//get item name
	$before = 'fff;"\>';
	$after = '\<\/b';
	$nameblock = '/' . $before . '(.*?)' . $after . '/';
	$length = strlen($nameblock);
	preg_match($nameblock, $pagedata, $match);
	$name = $match[1];
	
	$return = array();
	
	if(empty($name)){
		$return[] = "No weapon found (id: " . $id . ")";
		$return[] = false;
	}
	else{
		//get item image
		$before = '"10"\>\<img src="';
		$after = '"';
		$imgblock = '/' . $before . '(.*?)' . $after . '/';
		preg_match($imgblock, $pagedata, $match);
		$img = $match[1];


		/*****ATK ICONS*****/
		//get attack icons
		$before = 'Actual Icons\<\/strong\>\<\/td\>\n\<td\>\<table\ style\=\"width\:100\%\;\"\>\<td\>\<td\>\<\/tr\>\<tr\>\<td\ style\=\"width\:25\%\;\"\>';
		$after = '\/table';
		$atkblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($atkblock, $pagedata, $match);
		$atk = $match[1];

		$atkEntry = "";
		$maxAtk = 0; //for weapon tier
		foreach($iconlist as $icon){
			$pattern = '/' . $icon . '\.png/';
			if(preg_match($pattern, $atk)){
				$before = 'alt\=\"ATTACK\ ' . $icon . '\"\ \/\>\ ';
				$after = '\<\/td\>';
				$iconblock = '/' . $before . '(.*?)' . $after . '/i';
				preg_match($iconblock, $atk, $match);
				$iconcount = trim($match[1]);
				
				if(preg_match('/\-/', $iconcount)){
					$spread = explode("-", $iconcount);
					$min = trim($spread[0]);
					$max = trim($spread[1]);
					$inc = 0.5;
					$avg = ($min + $max) / 2;
					$std = 3;
					$atkEntry .= $icon . ',(' . $min . ';' . $max . ';' . $inc . ';' . $avg . ';' . $std . '):';
					$maxAtk += $max; // for weapon tier
				}
				else{
					$atkEntry .= $icon . ',' . $iconcount . ':';
					$maxAtk += $iconcount; // for weapon tier
				}
			}
		}
		$atkEntry = substr($atkEntry, 0, -1);
		$atkEntry = preg_replace('/physical/', 'phys', $atkEntry);
		$atkEntry = preg_replace('/darkness/', 'dark', $atkEntry);


		/*****DEF ICONS*****/
		//get defensive icons
		$before = 'Defense\<\/strong\>\<\/td\>\n\<td\>\ ';
		$after = '\<\/td';
		$defblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($defblock, $pagedata, $match);
		$def = $match[1];
		
		//count def icons
		$iconDefCount = array();
		foreach($iconlist as $icon){
			$pattern = '/' . $icon . '\_def\.png/';
			preg_match_all($pattern, $def, $matches);
			$iconDefCount[$icon] = (count($matches, COUNT_RECURSIVE)-1);
		}

		//check for 100% def
		$fullblock_100 = preg_split('/100/', $def);
		$fullblock_all = preg_split('/All/', $def);
		if(count($fullblock_100) > 1 || count($fullblock_all) > 1){
			foreach($iconlist_def_alt as $icon){
				$pattern_100 = '/' . $icon . '\*\"\ \/\>\ 1/';
				$pattern_all = '/' . $icon . '\*\"\ \/\>\ A/';
				if(preg_match_all($pattern_100, $def, $matches) || preg_match_all($pattern_all, $def, $matches)){
					//print_r($matches);
					if($icon == 'dark'){
						$iconDefCount['darkness'] = 1100;
					}
					else{
						$iconDefCount[$icon] = 1100;
					}
					
				}
			}
		}


		//create string for def entry in db
		$defEntry = "";
		foreach($iconlist as $icon){
			if(!empty($iconDefCount[$icon])){
				$defEntry .= $icon . ',' . $iconDefCount[$icon] . ':';
			}
		}
		$defEntry = substr($defEntry, 0, -1);
		$defEntry = preg_replace('/physical/', 'phys', $defEntry);
		$defEntry = preg_replace('/darkness/', 'dark', $defEntry);




		/*****REFLECTION*****/
		$before = 'Reflect\<\/strong\>\<\/td\>\n\<td\>\ ';
		$after = '\<\/td';
		$reflectblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($reflectblock, $pagedata, $match);
		$reflect = $match[1];
		echo $reflect . "<br>";
		$reflectEntry = "";

		foreach($iconlist_def_alt as $icon){
			$before = $icon . '\*\"\ \/\>\ ';
			$after = '\%';
			$reficon = '/' . $before . '(.*?)' . $after . '/s';
			preg_match($reficon, $reflect, $match);
			$reflectamt = $match[1];
			
			$refRange = preg_split('/\-/', $reflectamt);
			if(count($refRange) > 1){
				$numbers = '/\d+/';
				preg_match_all($numbers, $reflectamt, $match);
				$min = $match[0][0] + 1000;
				$max = $match[0][1] + 1000;
				$reflectEntry .= $icon . ',' . $min . ',' . $max . ':';
			}
			elseif($reflectamt > 0){
				$reflectEntry .= $icon . ',' . ($reflectamt + 1000) . ':';
			}
		}
		$reflectEntry = substr($reflectEntry, 0, -1);


		/*****USEABILITY/RESTRICTIONS*****/
		//get useability and species/1p restrictions
		$before = 'alt\=\"' . $name . '\"\ \/\>\<br\ \/\>\n\<p\>';
		$after = '\<div';
		$useageblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($useageblock, $pagedata, $match);
		$useage = $match[1];
		
		$useageArray = array('Multiuse'=>0, 'Semi-Fragile'=>1, 'Once per Battle'=>2, 'Fragile'=>3, 'Single Use'=>4);
		$useageEntry = "";
		foreach($useageArray as $use=>$n){
			$pattern = '/' . $use . '/';
			preg_match($pattern, $useage, $match);
			if($match){
				$useageEntry = $n;
			}
		}

		$speciesEntry = "";
		$before = '';
		$after = '\ Only';
		$restriction = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($restriction, $useage, $match);
		$restriction = $match[1];
		if($restriction){
			$speciesEntry = $restriction;
		}

		
		/*****HEAL*****/
		//get heal amount
		$before = 'alt\=\"\*heal\*\"\ \/\>\ ';
		$after = '\<';
		$healblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($healblock, $pagedata, $match);
		$heal = $match[1];

		$maxHeal = 0; //for weapon tier
		
		//check for heal range or %
		$healRange = preg_split('/\-/', $heal);
		$healPercent = preg_split('/\%/', $heal);
		if(count($healRange) > 1){
			$numbers = '/\d+/';
			preg_match_all($numbers, $heal, $match);
			$min = $match[0][0];
			$max = $match[0][1];
			$healEntry = $min . ':' . $max;
			$maxHeal = $max; //for weapon tier
		}
		elseif(count($healPercent) > 1){
			$numbers = '/\d+/';
			preg_match($numbers, $heal, $match);
			$healPercent = $match[0];
			$healEntry = $healPercent + 1000;
			$maxHeal = $healEntry; //for weapon tier
		}
		else{
			$numbers = '/\d+/';
			preg_match($numbers, $heal, $match);
			$healEntry = $match[0];
			$maxHeal = $healEntry; //for weapon tier
		}
		if(!empty($healEntry)){
			$limitEntry .= "H"; //for equip limit
		}


		/*****FREEZE*****/
		//check for freeze %
		$before = 'alt\=\"\*freeze\*\"\ \/\>\ ';
		$after = '\<';
		$freezeblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($freezeblock, $pagedata, $match);
		$freeze = $match[1];

		$freezeEntry = "";
		$numbers = '/\d+/';
		preg_match($numbers, $freeze, $match);
		$freezePercent = $match[0];
		if($freezePercent){
			$freezeEntry = $freezePercent / 100;
			$limitEntry .= "F"; //for equip limit
		}


		//check for item generation
		$before = 'alt\=\"\*give_item\*\"\ \/\>\ ';
		$after = 'idb\=1';
		$giveitemblock = '/' . $before . '(.*?)' . $after . '/s';
		preg_match($giveitemblock, $pagedata, $match);
		$giveitem = $match[1];

		$generateEntry = "";
		if($giveitem){
			$before = 'id\=';
			$after = '\&';
			$itemID = '/' . $before . '(.*?)' . $after . '/s';
			preg_match($itemID, $giveitem, $match);
			$generateEntry = $match[1];
		}
		
		sleep(1); //to avoid spamming jn's server with requests
		
		//calculate weapon tier	
		$htweapons = file_get_contents('1p/weaponlist_ht.txt');
		$coveweapons = file_get_contents('1p/weaponlist_cove.txt');
		$htweaps = explode(PHP_EOL, $htweapons);
		$coveweaps = explode(PHP_EOL, $coveweapons);
		
		$tier = (($maxAtk > 9 || $maxHeal > 30) ? 1 : 0);
		if(in_array($name, $htweaps)){
			$tier = 2;
		}
		if(in_array($name, $coveweaps)){
			$tier = 3;
		}




		/*****SQL ENTRY*****/
		$con = connect_to_server();
		$sql = "INSERT INTO `weapons` (id, name, image, attack, defense, reflect, heal, freeze, useage, equip_one, item_spawn, species, tier, flag)
		VALUES ('$weapid', '$name', '$img', '$atkEntry', '$defEntry', '$reflectEntry', '$healEntry', '$freezeEntry', '$useageEntry', '$limitEntry', '$generateEntry', '$speciesEntry', '$tier', 1)";

		if(!mysqli_query($con, $sql)){
			printf(mysqli_error($con));
		}
		else{
			$return[] = "Added weapon.";
			$return[] = true;
		}
	}
	
	return $return;

}













?>