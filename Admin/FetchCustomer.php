<?php
include_once '../Database/DBConnection.php';

$email = $_POST['email'];

$sql = "SELECT * FROM User_ WHERE email LIKE '$email%'";
$result = mysqli_query($conn, $sql);

if($result->num_rows < 1)
	echo "<h4 align='center'>Shoes not exist!</h4>";
else{
	$output = "";
	$output = " <table align='center' cellpadding='10' cellspacing='0'>
				<tr>
					<th>Photo</th>
					<th>Shoe Id</th>
					<th>Brand</th>
					<th>Category</th>
					<th>Price</th>
					<th>Stock</th>
					<th>Action</th>
				</tr>";
	while($row = mysqli_fetch_array($result)){
			$output .= "<tr>
							<td>".$row['photo']."</td>
							<td>".$row['sId']."</td>
							<td>".$row['brand']."</td>
							<td>".$row['category']."</td>
							<td>".$row['price']."</td>
							<td>".$row['stock']."</td>
							<td>
								<button type='submit' name='btnEdit' value='".$row['sId']."'>Edit</button>&ensp;
								<button type='submit' name='btnDel' value='".$row['sId']."'>Delete</button>
							</td>
						</tr>";
	}
	$output .= "</table>";
	echo $output;
}
?>