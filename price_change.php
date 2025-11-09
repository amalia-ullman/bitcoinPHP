<?php

$errors = [];

if (isset($_GET['crypto']) && isset($_GET['fiat']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {



    // $timestamp = $price_data->{'prices'}[0][0];
    // $timestamp = intval($timestamp / 1000);
    // var_dump($price_data->{'prices'}[0]);

    if ($_GET['crypto'] == "") {
        $errors[] = "You must select a cryptocurrency";
    }

    if ($_GET['fiat'] == "") {
        $errors[] = "You must select a value for fiat";
    }

    if (empty($errors)) {
        $coingecko_api_url = "https://api.coingecko.com/api/v3/coins/" . $_GET['crypto'] . "/market_chart/range?vs_currency=" . $_GET['fiat'] . "&from=" . $_GET['start_date'] . "&to=" . $_GET['end_date'];
    } else {
        header('HTTP/1.1 422 Unprocessable Content');
    }

    //echo date("Y-m-d", $timestamp);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Coingecko Price Change</title>
</head>

<body class="container">

    <?php require __DIR__ . "/views/_nav.php" ?>

    <form action="price_change.php" method="GET">
        <?php if (!empty($errors)): ?>
            <div>
                <p style="color: red">Please fix the following errors before submitting:</p>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li style="color: red"><?= $e ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>
        <label for="crypto">Select Crypto</label>

        <?php if (!empty($errors)): ?>
            <select name="crypto" id="crypto" required aria-invalid="true">
            <?php else: ?>
                <select name="crypto" id="crypto" required>
                <?php endif ?>
                <option value="">--Please choose an option--</option>
                <option value="bitcoin">Bitcoin</option>
                <option value="ethereum">Ethereum</option>
                <option value="litecoin">Litecoin</option>
                <option value="bitcoin-cash">Bitcoin Cash</option>
                </select>

                <label for="fiat">Select FIAT</label>

                <?php if (!empty($errors)): ?>
                    <select name="fiat" id="fiat" required aria-invalid="true">
                    <?php else: ?>
                        <select name="fiat" id="fiat" required>
                        <?php endif ?>
                        <option value="usd" selected>US Dollar</option>
                        <option value="aud">Australian Dollar</option>
                        <option value="gbp">British Pound</option>
                        <option value="eur">Euro</option>
                        </select>


                        <label for="start">Start Time</label>
                        <input type="date" name="start_date" value="" required>

                        <label for="end">End Time</label>
                        <input type="date" name="end_date" value="<?= date("Y-m-d") ?>" required>

                        <input type="submit" value="ok">

    </form>

    <?php
    if (isset($coingecko_api_url)):

        $price_data = json_decode(file_get_contents($coingecko_api_url));


        foreach ($price_data->{'prices'} as $item) {
            $timestamp = intval($item[0] / 1000);
            $timestamps[] = $timestamp;
            $prices[] = $item[1];
        }
    ?>
        Querying: <code><?= $coingecko_api_url ?></code>



        <div>
            <canvas id="myChart"></canvas>
            <script>
                const ctx = document.getElementById('myChart');
                // echo date("Y-m-d", intval($price_data->{'prices'}[0][0] / 1000))
                // echo json_encode($price_data->{'prices'})
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [<?php foreach ($timestamps as $ts) {
                                        echo date("'Y-m-d-H:i'", $ts) . ",";
                                    } ?>],
                        datasets: [{
                            label: 'price by date (hourly)',
                            data: [<?php foreach ($prices as $p) {
                                        echo $p . ",";
                                    } ?>],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: false
                            }
                        }
                    }
                });
            </script>
        </div>

    <?php endif; ?>

</body>

</html>