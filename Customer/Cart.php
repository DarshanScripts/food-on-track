<?php
session_start();

if (isset($_SESSION['sesLogin']['btnLogin'])) {
	$cId = $_SESSION['sesLogUser']['cId'];

	if (isset($_POST['btnRFC'])) {
		require_once '../Database/DBConnection.php';
		$sql = "DELETE FROM Cart WHERE cId = ? AND mId = ?";
		$stmt = $conn->prepare($sql);
		$mId = $_POST['btnRFC'];
		$stmt->bind_param('ii', $cId, $mId);
		$stmt->execute();
		if ($stmt)
			echo "<script>alert('Item removed from Cart!');window.location.replace('./Cart.php');</script>";
		else
			echo "<script>alert('Item not removed from cart!');</script>";
	}

	if (isset($_POST['btnBuy'])) {
		require_once '../Database/DBConnection.php';

		// Start transaction
		$conn->begin_transaction();

		$success = true;

		// Fetch all items from the cart for this user
		$sql = "SELECT * FROM Cart WHERE cId = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('i', $cId);
		$stmt->execute();
		$result = $stmt->get_result();

		// Prepare the insert statement for orders
		$sqlInsert = "INSERT INTO Orders (cId, mId) VALUES (?, ?)";
		$stmtInsert = $conn->prepare($sqlInsert);

		// Prepare the delete statement for cart
		$sqlDelete = "DELETE FROM Cart WHERE cId = ? AND mId = ?";
		$stmtDelete = $conn->prepare($sqlDelete);

		while ($row = $result->fetch_assoc()) {
			$mId = $row['mId'];
			$stmtInsert->bind_param('ii', $cId, $mId);

			if ($stmtInsert->execute()) {
				// If the insert is successful, remove the item from the cart
				$stmtDelete->bind_param('ii', $cId, $mId);
				if (!$stmtDelete->execute()) {
					$success = false;
					break;
				}
			} else {
				$success = false;
				break;
			}
		}

		if ($success) {
			$conn->commit();
			echo "<script>alert('Items purchased. They will be delivered to you soon!');window.location.replace('./ViewOrders.php');</script>";
		} else {
			$conn->rollback();
			echo "<script>alert('Items not purchased! Please try again.');</script>";
		}
	}
?>

	<!DOCTYPE html>
	<html>

	<head>
		<meta charset="UTF-8">
		<title>Food On Track</title>
		<link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
		<script src="../assets/jquery-3.2.1.min.js"></script>
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
		<h3 align='center'>Your Cart</h3>
		<form method="POST">
			<?php
			require_once '../Database/DBConnection.php';
			$output = "";
			$output .= "<table cellspacing='30px' cellpadding='30px' align='center'><tr>";
			$sql = "SELECT * FROM Menu S, Cart C WHERE S.mId = C.mId AND C.cId = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param('i', $cId);
			$stmt->execute();
			$result = $stmt->get_result();

			$sql2 = "SELECT SUM(price) AS 'total' FROM Menu S, Cart C WHERE S.mId = C.mId AND C.cId = ?";
			$stmt2 = $conn->prepare($sql2);
			$stmt2->bind_param('i', $cId);
			$stmt2->execute();
			$result2 = $stmt2->get_result();
			$row2 = $result2->fetch_assoc();

			$i = 1;
			$noOfItems = 0;

			if ($result->num_rows > 0) {
				$noOfItems = $result->num_rows;
				while ($row = $result->fetch_assoc()) {
					$desc = strlen($row['description']) > 30 ? substr($row['description'], 0, 29) : $row['description'];
					$output .= "<td align='center' class='px-5'>" .
						"<img src='../assets/images/" . $row['image'] . "' width='150px' height='150px'><br>" .
						$row['name'] . "<br>" .
						$desc . "...<br>" .
						$row['type'] . "<br> Price: " .
						$row['price'] . "<br>
                    <button type='submit' name='btnRFC' value='" . $row['mId'] . "'>Remove from Cart</button>
                </td>";
					if ($i % 4 == 0)
						$output .= "</tr><tr>";
					$i++;
				}
			} else {
				$output = "No Item in cart!";
			}

			$output .= "</table><br><br><center>Total No. of Items: <b>" . $noOfItems . "</b>";
			$output .= "<br><center>Total Amount: <b>" . $row2['total'] . "</b></center>";

			echo $output;
			?>
			<hr>
			<?php if ($noOfItems > 0) : ?>
				<div id="orderDetails">
					<center><br>
						Order via:
						<select name="orderType" id="type">
							<option hidden>Select</option>
							<option value="PNR">PNR</option>
							<option value="TrainNo">Train No.</option>
						</select>
						<br><br>

						<div id="callType"></div>

						<button type='submit' name='btnBuy' class='px-3'>Deliver</button><br><br><br>
					</center>
				</div>
			<?php endif; ?>
		</form>

		<script>
			$(document).ready(function() {
				$("#type").change(function() {
					var type = $(this).val();
					console.log(type);
					if (type == "PNR")
						$("#callType").html('<input type="text" id="pnr" name="txtPNR" placeholder="Enter PNR" style="width:20%" class="form-control" pattern="[0-9]{10}" required><br>Boarding Station: <select name="selBoardingStation" id="station" required><option hidden>Select</option><option value="Surat">Surat</option><option value="Vadodara">Vadodara</option><option value="Vapi">Vapi</option><option value="Bardoli">Bardoli</option><option value="Navsari">Navsari</option></select><br><br>');
					if (type == "TrainNo")
						$("#callType").html('<input type="text" id="trainNo" name="txtTrainNo" placeholder="Enter Train No." style="width:20%" class="form-control" pattern="[0-9]{5}" required><br><br>Train Name: <input type="text" id="trainName" name="txtTrainName" placeholder="Enter Train Name" style="width:20%" class="form-control" required><br><br>Boarding Date: <input type="date" id="boardingDate" name="dtBoardingDate" style="width:20%" class="form-control" required><br><br>Boarding Station:<select name="selBoardingStation" id="station" required><option hidden>Select</option><option value="Surat">Surat</option><option value="Vadodara">Vadodara</option><option value="Vapi">Vapi</option><option value="Bardoli">Bardoli</option><option value="Navsari">Navsari</option></select><br><br>');
				});
			});
		</script>

	</body>

	</html>
<?php
} else {
	echo "Please <a href='./CustLogin.php'>Login</a> first!";
}
?>