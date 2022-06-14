<?php
namespace Phppot;

require_once __DIR__ . '/Config.php';

if (! empty($_GET["orderId"])) {
    $orderId = $_GET["orderId"];
}
?>
<html>
<title>Pay with credit card</title>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,follow" />
<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, Roboto, Segoe UI,
        Helvetica Neue, Helvetica, Arial, sans-serif;
    margin: 0 auto;
    -webkit-font-smoothing: antialiased;
    box-sizing: border-box;
    color: #2f2f2f;
}

.phppot-container {
    width: 760px;
    margin: 20px auto 40px auto;
    padding: 0px 20px 20px 20px;
    text-align: center;
}

.success {
    width: 100%;
    background: #adebbb;
    padding: 8px;
    margin-top: 80px;
    box-sizing: border-box;
}

.checkout-footer {
    border-top: 1px #ddd solid;
    text-align: center;
    color: #888;
    margin: 20px 0 10px 0;
    padding: 20px 0 20px 0;
    clear: both;
}

.small-info {
    font-size: .8em;
    color: #565656;
}

h1 {
    margin: 0 0 10px 0;
    font-weight: 500;
    font-size: 1.7em;
}

#site-logo {
    vertical-align: middle;
    padding: 12px 8px 12px 0px;
}

@media all and (max-width: 780px) {
    .phppot-container {
        width: auto;
    }
}

@media all and (max-width: 400px) {
    .phppot-container {
        padding: 0px 20px;
    }
    .phppot-container h1 {
        font-size: 1.2em;
    }
}
</style>
</head>
<body>
    <div class="phppot-container">

        <div class="row">

            <div class="success">Thank you for the submission.
            Your reference ID is <?php echo $orderId;?>d. I will get
            back to you shortly.</div>
        </div>
    </div>
    <div class="checkout-footer small-info">
        <div class="content"></div>
    </div>
</body>
</html>