<?php
if(isset($_POST['nd']))
{
 	$car=$_POST['car'];
	$nd=$_POST['nd'];
	
	if($car == "Mercedes")
	$pd = 120;
	else
	{
	  if($car == "BMW")
	   $pd = 100;
	  else $pd = 80;
	}

	if(isset($_POST['driver']) && $_POST['driver'] == 'Yes')
	{
	  $dd="with driver ";
	  $pd= $pd + 50;
	}
	else
	{
	  $dd="without driver ";
	}

	$pd = $pd * $nd;
	echo "<h1> <b> <u> Details of your invoice </u> </b></h1>";
	echo "Your command is $car $dd for $nd days<br> your total price is $ $pd <br> ";
	echo "<hr> <H2> INFORMATION </h2>";
	echo "Mercedes=120 <br>BMW=100<br> Honda=80<br> With driver=50";

}
else echo "error!"
?>



<html>
<head>
<title> RENT CAR SYSTEM </title>
</head>
<body>

<h1><u><b>Rental Car System</b></u></h1>

<form name="rent" action="carRent.php" method="POST">

<pre>
     Car Brand:     <select name="car" size="1">
				<option>Mercedes</option>
				<option>BMW</option>
				<option>Honda</option>
				</select> <br>
     Number of Days:<input type="text" size="10" name="nd"> <br>
     With Driver:<input type="checkbox" name="driver" value="Yes"> <br>
           <input type="submit" value="Calculte Price" >  <input type="reset" value="Reset">
</pre>
</form>

</body>
</html>
