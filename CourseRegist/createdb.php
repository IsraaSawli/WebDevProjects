<?php
$dbName="registration";
if ($dbc = @mysqli_connect('localhost','root', ''))
 {
		print '<p>Successfully connected to MySQL!</p>';
        
		$query = "CREATE DATABASE $dbName";		
		if (@mysqli_query($dbc,$query))
		{
			print "The database $dbName has been created!";
		}
		else {  echo "impossible creation db"; }		
		mysqli_close($dbc); // Close the connection.
}
else
{ echo "connection server impossible"; }
?>