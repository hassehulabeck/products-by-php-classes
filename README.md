# Ett konkret exempel med class i PHP

## Vi bygger en produkt-visningsdel för en webbshop. 
Produkterna är lagrade i en json-fil och kan ha olika egenskaper, vilket gör att vi kommer att ha nytta av en “bas-klass”, eftersom alla produkter ändå har några gemensamma egenskaper (name, price, category).

Däremot kan ju en del egenskaper och även metoder skilja sig åt mellan olika produkter. En digital produkt (exempelvis en strömmad film) har ju inte behov av egenskapen färg eller vikt, något som är nödvändigt för kläder.

Vår kod ska utföra följande:
1. Läsa in värden (produkter) från en fil.
2. För varje produkt ska vi skapa en instans av ett objekt.
3. Egenskaperna för varje objekt ska placeras i lämpliga HTML-element. 
4. Hela produkt-visningsdelen ska renderas på webbsidan.

## JSON-filen
Innehållet ser ut ungefär så här, dvs objekten har flera matchande egenskaper, men också några som skiljer sig åt:
```json
[{
        "name": "Lättmjölk",
        "price": 8.5,
        "category": "dairy"
    },
    {
        "name": "Äppeljuice",
        "price": 23,
        "category": "juice",
        "flavor": "äpple"
    }
]
```
## Läsa in värden från .json
Använd file_get_contents() eftersom include och require ska användas för .php-filer. json_decode används för att omvandla från JSON till en array som PHP kan jobba med.
```php
$products = json_decode(file_get_contents('products.json'));
```

## Skapa en class
I och med att produkterna har flera identiska egenskaper, så gör vi en basklass. Notera att de båda egenskaperna name och price är protected. Det betyder att vi bara kommer åt dem inifrån denna klass - eller en klass som ärver egenskaper från denna. Hade vi använt private så hade de inte kunnat ärvas på samma sätt, och använder vi public så kan vi av misstag komma åt dem utifrån applikationskoden. Börja alltid med private, och om det inte fungerar, så steppa ner till protected.
```php

class Product
{
    protected $name;
    protected $price;
 
    public function __construct($name, $price, $category = null)
    {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

```

Därefter gör vi en class som ärver egenskaper från sin förälder. Här kan vi kosta på oss att använda private för egenskapen flavor, eftersom vi just nu inte har någon tanke på att skapa en klass som ärver från Juice.
```php

class Juice extends Product
{
    private $flavor;
    public function __construct($name, $price, $category, $flavor)
    {
        parent::__construct($name, $price, $category);
        $this->flavor = $flavor;
    }
```
### Här finns några intressanta detaljer:
1. ordet **extends** är det som skapar själva arvet. Det betyder att Juice kommer att vara ett objekt som har alla egenskaper och metoder som Product har.
2. **parent::** pekar på föräldern, vilket i vårt fall innebär att när någon vill skapa ett objekt från Juice-klassen, då görs först ett anrop till förälderns constructor som hanterar egenskaperna name, price och category. Därefter tar barnet vid och lägger till sin specifika egenskap (flavor).
## Skapa objekt från klasserna
Loopa igenom produkterna. I varje loop skapar du ett objekt och använder antingen Product- eller Juice-klassen för att skapa objektet. De nya objekten sparas i en array.
```php
foreach ($products as $product) {
    if ($product->category != "juice") {
        $newProduct = new Product($product->name, $product->price, $product->category);
    } else {
        $newProduct = new Juice($product->name, $product->price, $product->category, $product->flavor);
    }
    $prodObjects[] = $newProduct;
}
```

## Skapa snygg HTML
En metod i Product-klassen gör susen:
```php

    public function getHTML()
    {
        return "<div><h1>" . $this->name . "</h1><p class='price'>" . $this->price . "</p></div>";
    }
```
Vi kan använda oss av denna även för Juice-klassen, men om vi behöver ha olika metoder, så går det bra. Det är bara att skriva en metod med samma namn i Juice-klassen, så skriver den över förälderns fåniga metod.
```php

    public function getHTML()
    {
        return "<div><h1>" . $this->name . "</h1><p class='price'>" . $this->price . "</p><p class='flavor'>" . $this->flavor . "</div>";
    }
```
## Loopa produkter igen
Peta först in lite css någonstans som utnyttjar css-klasserna och andra element, och loopa sedan för att skriva ut alla produkter snyggt och effektivt. Alla produkter som är objekt från klassen Products kommer att anropa sin getHTML-metod, och alla Juice-objekt kommer att anropa sin.
```html
<body>
    <section>
        <?php
        foreach ($prodObjects as $product) {
            echo $product->getHTML();
        }
        ?>
    </section>
</body>
```
Klart. 

