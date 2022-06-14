<?php
namespace Phppot;

use Phppot\DataSource;

class StripePayment
{

    private $ds;

    function __construct()
    {
        require_once __DIR__ . "/../lib/DataSource.php";
        $this->ds = new DataSource();
    }

    public function insertOrder($orderReferenceId, $email, $unitAmount, $currency, $orderStatus, $paymentType, $notes, $name, $address, $country, $postalCode)
    {
        $order_date = date("Y-m-d H:i:s");
        $insertQuery = "INSERT INTO tbl_payment(order_hash, payer_email, amount, currency, payment_type , order_date, order_status, notes, name, address, country, postal_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

        $paramValue = array(
            $orderReferenceId,
            $email,
            $unitAmount,
            $currency,
            $paymentType,
            $order_date,
            $orderStatus,
            $notes,
            $name,
            $address,
            $country,
            $postalCode
        );

        $paramType = "ssdsssssssss";
        $insertId = $this->ds->execute($insertQuery, $paramType, $paramValue);
        return $insertId;
    }

    public function updateOrder($paymentIntentId, $orderId, $orderStatus, $paymentStatus, $stripePaymentStatus, $stripeResponse)
    {
        $query = "UPDATE tbl_payment SET stripe_payment_intent_id = ?, payment_status = ?, order_status = ?, stripe_payment_status = ? stripe_payment_response = ? WHERE order_hash = ?";

        $paramValue = array(
            $paymentIntentId,
            $paymentStatus,
            $orderStatus,
            $stripePaymentStatus,
            $stripeResponse,
            $orderId
        );

        $paramType = "sssss";
        $this->ds->execute($query, $paramType, $paramValue);
    }
}
?>