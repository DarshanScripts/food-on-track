<?php
session_start();

$email = $pass = "";
if (isset($_POST['btnLogin'])) {

    //    echo "<pre>";
    //    print_r($_POST);
    //    echo"</pre>";

    $email = $_POST['txtEmail'];
    $pass = $_POST['txtPass'];

    //    validations
    //    
    //    email
    //    [gmail|yahoo|icloud][.][com|in]
    if (!preg_match("/^[a-z][a-z0-9]+@(gmail|outlook|hotmail|yahoo|icloud)[.](com|in)$/", $email))
        echo "<script>alert('Email format is incorrect!');</script>";

    //    pass
    elseif (strlen($pass) < 5 || strlen($pass) > 8)
        echo "<script>alert('Length of Password should be between 5 to 8 characters!');</script>";

    else {
        require_once '../Database/DBConnection.php';
        $sql = "SELECT * FROM Customer WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $email, $md5Pass);
        $md5Pass = md5($pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo "<script>alert('Login Successful!');</script>";
            $_SESSION['sesLogUser'] = $row;
            $_SESSION['sesLogin'] = $_POST;
            if (isset($_POST['chkRememberMe'])) {
                setcookie('ckCEmail', $email, time() + 60 * 60 * 24 * 30 * 12); //1 year expiry
                setcookie('ckCPass', $pass, time() + 60 * 60 * 24 * 30 * 12); //1 year expiry
            }
            header('location: ./Homepage.php');
        } else
            echo "<script>alert('Invalid Email or Password!');</script>";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Food On Track</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <h1 align="center">Food On Track Customer Login Form</h1>
    <hr>
    <form method="POST">
        <!-- Email input -->
        <div class="form-outline mb-4">
            <input type="text" name="txtEmail" class="form-control" required="" value="<?php if (isset($_COOKIE['ckCEmail'])) echo $_COOKIE['ckCEmail']; ?>" />
            <label class="form-label" for="form2Example1">Email Address</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
            <input type="password" name="txtPass" class="form-control" required="" value="<?php if (isset($_COOKIE['ckCPass'])) echo $_COOKIE['ckCPass']; ?>" />
            <label class="form-label" for="form2Example2">Password</label>
        </div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
            <div class="col d-flex">
                <!-- Checkbox -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="chkRememberMe" id="form2Example31" checked />
                    <label class="form-check-label" for="form2Example31"> Remember me </label>
                </div>
            </div>


        </div>

        <!-- Submit button -->
        <button type="submit" name="btnLogin" class="btn btn-primary btn-block mb-4">Sign In</button>

        <!-- Register buttons -->
        <div class="text-center">
            <p>Not a member? <a href="./CustReg.php">Register</a></p>
        </div>
    </form>
</body>

</html>