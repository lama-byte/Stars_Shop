<?php
require_once 'config.php';

$cart_items = $cart->getItems();
if (empty($cart_items)) {
    header("Location: cart.php");
    exit();
}
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['place_order'])) {
        
        if (!isLoggedIn()) {
            $message = "Please log in to complete your order";
        } else {
            
            $out_of_stock = [];
            foreach ($cart_items as $productId => $quantity) {
                if (isset($products[$productId])) {
                    $product = $products[$productId];
                    if ($quantity > $product->getStock()) {
                        $out_of_stock[] = $product->getName();
                    }
                }
        }
            if (!empty($out_of_stock)) {
                $message = "Sorry, the following items are out of stock: " . implode(", ", $out_of_stock);
        } else {
                foreach ($cart_items as $productId => $quantity) {
                    if (isset($products[$productId])) {
                        $products[$productId]->reduceStock($quantity);
                    }
                }
                $cart->clear();
                $message = "Order placed successfully! Thank you for your purchase.";
            }
        }
    }
}
$current_user = getCurrentUser();
$totals = $cart->getTotal($products);
$total_quantity = $cart->getTotalQuantity();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="base.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    main {
        background-color: whitesmoke;
        padding: 3em;
        margin: 2em auto;
        width: 80%;
        border-radius: 15px;
    }

    main h1 {
        font-family: "Unbounded", cursive;
        font-weight: 700;
        color: rgb(130, 73, 130);
        font-size: 2.6em;
        text-align: center;
        margin-bottom: 0.5em;
    }

    main h2 {
        font-family: "Unbounded", cursive;
        font-weight: 600;
        color: rgb(130, 73, 130);
        font-size: 1.7em;
        text-align: center;
        margin-bottom: 1.2em;
    }

    table {
        width: 90%;
        margin: 1.5em auto;
        border-collapse: collapse;
        font-family: "Unbounded", cursive;
    }

    table th {
        background-color: rgb(130, 73, 130);
        color: white;
        padding: 12px;
        font-weight: 600;
    }

    table td {
        padding: 15px;
        border-bottom: 2px solid rgb(205, 205, 205);
        text-align: center;
    }

    table img {
        width: 70px;
        border-radius: 10px;
        border: 2px solid lightyellow;
    }

    main section span,
    main section p {
        display: block;
        font-family: "Unbounded", cursive;
        font-size: 1.1em;
        margin: 6px auto;
        text-align: center;
        color: rgb(70, 70, 70);
    }

    main section p:last-child {
        font-weight: 600;
        font-size: 1.3em;
        color: rgb(130, 73, 130);
        margin-top: 10px;
    }


    main section button {
        font-size: 22px;
        font-family: "Lemon", serif;
        cursor: pointer;
        padding: 15px 40px;
        border-radius: 15px;
        border: 2.5px solid rgb(130, 73, 130);
        background-color: transparent;
        color: rgb(130, 73, 130);
        display: block;
        margin: 2em auto 0;
        transition: 0.2s;
    }

    main section button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }
    #summary {
        margin: 5em 10em;
    }

    #summary>span{
        display: flex;
        justify-content: space-around;
        width: 100%;
        font-size: 1.1em;
        font-family: "Unbounded", cursive;
        white-space: nowrap;
    }

    #summary>span>span{
        margin-right: 0;
        width: 100%;
        text-align: right;
        font-weight: 700;
        font-size: 1.1em;
    }

    hr {
        margin: 3em auto;
    }
    </style>
</head>

<body>
    <header>
        <h1 class="logo">⋆⭒˚⋆ Star's Shop</h1>
        <nav class="headerNav">
            <a href="#contactInfo">Contact</a>
            <a href="cart.php"><img src="/resources/cart.png" alt="cart" width="30"></a>
            <a href="home.php"><img src="/resources/exit.png" alt="logout" width="30"></a>
        </nav>
    </header>

    <main>
        <h1>Checkout</h1>
        <br>
        <h2>Your Order is Ready to Checkout!</h2>
        <?php if ($message !== ""): ?>
            <div class="msg <?php echo strpos($message, 'successfully') === false ? 'error' : ''; ?>"> // css 
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <section id="cartTable">
            <table>
            <tr>
                    <th>product pic</th>
                    <th>product name</th>
                    <th>quantity</th>
                    <th>subtotal</th>
            </tr>
        <?php foreach ($cart_items as $productId => $quantity): ?>
            <?php if (isset($products[$productId])): ?>
         <?php $product = $products[$productId]; ?>    
                <tr>
                    <td>
                        <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>" width=150>
                    </td>
                    <td><?php echo $product->getName(); ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td><?php echo formatPrice($product->getPrice() * $quantity); ?></td>
                </tr>
             <?php endif; ?>
             <?php endforeach; ?>
            </table>  
        </section>
        <hr>
        <section id="summary">
            <h2>Order Summary</h2>
            <br>
            <span>Number of Items: <span class="summaryValue"><?php echo $total_quantity; ?></span></span>
            <span>Sub Total: <span class="summaryValue"><?php echo formatPrice($totals['subtotal']); ?></span></span>
            <span>Shipping: <span class="summaryValue"><b>free!</b></span></span>
            <span>tax (15%): <span class="summaryValue"><?php echo formatPrice($totals['tax']); ?></span></span>
            <span><strong>Total:</strong> <span class="summaryValue"><?php echo formatPrice($totals['total']); ?><span></span>
        </section>
                <hr>
        <section id="complete">
            <form method="POST">
                <button>Complete Order</button>
            </form>   
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