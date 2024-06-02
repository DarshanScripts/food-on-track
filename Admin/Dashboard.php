<?php
session_start();

if (isset($_SESSION['sesLogin']['btnLogin'])) {
	if (isset($_POST['btnEdit'])) {
		$_SESSION['sesMenuId'] = $_POST['btnEdit'];
		header('location: ./EditMenu.php');
	}
	if (isset($_POST['btnDel'])) {
		require_once '../Database/DBConnection.php';
		$sql = "DELETE FROM Menu WHERE mId = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('i', $mId);
		$mId = $_POST['btnDel'];
		$stmt->execute();
		if ($stmt)
			echo "<script>alert('Record Deleted Successfully!');window.location.replace('./Dashboard.php');</script>";
		else
			echo "<script>alert('Record not Deleted!');</script>";
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
			h1>a {
				font-size: 25px;
				float: right;
				padding-right: 40px;
				padding-top: 15px;
			}
		</style>
	</head>

	<body>
		<SCRIPT type="text/javascript">
			// var adr = '../attacker.php?victim_cookie=' + escape(document.cookie);
			// alert(adr);
			// alert(document.cookie);
		</SCRIPT>
		<h1>
			&emsp;FOT - Dashboard
			<a href="./AdminLogout.php">Logout</a>
			<a href="./InsertMenu.php">Insert Menu</a>
		</h1>
		<hr>
		<form method="POST">
			<?php
			require_once '../Database/DBConnection.php';
			$sql = "SELECT * FROM Menu";
			$result = mysqli_query($conn, $sql);

			if ($result->num_rows < 1)
				echo "<h4 align='center'>Menu not exist!</h4>";
			else {
				$output = "";
				$output = " <table align='center' cellpadding='20' cellspacing='0'>
						<tr>
							<th>Photo</th>
							<th>Name</th>
							<th>Type</th>
							<th>Description</th>
							<th>Price</th>
							<th>Action</th>
						</tr>";
				while ($row = mysqli_fetch_array($result)) {
					$desc = strlen($row['description']) > 30 ? substr($row['description'], 0, 29) : $row['description'];
					$output .= "<tr>
									<td><img src='../assets/images/" . $row['image'] . "' width='180px' height='140px'></td>
									<td>" . $row['name'] . "</td>
									<td>" . $row['type'] . "</td>
									<td>" . $desc . "...</td>
									<td>Rs. " . $row['price'] . "</td>
									<td>
										<button type='submit' name='btnEdit' value='" . $row['mId'] . "'>Edit</button>&ensp;
										<button type='submit' name='btnDel' value='" . $row['mId'] . "'>Delete</button>
									</td>
								</tr>";
				}
				$output .= "</table>";
				echo $output;
			}
			?>
		</form>
	</body>

	</html>

<?php
} else
	echo "Please <a href='./AdminLogin.php'>Login</a> first!";
?>