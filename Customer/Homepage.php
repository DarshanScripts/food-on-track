<?php
session_start();

if (isset($_SESSION['sesLogin']['btnLogin'])) {
	$cId = $_SESSION['sesLogUser']['cId'];

	if (isset($_POST['btnATC'])) {
		// print_r($_POST);
		require_once '../Database/DBConnection.php';
		$sql = "INSERT INTO Cart VALUES(?,?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ii',$cId,$mId);
		$mId = $_POST['btnATC'];
		$stmt->execute();
		if ($stmt)
			echo "<script>alert('Item is added to Cart!');window.location.replace('./Homepage.php');</script>";
		else
			echo "<script>alert('Item not added to cart!');</script>";
	}

	if (isset($_POST['btnBuy'])) {
		$_SESSION['sesMId'] = $_POST['btnBuy'];
		header('location:Purchase.php');
	}
	?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="UTF-8">
		<title>Food On Track</title>
		<!-- <link rel="stylesheet" href="../assets/style.css"> -->
		<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
		<style>
			a{
				font-size: 25px;
				float: right;
				padding-right: 40px;
				padding-top: 15px;
			}
		</style>
	</head>

	<body>
		<h1>
			&emsp;Food On Track
			<a href="./CustLogout.php">Logout</a>
			<a href="./Cart.php">Go to Cart</a>
			<a href="./ViewOrders.php">View Order</a>
			<input type="text" id="search" placeholder="Search..." style="float:right; width:20%" class="form-control mt-3 mx-4">
		</h1>
		<hr>
		<!-- <center>
			Order via: 
			<select name="orderType">
				<option value="PNR">PNR</option>
				<option value="Train No.">Train No.</option>
			</select>
			<input type="text" id="search" placeholder="Search..." style="float:right; width:20%" class="form-control mt-3 mx-4">
		</center> -->
		

		<h3 align='center'>List of Items</h3>
		<form method="POST">
		<?php
		require_once '../Database/DBConnection.php';
		$output = "";
		$output .=  "<table cellspacing='30px' cellpadding='30px' align='center' id='result'><tr>";
		$sql = "SELECT * FROM Menu";
		$result = mysqli_query($conn,$sql);

		$i = 1;
		while($row = $result->fetch_assoc()){
			// if($row['stock'] == 0)
			// 	$status = " disabled";
			// else
			// 	$status = "";

			$sql2 = "SELECT * FROM Cart WHERE cId = ? AND mId = ?";
			$stmt = $conn->prepare($sql2);
			$stmt->bind_param('ii',$cId,$mId);
			$mId = $row['mId'];
			$stmt->execute();
			$result2 = $stmt->get_result();

			if($result2->num_rows > 0)
				$cartStatus = " disabled";
			else
				$cartStatus = "";
				
			if(strlen($row['description']) > 30)
				$desc = substr($row['description'],0,29);
			else
				$desc = $row['description'];
				$output .=  "<td align='center' class='px-5'>
							<img src='../assets/images/".$row['image']."' width='180px' height='140px'><br>".
							$row['name']."<br>".
							$desc."...<br>".
							$row['type']."<br>". "Price: " .
							$row['price']."<br>
							<button type='submit' name='btnATC' value='".$row['mId']."' ".$cartStatus.">Add to Cart</button>
							
						</td>";
			if($i%4==0)
				$output .= "</tr><tr>";
			$i++;
		}
		echo $output;
		?>
		</form>

		<script src="../assets/jquery-3.2.1.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#search").keyup(function(){
					var input = $(this).val();
					$.ajax({
						url: "./FetchItem.php",
						type: "POST",
						data: {
							"name": input
						},
						success: function(data){
							$("#result").html(data);
						}
					});
				});
			});
		</script>
	</body>

	</html>
<?php
} else
    echo "Please <a href='./CustLogin.php'>Login</a> first!";
?>


<!-- <button type='submit' name='btnBuy' class='px-3' value='".$row['mId']."' ".">Buy</button> -->