<?php
session_start();

if (isset($_SESSION['sesLogin']['btnLogin'])) {

    $mId = $_SESSION['sesMenuId'];

    require_once '../Database/DBConnection.php';
    $sql = "SELECT * FROM Menu WHERE mId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $mId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";

        $name = $row['name'];
        $type = $row['type'];
        $description = $row['description'];
        $price = $row['price'];
        $photo = $row['image'];
    }

    if (isset($_POST['btnUpd'])) {
        $fileName = $_FILES['filePhoto']['name'];
        $fileTmpName = $_FILES['filePhoto']['tmp_name'];
        $fileType = $_FILES['filePhoto']['type'];
        $fileSize = $_FILES['filePhoto']['size'];
        if ($fileSize > 2097152)
            echo "<script>alert('Please upload the photo of less than or equal to 2 MB!');</script>";
        elseif ($fileType != "image/jpeg")
            echo "<script>alert('Please upload the photo of type JPEG!');</script>";
        else {
            require_once '../Database/DBConnection.php';
            $sql = "UPDATE Menu SET name = ?, type = ?, description = ?, price = ?, image = ? WHERE mId = '$mId'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $name, $type, $description, $price, $fileName);
            $name = $_POST['txtName'];
            $type = $_POST['selType'];
            $description = $_POST['txtaDescription'];
            $price = $_POST['txtPrice'];
            // $stock = $_POST['txtStock'];
            $stmt->execute();
            if ($stmt) {
                echo "<script>alert('Menu updated Successful!');window.location.replace('./Dashboard.php');</script>";
                $dir = "../assets/images/" . $fileName;
                move_uploaded_file($fileTmpName, $dir);
            } else
                echo "<script>alert('Menu not updated!');</script>";
        }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Edit Menu | FOT</title>
        <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="../assets/style.css">
        <style>
            a {
                font-size: 25px;
                float: right;
                padding-right: 30px;
                padding-top: 15px;
                text-decoration: none;
            }
        </style>
        <script>
            function hideShowPassword() {
                var pass = document.getElementById("pass");
                if (pass.type === "password")
                    pass.type = "text";
                else
                    pass.type = "password";
            }
        </script>
    </head>

    <body>
        <h1>
            &emsp;FOT - Edit Menu
            <a href="../Logout.php">Logout</a>
            <a href="./Dashboard.php">Homepage</a>
        </h1>
        <hr>
        <form method="POST" enctype="multipart/form-data">
            <!-- Brand input -->
            <div class="form-outline mb-3">
                <input type="text" name="txtName" class="form-control" required="" value="<?php echo $name ?>" <?php if (!isset($_POST['btnEdit'])) echo ' disabled'; ?> />
                <label class="form-label" for="form2Example1">Name</label>
            </div>



            <!-- Category input -->
            <div class="form-outline mb-3">
                <select name="selType" class="form-control" required="" <?php if (!isset($_POST['btnEdit'])) echo ' disabled'; ?>>
                    <option hidden>-- Select --</option>
                    <option value="Veg" <?php if ($type == "Veg") echo " selected" ?>>Veg</option>
                    <option value="Non Veg" <?php if ($type == "Non Veg") echo " selected" ?>>Non Veg</option>
                </select>
                <label class="form-label" for="form2Example1">Food Type</label>
            </div>

            <!-- Description input -->
            <div class="form-outline mb-3">
                <textarea name="txtaDescription" class="form-control" required="" rows="3" cols="20" <?php if (!isset($_POST['btnEdit'])) echo ' disabled'; ?>><?php echo $description ?></textarea>
                <label class="form-label" for="form2Example1">Description</label>
            </div>

            <!-- Price input -->
            <div class="form-outline mb-3">
                <input type="text" name="txtPrice" class="form-control" required="" value="<?php echo $price ?>" <?php if (!isset($_POST['btnEdit'])) echo ' disabled'; ?> />
                <label class="form-label" for="form2Example1">Price</label>
            </div>

            <!-- Photo input -->
            <div class="form-outline mb-3">
                <input type="file" name="filePhoto" class="form-control" required="" value="<?php echo $photo ?>" <?php if (!isset($_POST['btnEdit'])) echo ' disabled'; ?> />
                <label class="form-label" for="form2Example1">Photo</label>
            </div>

            <!-- Submit button -->
            <button type="submit" name="btnEdit" class="btn btn-primary btn-block mb-4" <?php if (isset($_POST['btnEdit'])) echo ' disabled'; ?>>Edit</button>
            <button type="submit" name="btnUpd" class="btn btn-primary btn-block mb-4" <?php if (!isset($_POST['btnEdit'])) echo ' disabled'; ?>>Update Profile</button>
        </form>
    </body>

    </html>
<?php
} else
    echo "Please <a href='./AdminLogin.php'>Login</a> first!";
?>