<h1>REST API megoldás</h1>

Mivel a feldat nem tartalmazta, hogy hogyan kell megoldani az API-t, a megküldött feladatba Yii2 framework alkalmaztam, de, hogy ne csak az legyen benne megírtam külön egy egyszerű MVC szemléletű API-t is hozzá, ami ugyanazokat a végzi, mint a framework által adott API.

Használatához a

     manualAPI/

könyvtárat fel kell venni egy virtual-szervernek és a 

     config/params.php-ban
     ---------------------
     'APIServer' => meg kell adni a szerver nevét

Ezzel működik kis az általam írt API.

Az API tartalmaz egy HTTP Basic authentikácit is.
