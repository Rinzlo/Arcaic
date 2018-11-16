<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Auth;
use App\Config;
use App\Flash;
use Core\View;
use App\Models\Post;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Posts controller
 */
class Posts extends \App\Controllers\Authenticated
{

    /**
     * Show the index page
     *
     * @return void
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function indexAction(): void
    {
        $this->sendEmailTest(Config::MAIL_USERNAME, 'PHPMailer GMail SMTP test', 'with mailer');
        $posts = Post::getAll();

        View::renderTemplate('Posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * Add a new post
     */
    public function newAction(): void
    {
        echo "new post";
    }

    /**
     * Show an item
     */
    public function showAction(): void
    {
        echo "show action";
    }

    /**
     * Tests to send email via gmail smtp
     *
     * @param string $email_to
     * @param string $subject
     * @param string $body
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function sendEmailTest(string $email_to, string $subject = '', string $body = ''): void
    {
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = Config::SMTP_DEBUG;
        //Set the hostname of the mail server
        $mail->Host = 'smtp.gmail.com';
        // use
        // $mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 587;
        // Allows use of a separate from sender than the username
        $mail->Mailer = "gmail";
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = Config::MAIL_USERNAME;
        //Password to use for SMTP authentication
        $mail->Password = Config::MAIL_PASSWORD;
        //Set who the message is to be sent from
        $mail->setFrom(Config::MAIL_FROM, Config::APP_NAME);
        //Set an alternative reply-to address
//        $mail->addReplyTo('example@gmail.com', 'First Last');
        //Set who the message is to be sent to
//        $mail->addAddress('example@gmail.com', 'John Doe');
        // Set who the message is to be sent to without their name
        $mail->addAddress($email_to);
        //Set the subject line
        $mail->Subject = $subject;
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
//        $mail->msgHTML(file_get_contents('contents.html'), __DIR__);
        //Replace the plain text body with one created manually
        // body stuff
        $mail->msgHTML($body);
//        $mail->AltBody = 'This is a plain-text message body';
        //Attach an image file
//        $mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            Flash::addMessage("Mailer Error: " . $mail->ErrorInfo, Flash::WARNING);
//            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            Flash::addMessage("Message sent, check your spam folder!", Flash::SUCCESS);
//            echo "Message sent!";
            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
        }
    }
}