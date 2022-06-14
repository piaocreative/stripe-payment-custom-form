<?php
namespace Phppot;

use Stripe\Stripe;
use Stripe\WebhookEndpoint;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Config.php';

class StripeService
{

    private $apiKey;

    private $stripeService;

    public function __construct()
    {
        require_once __DIR__ . '/../Config.php';
        $this->apiKey = Config::STRIPE_SECRET_KEY;
        $this->stripeService = new Stripe();
        $this->stripeService->setVerifySslCerts(false);
        $this->stripeService->setApiKey($this->apiKey);
    }

    public function createPaymentIntent($orderReferenceId, $amount, $currency, $email, $customerDetailsArray, $notes, $metaData)
    {
        try {
            $this->stripeService->setApiKey($this->apiKey);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'description' => $notes,
                'shipping' => [
                    'name' => $customerDetailsArray["name"],
                    'address' => [
                        'line1' => $customerDetailsArray["address"],
                        'postal_code' => $customerDetailsArray["postalCode"],
                        'country' => $customerDetailsArray["country"]
                    ]
                ],
                'amount' => $amount * 100,
                'currency' => $currency,
                'payment_method_types' => [
                    'card'
                ],
                'metadata' => $metaData
            ]);
            $output = array(
                "status" => "success",
                "response" => array('orderHash' => $orderReferenceId, 'clientSecret'=>$paymentIntent->client_secret)
            );
        } catch (\Error $e) {
            $output = array(
                "status" => "error",
                "response" => $e->getMessage()
            );
        }
        return $output;
    }


    public function getToken()
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < 17; $i ++) {
            $token .= $codeAlphabet[mt_rand(0, $max)];
        }
        return $token;
    }

}
