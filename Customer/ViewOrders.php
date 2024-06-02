<?php
session_start();

if (isset($_SESSION['sesLogin']['btnLogin'])) {
	$cId = $_SESSION['sesLogUser']['cId'];
	require_once '../Database/DBConnection.php';

	if (isset($_POST['deleteOrder'])) {
		$orderId = isset($_POST['deleteOrder']) ? $_POST['deleteOrder'] : null;
		if ($orderId !== null) {
			$sqlDelete = "DELETE FROM Orders WHERE orderId = ?";
			$stmtDelete = $conn->prepare($sqlDelete);
			$stmtDelete->bind_param('i', $orderId);
			if ($stmtDelete->execute()) {
				// Delete successful
				header("Refresh:0"); // Refresh the page to reflect changes
			} else {
				// Delete failed
				echo "<script>alert('Failed to delete order!');</script>";
			}
		}
	}

	$sql = "SELECT O.orderId, O.cId, O.mId, M.name, M.description, M.price, M.type, M.image, O.orderDate
            FROM Orders O
            JOIN Menu M ON O.mId = M.mId
            WHERE O.cId = ?
            ORDER BY O.orderDate DESC";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('i', $cId);
	$stmt->execute();
	$result = $stmt->get_result();
?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="UTF-8">
		<title>Your Orders</title>
		<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
		<style>
			a {
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
			<a href="./Homepage.php">Homepage</a>
		</h1>
		<hr>
		<h3 align='center'>Your Orders</h3>
		<form method="POST">
			<table class="table table-bordered table-hover w-75" align="center">
				<thead>
					<tr>
						<th>Order ID</th>
						<th>Item Name</th>
						<th>Description</th>
						<th>Type</th>
						<th>Price</th>
						<th>Order Date</th>
						<th>Image</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							$desc = strlen($row['description']) > 30 ? substr($row['description'], 0, 29) : $row['description'];
							echo "<tr>";
							echo "<td>{$row['orderId']}</td>";
							echo "<td>{$row['name']}</td>";
							echo "<td>{$desc}...</td>";
							echo "<td>{$row['type']}</td>";
							echo "<td>{$row['price']}</td>";
							echo "<td>{$row['orderDate']}</td>";
							echo "<td><img src='../assets/images/{$row['image']}' width='120px' height='100px'></td>";
							echo "<td><button type='submit' name='deleteOrder' class='btn btn-danger' value='{$row['orderId']}'>Delete</button></td>";
							echo "</tr>";
						}
					} else {
						echo "<tr><td colspan='8' align='center'>No orders found!</td></tr>";
					}
					?>
				</tbody>
			</table>
		</form>
	</body>

	</html>

<?php
} else {
	echo "Please <a href='./CustLogin.php'>Login</a> first!";
}
?>