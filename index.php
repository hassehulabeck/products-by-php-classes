<?php
// Slå på all felrapportering. Bra under utveckling, dåligt i produktion.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'product.class.php';
include_once 'juice.class.php';

$dbh = new PDO('mysql:host=localhost;dbname=drinks;charset=UTF8', 'drinkAdmin', "br0mmabl0cks");

$sth = $dbh->prepare("SELECT * FROM breakfast_drinks");
$sth->execute();
$sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Product');
$result = $sth->fetchAll();

echo "<pre>";
var_dump($result);
echo "</pre>";

// Två variabler för att lagra alla resp en produkt.
$prodObjects = array();
$newProduct;

// Loopa och skapa objekt.
foreach ($products as $product) {
    if ($product->category != "juice") {
        $newProduct = new Product($product->name, $product->price, $product->category);
    } else {
        $newProduct = new Juice($product->name, $product->price, $product->category, $product->flavor);
    }
    $prodObjects[] = $newProduct;
}


// Ett test för att se att skapandet har fungerat.
// Visa priset, ändra priset, visa priset igen.
echo "Nuvarande pris: " . $prodObjects[5]->getPrice();
echo "<p>Ändrar nu priset till 12.</p>";
$prodObjects[5]->setPrice(12);
echo "Nytt pris: " . $prodObjects[5]->getPrice() . "</p>";

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkter</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Våra produkter</h1>
    <section>
        <?php
        foreach ($prodObjects as $product) {
            echo $product->getHTML();
        }
        ?>
    </section>
</body>

</html>