<?php
require_once "../Database/DBConnection.php";

$name = $_POST['name'];

$sql = "SELECT * FROM Menu WHERE name LIKE '$name%' OR type LIKE '$name%'";
$result = mysqli_query($conn, $sql);

if($result->num_rows < 1)
	echo "<h4 align='center'>Product or Category does not exist!</h4>";
else{
	$output = "";
	$output .= " <table align='center' cellpadding='10' cellspacing='0'><tr>";
	$i = 1;
	while($row = mysqli_fetch_array($result)){
		// if($row['stock'] == 0)
		// 		$cartStatus = " disabled";
		// else
		// 	$cartStatus = "";
		if(strlen($row['description']) > 30)
			$desc = substr($row['description'],0,9);
		else
			$desc = $row['description'];
			$output .=  "<td align='center' class='px-5'>".
						"<img src='../assets/images/".$row['image']."' width='150px' height='150px'><br>".
							$row['name']."<br>".
							$desc."...<br>".
							$row['type']."<br>".
							$row['price']."<br>
							<button type='submit' name='btnATC' value='".$row['mId']."' ".">Add to Cart</button>
						</td>";
		if($i%4==0)
			$output .= "</tr><tr>";
		$i++;
	}
	$output .= "</table>";
	echo $output;
}
?>