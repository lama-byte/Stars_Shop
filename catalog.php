<?php
require_once 'config.php';
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity'] ?? 1);
    
    if ($quantity > 0 && isset($products[$product_id])) {
        if ($products[$product_id]->reduceStock($quantity)) {
            $cart->addItem($product_id, $quantity);
        }
    }
}
elseif(isset($_POST['remove_from_cart'])){
   $product_id = intval($_POST['product_id']);
        $cart->removeItem($product_id);
    } 

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['loggedin']);

    header("Location: home.php");
    exit;
}

$filtered_products = searchProducts($search, $category);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="base.css">
    <link href="https://fonts.googleapis.com/css2?family=Lemon&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
    .card {
        display: inline-block;
        width: 15%;
        vertical-align: top;
        padding: 1.2em;
        align-items: center;
    }

    .card>img {
        border: 2px solid lightyellow;
        border-radius: 10px;

    }

    .card h3 {
        height: 45px;
        font-family: "Unbounded", cursive;
        font-weight: 400;
        font-size: 15px;
        padding: 0.3em;
        font-style: italic;
    }

    .card span {
        display: block;
        padding: 0.3em;
        border-bottom: 2px solid rebeccapurple;
        pointer-events: none;
    }

    .card p {
        height: 45px;
        font-family: "Unbounded", cursive;
        font-weight: 200;
        font-size: 10px;
        padding: 0.3em;
    }

    .buttons {
        margin: 15px;
        text-align: center;
    }

    .buttons button {
        display: inline-block;
        border: 1px solid rgb(185, 101, 101);
        border-radius: 100px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 22px;
        font-weight: 200;
        padding: 8px 20px;
        text-align: center;
        color: rebeccapurple;
        cursor: pointer;

    }

    .buttons button:hover {
        background-color: rgb(244, 212, 212);
        color: white;
    }

    .buttons form {
        display: inline;
    }


    main {
        display: block;
        padding: 3em;
        align-items: center;
        justify-content: space-between;
        background-color: whitesmoke;
        gap: 40px;
    }

    #productList {
        margin: 1.2em;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
    }

    #intro {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;      
        margin: 2em;
    }

    #intro form {
        display: flex;
        align-items: center;
        gap: 20px;      
    }

    #intro h1 {
        padding: 10px;
        font-family: "Unbounded", Arial, Helvetica, sans-serif;
        font-weight: 700;
        font-style: normal;
        color: rgb(130, 73, 130);
        font-size: 2em;
    }

    #cartBtn {
        margin: 2em;
        justify-content: right;    
    }

    #cartBtn button {
        size: 3em;
        font-size: 20px;
        font-family: "Lemon", serif;
        color: rgb(130, 73, 130);
        cursor: pointer;
        border: 2.5px  solid rgb(130, 73, 130);
        border-radius: 15px;
        padding: 15px 30px ;
        justify-content: right;
    }

    #cartBtn button:hover{
        background-color: rgb(244, 212, 212);
        color: white;
    }

    select {
        padding: 15px;
        font-size: 1em;
        border: 3px solid rgb(130, 73, 130);
        border-radius: 15px;
        font-family: "Unbounded", sans-serif;
        color: rgb(80, 80, 80);
        width: 75%;
    }

    select + button {
        padding: 0;
        border: 3px solid rgb(130, 73, 130);
        border-radius: 15px;
        font-family: "Unbounded", sans-serif;
        color: rgb(80, 80, 80);
    }
    </style>
</head>

<body>
    <header>
        <h1 class="logo">⋆⭒˚⋆ Star's Shop</h1>
        <nav class="headerNav">
            <a href="#productList">Products</a>
            <a href="#contactInfo">Contact</a>
            <a href="cart.php"><img src="resources/cart.png" alt="cart" width="30"></a>
            <a href="catalog.php?logout=1"><img src="resources/exit.png" alt="logout" width="30"></a>
        </nav>
    </header>

    <main>
        <section id="intro">
            <form method="GET">
            <h1>Available Products</h1>
            <!-- <input id="searchBar" type="text" name="serach"
             placeholder="search for a product" 
             value="<?php echo htmlspecialchars($search); ?>"> -->

             <select class="filter" name="category">
                    <option value="">All Categories</option>
                    <option value="crochet" <?php echo $category === 'crochet' ? 'selected' : ''; ?>>Crochet</option>
                    <option value="accessories" <?php echo $category === 'accessories' ? 'selected' : ''; ?>>Accessories</option>
                </select>

            <button class="filter" type="submit"><img src="resources/search-icon.png" alt="search icon" width="45"></button>

            <?php if (!empty($search) || !empty($category)): ?>
                    <a href="catalog.php" style="margin-left: 10px;">Clear Filters</a>
                <?php endif; ?>
            </form>
        </section>

        <?php if (!empty($search) || !empty($category)): ?>
            <div >
                <?php if (!empty($search)): ?>
                    <p>Search: <strong>"<?php echo htmlspecialchars($search); ?>"</strong></p>
                <?php endif; ?>
                <?php if (!empty($category)): ?>
                    <p>Category: <strong><?php echo ucfirst($category); ?></strong></p>
                <?php endif; ?>
                <p>Found: <strong><?php echo count($filtered_products); ?> products</strong></p>
            </div>
        <?php endif; ?>

        <div id="productList">
            <br>
            <section class="row">
                <?php for($i = 0; $i < min(10, count($filtered_products)); $i++): ?>
                <?php if(isset($filtered_products[$i])): ?>
                <?php $product = $filtered_products[$i]; ?>
                <section class="card">
                    <img src="<?php echo $product->getImage(); ?>" alt="<?php echo $product->getName(); ?>" width=150>
                    <h3><?php echo $product->getName(); ?></h3>
                    <span><?php echo formatPrice($product->getPrice()); ?></span>
                    <p><?php echo $product->getDescription(); ?></p>

                    <div class="buttons">
                    <form method="POST" >
                        <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" name="add_to_cart">+</button>
                    </form>
                    <form method="POST" >
                     <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                    <button type="submit" name="remove_from_cart">-</button>
                    </form>
                    </div>
                </section>
                    <?php endif; ?>
                <?php endfor; ?>
            </section>
        </div>

        <section id="cartBtn">
            <a href="cart.php">
                <button>View Shopping Cart (<?php echo $cart->getTotalQuantity(); ?>) </button>
            </a>
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
        <p>©️ 2025 Stars Shop</p>

    </footer>
</body>

</html>