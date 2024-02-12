<?php
if ($dbc = @mysqli_connect('localhost','root', ''))
 { 
	$dbName="registration";
	mysqli_select_db($dbc ,$dbName) 
	or die ('<p style="color: red;">Could not select the database '.
				$dbName.' because:<br/>'.mysqli_error($dbc).'.</p>');

}
else
{
	print '<p style="color: red;">Could not connect to
	MySQL server:<br />'.mysqli_error($dbc).'.</p>';
}
?>