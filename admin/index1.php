<!DOCTYPE html>
<html lang="en">
<head>
  <title>Payment Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-3">
  <h2>Online Payment Form</h2>

  <?php
  // Include database connection
  include 'connection.php';

  $id = '';
  $fname = '';
  $lname = '';
  $amount = '';

  // Check if the form is submitted
  if (isset($_POST['fetch_details'])) {
    // Get the ID from the form input
    $id = $_POST['payment_id'];
    
    // Fetch the details based on the entered ID
    $sql = "SELECT id, fname, lname, fintot AS amount FROM payment WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fname = $row['fname'];
        $lname = $row['lname'];
        $amount = $row['amount'];
    } else {
        echo "<p class='text-danger'>No payment records found for the provided ID.</p>";
    }
    
    $stmt->close();
  }

  $con->close();
  ?>

  <form action="" method="POST">
    <div class="mb-3 mt-3">
      <label for="payment_id">Payment ID:</label>
      <input type="text" class="form-control" id="payment_id" placeholder="Enter Payment ID" name="payment_id" value="<?php echo htmlspecialchars($id); ?>" required>
    </div>
    <button type="submit" name="fetch_details" class="btn btn-primary">Fetch Details</button>
  </form>

  <hr>

  <form action="stripe_payment.php" method="POST" name="cardpayment" id="payment-form">
    <div class="mb-3 mt-3">
      <label for="fname">First Name:</label>
      <input type="text" class="form-control" id="fname" placeholder="Enter first name" name="fname" value="<?php echo htmlspecialchars($fname); ?>" readonly>
    </div>
  
    <div class="mb-3 mt-3">
      <label for="lname">Last Name:</label>
      <input type="text" class="form-control" id="lname" placeholder="Enter last name" name="lname" value="<?php echo htmlspecialchars($lname); ?>" readonly>
    </div>
  
    <div class="mb-3 mt-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div>
   
    <div class="mb-3 mt-3">
      <label for="phone">Phone:</label>
      <input type="text" class="form-control" id="phone" placeholder="Enter number" name="phone">
    </div>
    
    <div class="mb-3 mt-3">
      <label for="amount">Fees Amount:</label>
      <input type="text" class="form-control" id="amount" placeholder="Enter amount" name="amount" value="<?php echo htmlspecialchars($amount); ?>" readonly>
    </div>
    
    <div class="row">
      <div class="col-xs-12">
        <div class="form-group">
          <label for="cardNumber">CARD NUMBER</label>
          <div class="input-group">
            <input type="text" class="form-control" name="card_number" placeholder="Valid Card Number" autocomplete="cc-number" id="card_number" maxlength="16" data-stripe="number" required />
            <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
          </div>
        </div>                            
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-4 col-md-4">
        <div class="form-group">
          <label for="card_exp_month"><span class="visible-xs-inline">MON</span></label>
          <select name="card_exp_month" id="card_exp_month" class="form-control" data-stripe="exp_month" required>
            <option>MON</option>
            <option value="01">01 ( JAN )</option>
            <option value="02">02 ( FEB )</option>
            <option value="03">03 ( MAR )</option>
            <option value="04">04 ( APR )</option>
            <option value="05">05 ( MAY )</option>
            <option value="06">06 ( JUN )</option>
            <option value="07">07 ( JUL )</option>
            <option value="08">08 ( AUG )</option>
            <option value="09">09 ( SEP )</option>
            <option value="10">10 ( OCT )</option>
            <option value="11">11 ( NOV )</option>
            <option value="12">12 ( DEC )</option>
          </select>
        </div>
      </div>

      <div class="col-xs-4 col-md-4">
        <div class="form-group">
          <label for="card_exp_year"><span class="visible-xs-inline">YEAR</span></label>
          <select name="card_exp_year" id="card_exp_year" class="form-control" data-stripe="exp_year">
            <option>Year</option>
            <option value="20">2020</option>
            <option value="21">2021</option>
            <option value="22">2022</option>
            <option value="23">2023</option>
            <option value="24">2024</option>
            <option value="25">2025</option>
            <option value="26">2026</option>
            <option value="27">2027</option>
            <option value="28">2028</option>
            <option value="29">2029</option>
            <option value="30">2030</option>
            <option value="31">2031</option>
            <option value="32">2032</option>
            <option value="33">2033</option>
            <option value="34">2034</option>
            <option value="35">2035</option>
          </select>
        </div>
      </div>

      <div class="col-xs-4 col-md-4 pull-right">
        <div class="form-group">
          <label for="card_cvc">CV CODE</label>
          <input type="password" class="form-control" name="card_cvc" placeholder="CVC" autocomplete="cc-csc" id="card_cvc" required />
        </div>
      </div>
    </div>

    <button type="submit" id="payBtn" class="btn btn-primary">Submit</button>
  </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>

<script>
  // Set your publishable key
  Stripe.setPublishableKey('*************************');

  // Callback to handle the response from Stripe
  function stripeResponseHandler(status, response) {
    if (response.error) {
      // Enable the submit button
      $('#payBtn').removeAttr("disabled");
      // Display the errors on the form
      $(".payment-status").html('<p>' + response.error.message + '</p>');
    } else {
      var form$ = $("#payment-form");
      // Get token id
      var token = response.id;
      // Insert the token into the form
      form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
      // Submit form to the server
      form$.get(0).submit();
    }
  }

  $(document).ready(function() {
    // On form submit
    $("#payment-form").submit(function() {
      // Disable the submit button to prevent repeated clicks
      $('#payBtn').attr("disabled", "disabled");
      
      // Create single-use token to charge the user
      Stripe.createToken({
        number: $('#card_number').val(),
        exp_month: $('#card_exp_month').val(),
        exp_year: $('#card_exp_year').val(),
        cvc: $('#card_cvc').val()
      }, stripeResponseHandler);
      
      // Submit from callback
      return false;
    });
  });
</script>

</body>
</html>
