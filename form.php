<?php 
require_once 'lib/data-function.php';

if(!empty($_POST)){
	$data = array (
		'country' => $_POST['country'],
		'season' => $_POST['season'],
		'again' => $_POST['again'],
		'comment' => $_POST['comment'],
);
	echo $data;
	insert_data;
}
?>

<form action="" method="post">
Name of country: <input type="text" name="country">
<br>
<br>
Season:
<br>
 <input type="radio" name="season" value="spring" id="season-spring">
 <label for="season-spring">spring</label>
 <br>
 <input type="radio" name="season" value="summer" id="season-summer">
 <label for="season-spring">summer</label>
 <br>
  <input type="radio" name="season" value="fall" id="season-fall">
 <label for="season-fall">fall</label>
 <br>
  <input type="radio" name="season" value="winter" id="season-winter">
 <label for="season-winter">winter</label>
 <br>
 <br>
 <label for="again">I want to go again</label>
 <input type="checkbox" name="again" id="again">
 <br>
 <br>
 Reason of recommendation
 <br>
 <textarea name="comment" cols="30" rows="5"></textarea><br />
  <br />
  <input type="submit" value="post" />

</form>