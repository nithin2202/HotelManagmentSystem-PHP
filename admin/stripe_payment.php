<?php
include "connection.php";

$payment_id = $statusMsg = '';
$ordStatus = 'error';

// Check whether stripe token is not empty
if (!empty($_POST['stripeToken'])) {
    // Get Token, Card and User Info from Form
    $token = $_POST['stripeToken'];
    $fname = isset($_POST['fname']) ? $_POST['fname'] : '';
    $lname = isset($_POST['lname']) ? $_POST['lname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $card_no = isset($_POST['card_number']) ? $_POST['card_number'] : '';
    $card_cvc = isset($_POST['card_cvc']) ? $_POST['card_cvc'] : '';
    $card_exp_month = isset($_POST['card_exp_month']) ? $_POST['card_exp_month'] : '';
    $card_exp_year = isset($_POST['card_exp_year']) ? $_POST['card_exp_year'] : '';
    $price = isset($_POST['amount']) ? $_POST['amount'] : '';

    // Include STRIPE PHP Library
    require_once('stripe-php/init.php');

    // Set your Stripe API keys
    $stripe = array(
        "SecretKey" => "**************************************************",
        "PublishableKey" => "*****************************************************"
    );

    \Stripe\Stripe::setApiKey($stripe['SecretKey']);

    // Create a customer in Stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $email,
        'source' => $token,
        'name' => $fname, // Use first name only
        'description' => $phone
    ));

    // Convert price to cents
    $itemPrice = $price * 100; // Stripe requires amount in cents
    $currency = 'inr'; // You can set the currency here

    // Charge the customer
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount' => $itemPrice,
        'currency' => $currency,
        'description' => 'Hotel Payment',
        'metadata' => array(
            'order_id' => uniqid()
        )
    ));

    // Handle charge response
    $chargeJson = $charge->jsonSerialize();

    if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1) {
        $transactionID = $chargeJson['id'];
        $paidAmount = $chargeJson['amount'] / 100; // Convert back to dollars
        $payment_status = $chargeJson['status'];
        $payment_date = date("Y-m-d H:i:s");

        // Insert transaction data into the registration table
        $sql = "INSERT INTO registration (fname, lname, email, phone, amount, paymentid, status, added_date) 
                VALUES ('$fname', '$lname', '$email', '$phone', '$price', '$transactionID', '$payment_status', '$payment_date')";
        mysqli_query($con, $sql);

        // If payment is successful
        if ($payment_status == 'succeeded') {
            $ordStatus = 'success';
            $statusMsg = 'Your payment has been successful!';
        } else {
            $statusMsg = "Your payment has failed!";
        }
    } else {
        $statusMsg = "Transaction has been failed!";
    }
} else {
    $statusMsg = "Your payment has been successful!.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stripe Payment Gateway Integration in PHP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/stripe.css">
</head>
<body>
    <div class="container">
        <h2 style="text-align: center; color: blue;">Stripe Payment Gateway Integration in PHP</h2>
        <h4 style="text-align: center;">This is the Stripe Payment Success URL</h4>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="status">
                    <h1 class="<?php echo $ordStatus; ?>"><?php echo $statusMsg; ?></h1>
                    <br>
                    <?php if ($ordStatus == 'success') : ?>
                        <h4 class="heading">Payment Information</h4>
                        <p><strong>Transaction ID:</strong> <?php echo $transactionID; ?></p>
                        <p><strong>Paid Amount:</strong> INR <?php echo number_format($paidAmount, 2); ?></p>
                        <p><strong>Payment Status:</strong> <?php echo $payment_status; ?></p>
                        <br>
                        <h4 class="heading">Product Information</h4>
                        <p><strong>Name:</strong> <?php echo $fname; ?></p>
                        <p><strong>Last Name:</strong> <?php echo $lname; ?></p>
                        <p><strong>Price:</strong> INR <?php echo number_format($price, 2); ?></p>
                    <?php endif; ?>
                </div>
                <a href="index1.php" class="btn-continue">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
