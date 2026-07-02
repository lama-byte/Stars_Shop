<?php
require_once 'config.php';

$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "All fields are required";
    } elseif (!validateEmail($email)) {
        $message = "Please enter a valid email address";
    } elseif (isset($users[$email])) {
        $message = "Email already registered";
    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 6 characters";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match";
    } else {
        $_SESSION['loggedin'] = true;
        $_SESSION['user_email'] = $email;
        $_SESSION['user'] = [
            'name' => $first_name . ' ' . $last_name,
            'address' => 'Not provided',
            'phone' => 'Not provided'
        ];
        
        $message = "Registration successful! You can now login.";
        $success = true;

        header("Refresh: 2; URL=catalog.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registeration page - Star's Shop</title>
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="signup.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    main {
        margin: 2em auto;
        background-color: whitesmoke;
        width: 60%;
    }

    #signup {
        width: 90%;
        margin: 0 auto;
        background-color: whitesmoke;
        padding: 2em;
        border-radius: 15px;
    }

    body {
        overflow-x: hidden;
    }

    #signup h2 {
        text-align: center;
        font-family: "Unbounded", sans-serif;
        font-size: 2em;
        font-weight: 600;
        color: rgb(130, 73, 130);
        margin-bottom: 40px;
    }


    form {
        width: 90%;
        margin: auto;
    }

    fieldset {
        border: none;
        margin-bottom: 30px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        gap: 40px;
    }

    .row div,
    #Email {
        width: 95%;
    }

    label {
        font-family: "Unbounded", sans-serif;
        font-size: 1em;
        font-weight: 400;
        color: rgb(80, 80, 80);
        margin-bottom: 8px; 
    }

    input {
        padding: 15px;
        font-size: 0.9em;
        border: 2px solid rgb(130, 73, 130);
        border-radius: 15px;
        font-family: "Unbounded", sans-serif;
        color: rgb(80, 80, 80);
        width: 100%;
        box-sizing: border-box;
    }

    input::placeholder {
        color: rgb(150, 150, 150);
    }

    button {
        display: block;
        margin: 40px auto 0;
        font-size: 20px;
        font-family: "Lemon", serif;
        color: rgb(130, 73, 130);
        background-color: transparent;
        cursor: pointer;
        border: 2.5px solid rgb(130, 73, 130);
        border-radius: 15px;
        padding: 15px 40px;
    }

    button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }

    #signup p {
        text-align: center;
        font-family: "Unbounded", sans-serif;
        margin-top: 20px;
        font-size: 1em;
    }

    #signup p a {
        color: rgb(130, 73, 130);
        font-weight: 600;
        text-decoration: none;
    }
    #signup p a:hover {
        text-decoration: underline;
    }

    .msg {
        font-family: "Unbounded", cursive;
        font-size: 1em;
        margin: 1em 3em;
        color: rgb(62, 141, 57);
        text-align: right;
    }
    </style>
</head>

<body>
    <header>
        <h1>⋆⭒˚⋆ Star's Shop</h1>
        <nav class="headerNav">
            <a href="home.php">Home</a>
            <a href="#contactInfo">Contact</a>
            <a href="login.php">Login</a>
        </nav>
    </header>

    <main>
        <section id="signup">
            <h2>Creat a New Account</h2>
            <?php if ($message !== ""): ?>
                <div class="msg <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
            <?php endif; ?>
            <form methode="post" action= "<?php echo $_SERVER['PHP_SELF']; ?>">
                <fieldset class="row">
                    <div>
                        <label> First name </label>
                        <input type="text" placeholder="Enter you first name" required value="<?php echo $_POST['first_name'] ?? ''; ?>">
                    </div>
                    <div>
                        <label> Last name</label>
                        <input type="text" name="first_name" placeholder="Enter your last name" required value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
                    </div>
                </fieldset>
                <fieldset id="Email">
                    <label> Email address</label> <br>
                    <input type="email" name="email" placeholder="examble@ex.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </fieldset>
                <fieldset class="row">
                    <div>
                        <label> Password</label>
                        <input type="password" placeholder="Enter your password" required>
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input type="password" placeholder="Enter your password again" required>
                    </div>
                </fieldset>
                <button type="submit">Sign up</button>
            </form>
            <p>Already have an account?
                <a href="login.php">login</a>
            </p>
        </section>
    </main>

    <footer id="contactInfo">
        <p><b>Got Any Ideas? We’d love to hear from you!</b></p>
        <a id="Email" href="mailto: support@starsCrochet.com">📧 Email: support@starsCrochet.com</a>
        <a id="Phone number" href="tel: +966-12-345-6789">📞 Phone: +966 12 345 6789</a>
        <a id="Address">📍 Location: Madina, Saudi Arabia</a>
        <br>
        <br>
        <br>
        <p>© 2025 Stars Shop</p>

    </footer>
</body>

</html>