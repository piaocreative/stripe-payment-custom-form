<?php
namespace Phppot;

use Stripe\Stripe;
use Stripe\WebhookEndpoint;
require_once __DIR__ . '/../vendor/autoload.php';

use Phppot\StripePayment;
\Stripe\Stripe::setApiKey('CLIENT_SECRET_KEY_HERE');

$json = file_get_contents("php://input");
$file = fopen("app.log", "a");

fwrite($file, $json);
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;
try {
    $event = \Stripe\Webhook::constructEvent($json, $sig_header, "WEBHOOK_SECRET_HERE");
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

if (! empty($event)) {

    $eventType = $event->type;
    fwrite($file, $event);

    $orderId = $event->data->object->metadata->order_id;
    $email = $event->data->object->metadata->email;
    $paymentIntentId = $event->data->object->id;
    $amount = $event->data->object->amount;
    $stripePaymentStatus = $event->data->object->status;

    if ($eventType == "payment_intent.payment_failed") {
        $orderStatus = 'Payement Failure';

        $paymentStatus = 'Unpaid';

        $amount = $amount / 100;

        require_once __DIR__ . '/../lib/StripePayment.php';
        $stripePayment = new StripePayment();

        $stripePayment->updateOrder($paymentIntentId, $orderId, $orderStatus, $paymentStatus, $stripePaymentStatus, $event);
    }
    if ($eventType == "payment_intent.succeeded") {
        /*
         * Json values assign to php variables
         *
         */
        $orderStatus = 'Completed';

        $paymentStatus = 'Paid';

        $amount = $amount / 100;

        require_once __DIR__ . '/../lib/StripePayment.php';
        $stripePayment = new StripePayment();

        $stripePayment->updateOrder($paymentIntentId, $orderId, $orderStatus, $paymentStatus, $stripePaymentStatus, $event);

        http_response_code(200);
    }
}

?>