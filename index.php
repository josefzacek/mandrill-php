<?php
// https://github.com/PHPMailer/PHPMailer
require 'PHPMailer/PHPMailerAutoload.php';

// set variables
$field ='';
$security ='';
$error_occured ='';

date_default_timezone_set('Europe/Dublin');
$date_and_time = date("d/m/Y G:i:s<br>", time());

// if submit button is pressed
if(isset($_POST['send_contact_form']))
	{
	// check if security question is correct
	if(isset($_POST['security']) && $_POST['security'] == 14)
		{
    // check if all fields are filled out
		if(isset($_POST['contact_name']) && isset($_POST['phone']) && isset($_POST['email']) && isset($_POST['message']))
			{
      // Data Variables
			$contact_name = htmlentities($_POST['contact_name']);
			$phone = htmlentities($_POST['phone']);
			$email = htmlentities($_POST['email']);
			$message = htmlentities($_POST['message']);
        
			// check if variables not empty
			if(!empty($contact_name) && !empty($phone) && !empty($email) && !empty($message))
			{

				$mail = new PHPMailer;

				$mail->SMTPOptions = array(															// PHP 5.6 certificate verification failure
						'ssl' => array(
								'verify_peer' => false,
								'verify_peer_name' => false,
								'allow_self_signed' => true
						)
				);

				$mail->IsSMTP();  																			// Set mailer to use SMTP
				$mail->Host = 'smtp.mandrillapp.com';										// Specify main and backup server
				$mail->Port = 587;																			// Set the SMTP port
				$mail->SMTPAuth = true;																	// Enable SMTP
				$mail->Username = 'MANDRILL_USERNAME';						// SMTP username
				$mail->Password = 'MANDRILL_PASSWORD';							// SMTP password
				$mail->SMTPSecure = 'tls';															// Enable encryption, 'ssl' also accepted
				$mail->From = 'form@josefzacek.com';							// this MUST look like email adress
				$mail->FromName = 'josefzacek.com - contact form';
				$mail->AddAddress('RECIPIENT_EMAIL_ADDRESS');					// Add a recipient
				$mail->AddAddress('SECOND_RECIPIENT_EMAIL_ADDRESS','Josef Zacek');	// Add a recipient / name is optional
				$mail->IsHTML(true);																		// Set email format to HTML
				$mail->Subject = 'Contact form';
				$mail->Body ='<p><b>Name:</b> ' . $contact_name .'</p>'
										.'<p><b>Email:</b> ' . $email .'</p>'
										.'<p><b>Phone:</b> ' . $phone .'</p>'
										.'<p><b>Message:</b> ' . $message .'</p>'
										.'<p><b>Sent date and time:</b> ' . $date_and_time .'</p>';

				if (!$mail->Send()) {
					$error_occured = 'Sorry an error occurred';
				} else {
					$emailSent = true;
					echo 'Message has been sent!'; 
				}

			}// end of (if) check if variables not empty
			else
			{ 
				$field = 'Please fill out this field!';
			}// end of (else) check if variables not empty
			
		}// end of (if) all fields filled out
		else 
		{ 
			$field = 'Required!';
		}// end of (else) all fields filled out
			
	}// end of (if) check if security question is correct
	else 
	{
		$field = 'All Field are required!';
		$security = '/ Wrong answer';
	}// end of (else) check id security question is correct
	
}// end of (if) submit button is pressed

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Mandrillapp</title>
  </head>
  <body>
    
    <article>

    <?php if(isset($emailSent) && $emailSent == true) { ?>

      <div style="text-align:center; margin: 10px 0;">
          <h1>Thank You <span id="thank-you-name"><?php echo $contact_name;?></span>,</h1>
          <p>we will consider your comment.</p>
          <p>Return to <a href="index.php">Index page</a></p>
      </div>

    <?php } else { ?>

      <h1>MandrillApp</h1>
      
      <div>
        <h2>Send us a message</h2>
        <p>We would love to hear from you. If you have any queries or comments please fill in the form below and one of our staff will get back to you.</p>

        <form action="index.php" method="post">
          <span class="error"><?php echo $error_occured;?></span>
          <div>
            <label for="contact_name">Name:<sup>*</sup><span class="error"><?php echo $field;?></span></label>
            <div>
              <input id="contact_name" name="contact_name" type="text" placeholder="Name" required />
            </div>
          </div> <!-- /name -->

          <div>
            <label for="phone">Phone:<sup class="required">*</sup><span class="error"><?php echo $field;?></span></label>
            <div>
            <input id="phone" name="phone" type="tel" placeholder="Phone" required />
            </div>
          </div> <!-- /phone -->

          <div>
            <label for="email">Email:<sup class="required">*</sup><span class="error"><?php echo $field;?></span></label>
            <div>
              <input id="email" name="email" type="email" placeholder="Email" required />
            </div>
          </div> <!-- /email -->

          <div>
            <label for="message">Message:<sup class="required">*</sup><span class="error"><?php echo $field;?></span></label>
            <div>
              <textarea id="message" name="message" placeholder="Message" required></textarea>
            </div>
          </div> <!-- /message -->

          <div>
            <label for="security">What is 7+7:<sup class="required">*</sup><span class="error"><?php echo $field;?> <?php echo $security; ?></span></label>
            <div>
              <input id="security" name="security" type="tel" required />
            </div>
          </div> <!-- /security -->

          <input name="send_contact_form" type="submit" value="Submit">
        </form>
      </div>

    <?php } ?>
  </article>

  </body>
</html>

