<html>
<head>
<link href="./assets/css/style.css" rel="stylesheet" type="text/css"/ >
</head>
<body>
    <?php if(!empty($successMessage)) { ?>
    <div id="success-message"><?php echo $successMessage; ?></div>
    <?php  } ?>
    <div id="error-message"></div>
<html>
<body>
<?php  require_once __DIR__ . "/payment-form-ui.php"; ?>
</body>
</html>