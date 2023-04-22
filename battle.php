<?php
session_start();
require 'functions.php';
require('layout.php');
$con = connect_to_server();
?>

<?php
$user = "test";
$petname = $_GET['pet'];
if(!checkPetName($petname, $user)){ //check when trying to start battle
	echo "Error: You don't own that pet. <br> <a href=\"select.php\">&lt;&lt;&lt;Back</a>";
	exit();
}
if(is_numeric($_GET['id'])){
	$oppID = get_oppID($_GET['id'], $petname);
}
?>

<head>
	<style>
		a {
		text-decoration: none;
		color:blue;
		//font-weight: bold;
		}
		a:hover {
			color:rgb(246,188,48);
		}
		body {
			background-color:rgb(214,214,214);
			font-family: Verdana,Arial,Helvetica,sans-serif;
			font-size:12;
		}
		.spacer{
			width:0%;
		}
		.left{
			text-align:left;
		}
		.right{
			text-align:right;
		}
		.petpics{
			width:100px;
		}
		label{
			 cursor:pointer;
		}
		table{
			 margin-left:auto;
			 margin-right:auto;
			 border-collapse: collapse;
		}
		td,th{
			border:none;
			max-width:200px;
		}
	</style>
	
	<script>
		var selected = []; //to track which weapons are clicked
		var base = "battle.php?";
		var opp = <?php echo $oppID; ?>;
		var pet = "<?php echo $petname; ?>";
		var $w1, $w2;
		
		function countWeaps(id){
			var check = selected.indexOf(id);
			if(check == -1){
				selected.push(id);
				
				if(selected.length > 2){
				document.getElementById(selected[0]).checked = false;
				selected.shift();
				}
			}
			
			if(!document.getElementById(id).checked){
				var i = selected.indexOf(id);
				selected.splice(i, 1);
			}
			
			
			console.log(selected);
		}
		
		
		function submitMove(){
			var a_ = document.getElementById("ability");
			var a = a_.options[a_.selectedIndex].value;

			
			var p_ = document.getElementById("power");
			var p = p_.options[p_.selectedIndex].value;
			
			for(var i=0; i<2; i++){
				if(!selected[i] && (selected[i] != 0)){
					selected[i] = "";
				}
				else{
					var id = document.getElementById(selected[i]).value;
					selected[i] = id;
				}
			}
			
			base += "id=" + opp + "&pet=" + pet + "&w1=" + selected[0] + "&w2=" + selected[1] + "&a=" + a + "&p=" + p;
			location.href=base;
		}
	</script>
</head>


<?php
$combatLog;
$oppName;
$oppImg = get_opp_img($oppID, $con);
$oppCurrHP;
$oppMaxHP;
$oppPower;
$petCurrHP;
$petMaxHP;
$petPower;
$petWeaponList;
$petAbilities;
$oppWeaponList;
$oppAbilities;
$w1;
$w2;
$oppW1;
$oppW2;
$oppStr;
$p2Dmg;
$oppDmg;

load_fight($petname); //gets fight data from db
process_turn(); //process last move
draw_petpower_bar($petPower);
draw_opppower_bar($oppPower);




?>

<div id="container" style="padding-top:50px; padding-bottom:50px; background-color:white; max-width:1200; margin-left:auto; margin-right:auto;">
	<div id="userbar" style="padding-bottom:10px; width=100%;">
		<?php display_user_bar($user); ?>
	</div>
	
	<div id="hpbars" style="max-width:500px; margin-left:auto; margin-right:auto;">
		<table style="text-align:center; font-weight:bold; margin-left:auto; margin-right:auto;" border="0">
			<tr valign="bottom">
				<td><img class="petpics" src="<?php output_pet_image(get_pet_species($petname), get_pet_colour($petname)); ?>"></td>
				<td></td>
				<td><img class="" src="<?php echo $oppImg;?>"></td>
			</tr>
			<tr>
				<td colspan="3"><img width="450" height="30" src="img/platformthing.png"></td>
			</tr>
			
			<tr>
				<td valign="middle">
				<?php echo $petname; ?>
				<br>
				<span style="color:<?php echo get_hp_text_color($petCurrHP, $petMaxHP); ?>;"><?php echo $petCurrHP; ?> / <?php echo $petMaxHP; ?></span>
				<br>
				<?php echo display_frozen_img($petname, $player = 'pet');?>
				Power <?php echo $petPower; ?>%
				<br>
				<img align="left" src="petpowerbar.png?=<?php echo filemtime("petpowerbar.png")?>">
				</td>
				
				<td width="50px">
				</td>
				
				<td>
				<?php echo $oppName; ?>
				<br>
				<span  style="color:<?php echo get_hp_text_color($oppCurrHP, $oppMaxHP); ?>;"><?php echo $oppCurrHP; ?> / <?php echo $oppMaxHP; ?></span>
				<br>
				<?php echo display_frozen_img($petname, $player = 'opp');?>
				Power <?php echo $oppPower; ?>%
				<br>
				<img align="right" src="opppowerbar.png?=<?php echo filemtime("opppowerbar.png")?>">
				</td>
			</tr>

		</table>
	</div>
	<br><br>
	<div id="combatLog" style="background-color:rgb(170,171,254); max-width:95%; margin-left:auto; margin-right:auto;">
		
		<table id="combatLog" width="100%" border=2; cellspacing="0">
		<?php echo $combatLog; ?>
		</table>
		
	</div>
	<br>
	<div id="equipment" style="max-width:95%; margin-left:auto; margin-right:auto;">

					<?php output_weapon_list($petWeaponList); ?>

	</div>

</div>
<?php footer(); ?>
