<?php
require_once 'config.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clear_cart'])) {
        $cart->clear();
        $_SESSION['message'] = "Cart cleared successfully";

        // Redirect after POST to avoid form resubmission popup
        header("Location: cart.php");
        exit;
    }
    elseif (isset($_POST['increase'])) {
        $productId = (int)$_POST['product_id'];
        $currentQty = $cart->getItems()[$productId] ?? 0;
        $cart->updateQuantity($productId, $currentQty + 1);
        $_SESSION['message'] = "Quantity increased ";

        // Redirect after POST to avoid form resubmission popup
        header("Location: cart.php");
        exit;
    }
    elseif (isset($_POST['decrease'])) {
        $productId = (int)$_POST['product_id'];
        $currentQty = $cart->getItems()[$productId] ?? 0;
        if ($currentQty > 1) {
            $cart->updateQuantity($productId, $currentQty - 1);
            $_SESSION['message'] = "Quantity decreased ";
        } else {
            $cart->removeItem($productId);
            $_SESSION['message'] = "Item Removed ";
        }
        // Redirect after POST to avoid form resubmission popup
        header("Location: cart.php");
        exit;
    }
}

$cart_items = $cart->getItems();
$total_quantity = $cart->getTotalQuantity();

//  Subtotal
$subtotal = 0;
foreach ($cart_items as $productId => $quantity) {
    if (isset($products[$productId])) {
        $subtotal += $products[$productId]->getPrice() * $quantity;
    }
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['loggedin']);

    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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

    main h2 {
        font-family: "Unbounded", cursive;
        font-weight: 700;
        color: rgb(130, 73, 130);
        font-size: 2.2em;
        text-align: center;
        margin-bottom: 1.5em;
    }

    main section {
        text-align: center;
        margin: 2em auto;
    }

    main section p {
        font-family: "Unbounded", cursive;
        font-size: 1.1em;
        color: rgb(80, 80, 80);
        margin: 10px 0;
    }

    #server_msg {
        font-family: "Unbounded", cursive;
        font-size: 1em;
        margin: 1em 3em;
        color: rgb(62, 141, 57);
        text-align: right;
    }

    main section p:last-child {
        font-weight: 600;
    }

    main section button {
        font-size: 20px;
        font-family: "Lemon", serif;
        cursor: pointer;
        padding: 15px 40px;
        border-radius: 15px;
        border: 2.5px solid rgb(130, 73, 130);
        color: rgb(130, 73, 130);
        background-color: transparent;
        transition: 0.2s;
    }

    main section button:hover:enabled {
        background-color: rgb(244, 212, 212);
        color: white;
    }

    main section button:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    table {
        width: 90%;
        margin: 2em auto;
        border-collapse: collapse;
        font-family: "Unbounded", cursive;
    }

    table th {
        background-color: rgb(130, 73, 130);
        color: white;
        padding: 12px;
        font-weight: 600;
        font-size: 1em;
        text-align: left;
    }

    table td {
        padding: 15px;
        border-bottom: 2px solid rgb(205, 205, 205);
        text-align: left;
        font-size: 0.9em;

    }

    table img{
        width: 70px;
        border-radius: 10px;
        border: 2px solid lightyellow;
        margin-bottom: 5px;
    }

    table span {
    display: flex;
    }


    table .quantity-btn {
        border: 1px solid rgb(130, 73, 130);
        padding: 5px 12px;
        background-color: white;
        color: rgb(130, 73, 130);
        cursor: pointer;
        font-size: 18px;
    }

    table button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }

    hr {
        margin-top: 3em;
    }

    #lastBtns {
        display: flex;
        margin: 4em 3em;
        justify-content: space-between;
    }

    </style>
</head>
<body>
    <header>
        <h1 class="logo">⋆⭒˚⋆ Star's Shop</h1>
        <nav class="headerNav">
            <a href="catalog.php">Products</a>
            <a href="#contactInfo">Contact</a>
            <a href="cart.php"><img src="/resources/cart.png" alt="cart" width="30"></a>
            <a href="cart.php?logout=1"><img src="/resources/exit.png" alt="logout" width="30"></a>
        </nav>
    </header>

    <main>
        <h2>Shopping Cart</h2>
        <?php if (isset($_SESSION['message'])): ?>
        <p id="server_msg"><?php echo $_SESSION['message']; ?></p>
        <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <section id="cartStatus">
            <?php if (empty($cart_items)): ?>
            <p>Your cart is empty :(</p>
            <?php else: ?>
                <table>
                <tr>
                    <th>product</th>
                    <th>price</th>
                    <th>quantity</th>
                    <th>subtotal</th>
                </tr>
                <?php foreach ($cart_items as $productId => $quantity): ?>
                        <?php if (isset($products[$productId])): ?>
                            <?php $product = $products[$productId]; ?>
                <tr>
                    <td>
                        <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>" width=150>
                        <span><?php echo $product->getName(); ?></span>
                    </td>
                    <td><?php echo formatPrice($product->getPrice()); ?></td>
                    <td>
                        <form method="POST">
                             <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                              <button type="submit" name="decrease" class="quantity-btn">-</button>
                                <input type="number" 
                                  name="quantity[<?php echo $productId; ?>]" 
                                    value="<?php echo $quantity; ?>" 
                                    min="1" 
                                  max="<?php echo $product->getStock(); ?>"
                               class="quantity-input">
                             <button type="submit" name="increase" class="quantity-btn">+</button>
                        </form>
                    </td>
                    <td><?php echo formatPrice($product->getPrice() * $quantity); ?></td>
                </tr>
                  <?php endif; ?>
                    <?php endforeach; ?>
                
            </table>  
        
            <p>total number of items: <?php echo $total_quantity; ?></p> 
            <section id="lastBtns">
            <form method="POST">
            <button type="submit" name="clear_cart" >Clear Cart</button>
        </form>
            <?php endif; ?>

            <?php if ($total_quantity > 0): ?>
            <a href="checkout.php">
                <button>Proceed to Checkout</button>
            </a>
            <?php else: ?>
            <button disabled>Proceed to Checkout</button>
            <?php endif; ?>
            </section>
        </section>
        <hr>
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