<?php

//echo file_get_contents($url);
// $file = "new.txt";
// $stringdata = "hi";
//file_put_contents("new.txt", "hi");
date_default_timezone_set('America/Los_Angeles');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <title>Coingecko Playground</title>
</head>

<body class="container">

    <?php require __DIR__ . "/views/_nav.php" ?>


    <form action="index.php" method="GET">
        <label for="crypto">Select Crypto</label>
        <select name="crypto" id="cyrpto">
            <option value="">--Please choose an option--</option>
            <option value="bitcoin">Bitcoin</option>
            <option value="ethereum">Ethereum</option>
            <option value="litecoin">Litecoin</option>
            <option value="bitcoin-cash">Bitcoin Cash</option>
        </select>

        <label for="fiat">Select FIAT</label>
        <select name="fiat" id="fiat">
            <option value="usd" selected>US Dollar</option>
            <option value="aud">Australian Dollar</option>
            <option value="gbp">British Pound</option>
            <option value="eur">Euro</option>
        </select>

        <input type="submit" value="Get Price">
    </form>

    <table>
        <tr>
            <th>Crypto</th>
            <th>FIAT</th>
            <th>Value</th>
            <th>Timestamp</th>
        </tr>
        <?php
        $queries_log = file_get_contents("data/recent_prices.log");
        $queries_log_lines = explode("\n", $queries_log);

        foreach ($queries_log_lines as $search) {
            echo "<tr>";

            $search_parts = explode('"', $search);
            if (!isset($search_parts[1])) {
                break;
            }
            echo "<td>" . $search_parts[1] . "</td>";
            echo "<td>" . $search_parts[3] . "</td>";
            echo "<td>" . substr($search_parts[4], 1, -2) . "</td>";
            echo "<td>" . $search_parts[7] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>



    <p>
        <?php
        if (isset($_GET['crypto']) && isset($_GET['fiat'])) {
            $url = "https://api.coingecko.com/api/v3/simple/price?ids=" . htmlspecialchars($_GET['crypto']) . "&vs_currencies=" . htmlspecialchars($_GET['fiat']);
            $res = file_get_contents($url);
            $data = json_decode($res);
            $data->timestamp = date("Y-m-d H:i:s");
            file_put_contents("urls.txt", $url . "\n", FILE_APPEND);
            file_put_contents("data/recent_prices.log", json_encode($data) . "\n", FILE_APPEND);
        }
        if (!isset($_GET['crypto'])) {
            echo "Please select a cryptocurrency";
        }
        ?>
    </p>
</body>

</html>