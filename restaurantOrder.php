<?php
session_start();
$menu = array('Crusty' => 2750, "Zingrella" => 2750, "Rosto" => 3000, "Turkey" => 3250);

if (!isset($_SESSION['order'])) {
  $_SESSION['order'] = array_fill_keys(array_keys($menu), 0);
}
print_r( $_SESSION);
if (isset($_GET['name'])) { //order.php?name=Crusty  <a href='order.php?name=" . $name . "'>
  $name = $_GET['name'];
  $_SESSION['order'][$name] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($menu as $name => $price) {
    if (isset($_POST[$name])) {
      $_SESSION['order'][$name] = (int)$_POST[$name];
    }
  }
}

echo "<h1>Order Online</h1>";
echo "<form method='post' action=''>";
echo "<table border>";
echo "<tr><th>Quantity</th><th>Sandwich</th><th>Unit price</th><th>Total</th><th></th></tr>";

foreach ($menu as $name => $price) {
  echo "<tr>";
  $qty = $_SESSION['order'][$name];

  echo "<td><input name='" . $name . "' type='text' value='" . $qty . "' /></td>";
  echo "<td>" . $name . "</td>";// crusty
  echo "<td>" . $price . "</td>";
  $total = $qty * $price;
  echo "<td>" . $total . "</td>";
  echo "<td><a href='order.php?name=" . $name . "'>DELETE</a></td>";
  echo "</tr>";
}
echo "<br/>";
echo "<br/>";
echo "<button type='submit'>UPDATE Order</button>";
echo "</table>";
echo "</form>";
