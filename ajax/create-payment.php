<?php
namespace Phppot;

use Phppot\StripeService;
use Phppot\StripePayment;
require_once __DIR__ . '/../config.php';

$content = trim(file_get_contents("php://input"));

$jsondecoded = json_decode($content, true);

if (! empty($jsondecoded)) {
    require_once __DIR__ . "/../lib/StripeService.php";
    $stripeService = new StripeService();

    $email = filter_var($jsondecoded["email"], FILTER_SANITIZE_EMAIL);
    $name = filter_var($jsondecoded["name"], FILTER_SANITIZE_STRING);
    $address = filter_var($jsondecoded["address"], FILTER_SANITIZE_STRING);
    $country = filter_var($jsondecoded["country"], FILTER_SANITIZE_STRING);
    $postalCode = filter_var($jsondecoded["postalCode"], FILTER_SANITIZE_STRING);
    $notes = $jsondecoded["notes"];
    $currency = filter_var($jsondecoded["currency"], FILTER_SANITIZE_STRING);
    $orderReferenceId = $stripeService->getToken();
    $unitPrice = $jsondecoded["unitPrice"];
    $orderStatus = "Pending";
    $paymentType = "stripe";
    $customerDetailsArray = array(
        "email" => $email,
        "name" => $name,
        "address" => $address,
        "country" => $country,
        "postalCode" => $postalCode
    );
    $metaData = array(
        "email" => $email,
        "order_id" => $orderReferenceId
    );

    require_once __DIR__ . '/../lib/StripePayment.php';
    $stripePayment = new StripePayment();

    $orderId = $stripePayment->insertOrder($orderReferenceId, $email, $unitPrice, $currency, $orderStatus, $paymentType, $notes, $name, $address, $country, $postalCode);
    $result = $stripeService->createPaymentIntent($orderReferenceId, $unitPrice, $currency, $email, $customerDetailsArray, $notes, $metaData);

    if (! empty($result) && $result["status"] == "error") {
        http_response_code(500);
    }
    echo json_encode($result["response"]);
    exit();
}