<?php
$url = "https://api.coingecko.com/api/v3/search?query=";
if (isset($_GET['query'])) {
    $result = file_get_contents($url . $_GET['query']);
    file_put_contents("searchResult.txt", $result);
    $obj = json_decode(file_get_contents("searchResult.txt"));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Search</title>
</head>

<body>
    <?php require __DIR__ . "/views/_nav.php" ?>
    <form action="search.php" method="GET">
        <label for="query">Search Currencies</label>
        <input type="text" id="query" name="query">
        <input type="submit" value="Search">
    </form>

    <?php if (isset($result)) : ?>
        <p><code><?= $result; ?></code></p>
    <?php endif ?>

</body>

</html>