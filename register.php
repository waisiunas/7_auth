<?php
require_once './database/connection.php';

$name = $email = "";
$errors = [];

if (isset($_POST['submit'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $password_confirmation = htmlspecialchars($_POST['password_confirmation']);

    if (empty($name)) {
        $errors['name'] = "Enter your name!";
    }

    if (empty($email)) {
        $errors['email'] = "Enter your email!";
    }

    if (empty($password)) {
        $errors['password'] = "Enter your password!";
    }

    if ($password !== $password_confirmation) {
        $errors['password'] = "Your password does not match!";
    }



    if (count($errors) === 0) {
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows === 0) {
            $hashed_password = sha1($password);
            $sql = "INSERT INTO `users`(`name`, `email`, `password`) VALUES ('$name','$email','$hashed_password')";
            if ($conn->query($sql)) {
                $success = "Magic has been spelled!";
                $name = $email = "";
            } else {
                $failure = "Magic has failed to spell!";
            }
        } else {
            $errors['email'] = "Email already exists!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="text-center">Register</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php require_once './partials/alerts.php' ?>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control <?php if (isset($errors['name'])) echo 'is-invalid' ?>" id="name" name="name" value="<?php echo $name ?>" placeholder="Name!">
                                <?php
                                if (isset($errors['name'])) { ?>
                                    <div class="text-danger"><?php echo $errors['name'] ?></div>
                                <?php
                                }
                                ?>
                            </div>

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
                                <label for="password_confirmation" class="form-label">Password Confirmation</label>
                                <input type="password" class="form-control <?php if (isset($errors['password_confirmation'])) echo 'is-invalid' ?>" id="password_confirmation" name="password_confirmation" placeholder="Password Confirmation!">
                                <?php
                                if (isset($errors['password_confirmation'])) { ?>
                                    <div class="text-danger"><?php echo $errors['password_confirmation'] ?></div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="mb-3">
                                <input type="submit" name="submit" class="btn btn-primary">
                            </div>

                            <div>
                                Already have an account? <a href="./">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>