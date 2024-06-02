<?php
session_start();

$name = $type = $description = $price = $photo = "";
if (isset($_SESSION['sesLogin']['btnLogin'])) {
    if (isset($_POST['btnAddMenu'])) {
        $name = $_POST['txtName'];
        $type = $_POST['selType'];
        $description = $_POST['txtaDescription'];
        $price = $_POST['txtPrice'];

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
            $sql = "INSERT INTO Menu(name,type,description,price,image) VALUES(?,?,?,?,?);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $name, $type, $description, $price, $fileName);

            $stmt->execute();
            if ($stmt) {
                echo "<script>alert('Menu inserted Successful!');window.location.replace('./Dashboard.php');</script>";
                $dir = "../assets/images/" . $fileName;
                move_uploaded_file($fileTmpName, $dir);
            } else
                echo "<script>alert('Menu not inserting!');</script>";
        }
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Insert Menu | FOT</title>
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
            &emsp;FOT - Insert Menu
            <a href="../Logout.php">Logout</a>
            <a href="./Dashboard.php">Homepage</a>
        </h1>
        <hr>
        <form method="POST" enctype="multipart/form-data">
            <!-- Brand input -->
            <div class="form-outline mb-3">
                <input type="text" name="txtName" class="form-control" required="" value="<?php echo $name ?>" />
                <label class="form-label" for="form2Example1">Name</label>
            </div>

            <!-- Category input -->
            <div class="form-outline mb-3">
                <select name="selType" class="form-control" required="">
                    <option hidden>-- Select --</option>
                    <option value="Veg">Veg</option>
                    <option value="Non Veg">Non Veg</option>
                </select>
                <label class="form-label" for="form2Example1">Type</label>
            </div>

            <!-- Description input -->
            <div class="form-outline mb-3">
                <textarea name="txtaDescription" class="form-control" required="" rows="3" cols="20"><?php echo $description ?></textarea>
                <label class="form-label" for="form2Example1">Description</label>
            </div>

            <!-- Price input -->
            <div class="form-outline mb-3">
                <input type="text" name="txtPrice" class="form-control" required="" value="<?php echo $price ?>" />
                <label class="form-label" for="form2Example1">Price</label>
            </div>

            <!-- Photo input -->
            <div class="form-outline mb-3">
                <input type="file" name="filePhoto" class="form-control" required="" />
                <label class="form-label" for="form2Example1">Photo</label>
            </div>

            <!-- Submit button -->
            <button type="submit" name="btnAddMenu" class="btn btn-primary btn-block mb-4">Add Menu</button>
        </form>
    </body>

    </html>
<?php
} else
    echo "Please <a href='./AdminLogin.php'>Login</a> first!";
?>