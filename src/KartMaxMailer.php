<?php 

namespace KartMax;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception as PHPMailerException;


class KartMaxMailer
{
	
	private $mailer;
	
	/**
	 * Instantiate PHPMailer object
	 */
	public function __construct()
	{
		$this->mailer = new PHPMailer(true);

		//Server settings
	    $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
	    $this->mailer->isSMTP();
	    $this->mailer->Host       = env('MAIL_HOST'); 
	    $this->mailer->SMTPAuth   = true;
	    $this->mailer->Username   = env('MAIL_USERNAME');
	    $this->mailer->Password   = env('MAIL_PASSWORD');
	    $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	    $this->mailer->Port       = env('MAIL_PORT');

	    //Set sender details
	    $this->mailer->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

	    $this->mailer->isHTML(true);  

	    //Add default subject
	    $this->mailer->Subject = 'Greetings from PIM';

	    //Add default body
	    $this->mailer->Body = 'Hello';
	}


	public function subject(string $subject)
	{
		$this->mailer->Subject = $subject;
		return $this;
	}


	public function body($view)
	{
		$this->mailer->Body = $view;
		return $this;
	}


	public function addRecipient(array $recipient)
	{
		$emailAddress = trim($recipient['email']);
		if(isValidEmail($emailAddress))
		{
			$this->mailer->addAddress($emailAddress, $recipient['name'] ?? '');
		}
		return $this;
	}


	public function addAttachment(array $filesPath)
	{
		foreach ($filesPath as $key => $filePath) 
		{
			$this->mailer->addAttachment($filePath);
		}
		return $this; 
	}


	public function sendMail()
	{
		try 
		{
		    $this->mailer->send();
		    return 'Email has been sent';
		} 
		catch (PHPMailerException $e) 
		{
		    echo "Message could not be sent. Mailer Error: {$this->mailer->ErrorInfo}";
		}
	}

}