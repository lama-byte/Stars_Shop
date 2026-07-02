<?php
require_once 'config.php';

if (isLoggedIn()) {
    header("Location: catalog.php");
    exit();
}

$message = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home page</title>
    <link rel="stylesheet" href="base.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        
    #welcome {
        width: 60%;
        display: inline-block;
        margin: 15px;
    }
        
    main {
        display: flex;
        padding: 3em;
        align-items: center;
        justify-content: space-between;
        background-color: whitesmoke;
        gap: 40px;
    }

    main h1 {
        font-family: "Pacifico", cursive;
        font-size: 3.8em;
        font-weight: 400;
        font-style: normal;
        color: rgb(130, 73, 130);
    }

    main p {
        font-family: "Unbounded", cursive;
        font-size: 1.2em;
        font-weight: 50;
        font-style: normal;
    }
    main a {
        display: block;
        margin: 20px auto 0;
    }

    main a>button {
        size: 3em;
        font-size: 20px;
        font-family: "Lemon", serif;
        color: rgb(234, 183, 14);
        cursor: pointer;
        border: 2.5px  solid rgb(234, 183, 14);
        border-radius: 15px;
        padding: 15px 30px ;
    }

    main a>button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }
    </style>
</head>

<body>
    <header>
        <h1 class="logo">⋆⭒˚⋆ Star's Shop</h1>
        <nav class="headerNav">
            <a href="home.php">Home</a>
            <a href="#contactInfo">Contact</a>
            <a href="login.php">Login</a>
            <a href="Signup.php">Signup</a>
        </nav>
    </header>
    <main>
        <section id="welcome">
            <p>Welcome to</p>
            <h1>Star's Crochet Shop!</h1>
            <p>Here you can find whatever you want for you and for your loved once &lt;3</p>
            <?php if ($message !== ""): ?>
                <div class="message">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <a href="login.php">
                <br>
            <button>Start Shopping Now!</button>
        </a>
        </section>
        <img src="resources/toro.png" alt="toro Welcome" width="330">
        
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