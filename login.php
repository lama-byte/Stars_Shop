<?php
require_once 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    }

    elseif (!isset($users[$email])) {
        $error = "Email not found";
    }
    
    elseif ($users[$email]['password'] !== $password) {
        $error = "Incorrect password";
    }
    else {
        $_SESSION['user'] = $users[$email];
        $_SESSION['loggedin'] = true;
        header("Location: home.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="base.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    main {
        margin: 2em auto;
        background-color: whitesmoke;
        padding: 3em;
        width: 60%;
        border-radius: 15px;
        text-align: center;
    }

    #login h2 {
        font-family: "Unbounded", cursive;
        font-weight: 700;
        color: rgb(130, 73, 130);
        font-size: 2em;
        margin: 40px auto;
    }

    #loginForm fieldset {
        border: none;
        margin-bottom: 1.5em;
    }

    #loginForm label {
        display: block;
        font-family: "Unbounded", cursive;
        font-size: 1em;
        margin: 200px 0 30px 0;
        color: rgb(90, 90, 90);

    }

    #loginForm input {
        width: 70%;
        padding: 12px;
        border-radius: 10px;
        border: 2px solid rgb(130, 73, 130);
        font-size: 1em;
        font-family: "Unbounded", cursive;
        outline: none;
    }

    #loginForm input:focus {
        border-color: rgb(185, 140, 185);
        background-color: rgb(252, 244, 255);
    }

    #loginForm button {
        padding: 15px 40px;
        background-color: transparent;
        border-radius: 15px;
        border: 2.5px solid rgb(130, 73, 130);
        font-size: 20px;
        font-family: "Lemon", serif;
        color: rgb(130, 73, 130);
        cursor: pointer;
        transition: 0.2s;
    }

    #loginForm button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }


    #login p {
        font-family: "Unbounded", cursive;
        margin-top: 1.5em;
    }

    #login a {
        color: rgb(130, 73, 130);
        text-decoration: underline;
        font-weight: 600;
    }

    #loginForm label {
        text-align: left;
        width: 70%; 
        margin: 10px auto 5px;
    }

    .error {
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
            <a href="Signup.php">SignUp</a>
        </nav>
    </header>
    <main>
        <?php if ($error): ?>
            <div class="error"> <?php echo $error; ?></div>
        <?php endif; ?>
        <section id="login">
        <h2>Welcome Back!</h2>
        <form id="loginForm" method="POST" action="login.php">
            <fieldset>
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" placeholder="enter you email address" required value="<?php echo $_POST['email'] ?? ''; ?>" >
                <br>
                <br>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Enter your password" required>
            </fieldset>
            <br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up</a></p> <!-- صفحة signup-->
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