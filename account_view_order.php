<?php include '../view/header.php'; ?>
<?php include '../view/sidebar.php'; ?>


<?php
include '../testPHPMailer/class.phpmailer.php';

function sendEmail($to_address, $to_name, $from_address, $from_name,
        $subject, $body, $is_body_html = false) {
      if (!valid_email($to_address)){
          throw new Exeption('This To address is invalid: '.
                  htmlspecialchars($to_address));
      }
      if (!valid_email($from_address)) {
          throw new Exception('This From address is invalid: ' . 
                  htmlspecialchars($from_address));
      }
        }

    if(isset($_POST['sendEmail'])){
        $email = new PHPMailer();

$email->IsSMTP();

// The XXXXXXXX in the following will need to be adjusted as with Host if not using gmail
// $email->IsSendmail();
$email->Host       = "smtp.gmail.com";   //Will need to be modified
$email->SMTPAuth   = true;  
 //$email->Port       = 465;                // The PORTS will vary
$email->Port       = 587;
$email->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)  
$email->SMTPSecure = 'tls';            // ssl is most recent but may need tls 
//$email->SMTPSecure = 'ssl';    
$email->Username   = "mejiaamaby@gmail.com"; // SMTP account username Will need to be modified
$email->Password   = "278548Am!";        // SMTP account password Will need to be modified



$email->SetFrom('mejiaamaby@gmail.com', '278548Am!');  //Will need to be modified – identifies email of sender

// $email->MsgHTML($message);
$email->SingleTo  = true;	// true allows that only one person will receive an email per array group
$email->From      = 'mejiaamaby@gmail.com'; //Will need to be modified – identifies email of sender

$email->FromName  = 'Amaby'; //Will need to be modified – identifies email of sender
$email->Subject   = 'Purchases from Guitar Shop PHP Class'; // appears in subject of email
$email->Body      = $messageHTML ;  // the body will interpret HTML - $messageHTML identified above
$email->AltBody = $message;            // the AltBody will not interpret HTML - $message identified above
$destination_email_address = 'amejia@smartweb.augustatech.edu'; // Destination address
$destination_user_name = 'Amaby Mejia'; // Destination name

// $email->AddAddress( 'xxxx@xxxx.xxx' );

//$file_to_attach = 'images.pdf';  // Used if you want attachments to email - This is the name of your attachment file.
                                 // File has to be located in same folder as index.php
//$email->AddAttachment( $file_to_attach ); // Used if you want attachments to email

       //$email->AddAddress($this->$destination_email_address, $destination_user_name); 
       $email->AddAddress($destination_email_address, $destination_user_name);
	// AddAddress method identifies destination and sends email	
         if(!$email->Send()) {
         echo "Mailer Error: " . $email->ErrorInfo;
          } else {
           echo "Message sent!";
          }
    }
?>

<main>
       
    <h1>Your Order</h1>
    <p>Order Number: <?php echo $order_id; ?></p>
    <p>Order Date: <?php echo $order_date; ?></p>
    <h2>Shipping</h2>
    <p>Ship Date:
        <?php
            if ($order['shipDate'] === NULL) {
                echo 'Not shipped yet';
            } else {
                $ship_date = strtotime($order['shipDate']);
                echo date('M j, Y', $ship_date);
            }
        ?>
    </p>
    <p><?php echo htmlspecialchars($shipping_address['line1']); ?><br>
        <?php if ( strlen($shipping_address['line2']) > 0 ) : ?>
            <?php echo htmlspecialchars($shipping_address['line2']); ?><br>
        <?php endif; ?>
        <?php echo htmlspecialchars($shipping_address['city']); ?>, <?php 
              echo htmlspecialchars($shipping_address['state']); ?>
        <?php echo htmlspecialchars($shipping_address['zipCode']); ?><br>
        <?php echo htmlspecialchars($shipping_address['phone']); ?>
    </p>
    <h2>Billing</h2>
    <p>Card Number: ...<?php echo substr($order['cardNumber'], -4); ?></p>
    <p><?php echo htmlspecialchars($billing_address['line1']); ?><br>
        <?php if ( strlen($billing_address['line2']) > 0 ) : ?>
            <?php echo htmlspecialchars($billing_address['line2']); ?><br>
        <?php endif; ?>
        <?php echo htmlspecialchars($billing_address['city']); ?>, <?php 
              echo htmlspecialchars($billing_address['state']); ?>
        <?php echo htmlspecialchars($billing_address['zipCode']); ?><br>
        <?php echo htmlspecialchars($billing_address['phone']); ?>
    </p>
    <h2>Order Items</h2>
    <table id="cart">
        <tr id="cart_header">
            <th class="left">Item</th>
            <th class="right">List Price</th>
            <th class="right">Savings</th>
            <th class="right">Your Cost</th>
            <th class="right">Quantity</th>
            <th class="right">Line Total</th>
        </tr>
        <?php
        $subtotal = 0;
        foreach ($order_items as $item) :
            $product_id = $item['productID'];
            $product = get_product($product_id);
            $item_name = $product['productName'];
            $list_price = $item['itemPrice'];
            $savings = $item['discountAmount'];
            $your_cost = $list_price - $savings;
            $quantity = $item['quantity'];
            $line_total = $your_cost * $quantity;
            $subtotal += $line_total;
            ?>
            <tr>
                <td><?php echo htmlspecialchars($item_name); ?></td>
                <td class="right">
                    <?php echo sprintf('$%.2f', $list_price); ?>
                </td>
                <td class="right">
                    <?php echo sprintf('$%.2f', $savings); ?>
                </td>
                <td class="right">
                    <?php echo sprintf('$%.2f', $your_cost); ?>
                </td>
                <td class="right">
                    <?php echo $quantity; ?>
                </td>
                <td class="right">
                    <?php echo sprintf('$%.2f', $line_total); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr id="cart_footer">
            <td colspan="5" class="right">Subtotal:</td>
            <td class="right">
                <?php echo sprintf('$%.2f', $subtotal); ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="right">
                <?php echo htmlspecialchars($shipping_address['state']); ?> Tax:
            </td>
            <td class="right">
                <?php echo sprintf('$%.2f', $order['taxAmount']); ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="right">Shipping:</td>
            <td class="right">
                <?php echo sprintf('$%.2f', $order['shipAmount']); ?>
            </td>
        </tr>
            <tr>
            <td colspan="5" class="right">Total:</td>
            <td class="right">
                <?php
                    $total = $subtotal + $order['taxAmount'] +
                             $order['shipAmount'];
                    echo sprintf('$%.2f', $total);
                ?>
            </td>
        </tr>
</table>
</main>
<?php include '../view/footer.php'; ?>
