<?php
require_once './database/connection.php';
session_start();
if (isset($_SESSION['user'])) {
    header('location: ./dashboard.php');
}

$email = "";
$errors = [];

if (isset($_POST['submit'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);


    if (empty($email)) {
        $errors['email'] = "Enter your email!";
    }

    if (empty($password)) {
        $errors['password'] = "Enter your password!";
    }

    if (count($errors) === 0) {
        $hashed_password = sha1($password);
        $sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$hashed_password' LIMIT 1";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user;
            // echo "<pre>";
            // print_r($_SESSION['user']);
            // echo "</pre>";
            $success = "Good to go!";
            header('location: ./dashboard.php');
        } else {
            $failure = "Invalid login details!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="text-center">Login</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php require_once './partials/alerts.php' ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?php if (isset($errors['email'])) echo 'is-invalid' ?>" id="email" name="email" value="<?php echo $email ?>" placeholder="Email!">
                                <?php
                                if (isset($errors['email'])) { ?>
                                    <div class="text-danger"><?php echo $errors['email'] ?></div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control <?php if (isset($errors['password'])) echo 'is-invalid' ?>" id="password" name="password" placeholder="Password!">
                                <?php
                                if (isset($errors['password'])) { ?>
                                    <div class="text-danger"><?php echo $errors['password'] ?></div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="mb-3">
                                <input type="submit" name="submit" class="btn btn-primary">
                            </div>

                            <div>
                                Do not have an account? <a href="./register.php">Register</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>