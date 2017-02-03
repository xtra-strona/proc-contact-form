<?php namespace ProcessWire;
         include("./_head.php");
// UTWORZ NASTĘPUJĄCE POLA
/*
  mail_to   => Do Kogo Ma iść Wiadomość => Pole E-Mail
  mail_from => Od Kogo Wysłałeś Wiadomość => Pole E-Mail
  mail_subject => Pole Opisu O czym jest Wiadomość => Pole Text lub Textarea

    text_1 => Pole Tekstowe Nagłowka => Pole Text
    body => Po prostu pole info => Defoltowe Pole Textarea ( body )
    ph_number => Numer Telefonu => Pole Text
*/

// PODEPNIJ SKRYPTY W STOPCE STRONY ORAZ NAJLEPIEJ ŻEBYŚ UŻYWAŁ BOOTSTRAPA
/*

<link href="<?php echo $config->urls->templates?>form-style.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<script type="text/javascript">
//VALIDATION
$(function () {
      $.validate({
        lang: 'pl'
      });
})
</script>
*/

$mail_to = $mail_from = $mail_subject = $_mrs = $_mrk = "";


$mail_to = $page->mail_to;
$mail_from = $page->mail_from;
$mail_subject = $page->mail_subject;

$nagl = $err = '';

// define variables and set to empty values
$nameErr = $emailErr = $subjectErr = $messageErr = "";
$name = $email = $subject = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["name"])) {
    $nameErr = "Imie Jest Wymagane";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Jedynie Litery oraz puste przestrzenie";
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email Jest Wymagany";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Nieprawidłowy Format Email";
    }
  }

  if (empty($_POST["subject"])) {
    $subjectErr = "Temat Jest Wymagany";
  } else {
    $subject = test_input($_POST["subject"]);
  }

    if (empty($_POST["message"])) {
    $messageErr = "Wiadomość Jest Wymagana";
  } else {
    $message = test_input($_POST["message"]);
  }

}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
} ?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ( ($name !='') && ($email !='') && ($subject !='') && ($message!='') ) {

            if( ($emailErr =='') ){
                        $mail = wireMail();
                        $mail->to("$mail_to")->from("$mail_from"); // all calls can be chained
                        $mail->subject("$mail_subject");
                        $mail->body("<b>Imie:</b> $name <br> <b>Email:</b> $email <br> <b>Temat:</b> $subject</br> <b>Wiadomość:</b> $message");
                        $mail->bodyHTML("<html><body><b>Imie:</b> $name <br> <b>Email:</b> $email <br> <b>Temat:</b> $subject<br> <b>Wiadomość:</b> $message</body></html>");
                        $mail->send();

                   $nagl = "<div class='form-nagl alert alert-success' role='alert'><ul><li><h2>Twoja Wiadomość Została wysłana</h2></li><li><b>Imie:</b> $name</li><li><b>E-Mail:</b> $email<li><b>Temat:</b> $subject</li><li><b>Wiadomość:</b> $message</ul></div>";

            }else {
                $err = "<div class='alert alert-danger' role='alert'><h3>Nieprawidłowy Format Email</h3></div>";
            }

                } else {

                    $nagl = "<div class='alert alert-success' role='alert'><h3>Wypełnij Formularz</h3></div>";
                }
}?>

    <?=$nagl; ?>
    <h1><?=$err; ?></h1>
    <!-- main-container -->
    <div class="container main-container contact-page">
        <div class="col-md-6">
            <form class='cont-form cmxform' id='commentForm' action="./" method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-contact">
                            <input type="text" name="name" value="<?php echo $name;?>" data-validation="length alphanumeric" data-validation-allowing=" . Ż ż ą ć ę ł ń ó ś ź Ó Ł" data-validation-length="min3" maxlength="30">
                            <?php if($nameErr !=''){ ?>
                              <span class="error">* <?php echo $nameErr;?></span>
                            <?php }else{ ?>
                      <span><?php echo _x('Imie','Formularz Kontaktowy'); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-contact">
                            <input data-validation="length email" data-validation-length="min3" maxlength="50" type="text" name="email" value="<?php echo $email;?>">
                            <?php if($emailErr !=''){ ?>
                              <span class="error">* <?php echo $emailErr;?></span>
                            <?php }else{ ?>
                              <span><?php echo _x('E-Mail','Formularz Kontaktowy'); ?></span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-contact">
                            <input name="subject" value="<?php echo $subject;?>" type="text" data-validation="length alphanumeric" data-validation-length="min5" data-validation-allowing=" . Ż ż ą ć ę ł ń ó ś ź Ó Ł" maxlength="100">
                            <?php if($subjectErr !=''){ ?>
                                <span class="error">* <?php echo $subjectErr;?></span>
                               <?php }else{ ?>
                                 <span><?php echo _x('Temat','Formularz Kontaktowy'); ?></span>
                               <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="textarea-contact">
                            <textarea name="message" value="<?php echo $message;?>" data-validation="length alphanumeric" data-validation-length="min10" data-validation-allowing=" . Ż ż ą ć ę ł ń ó ś ź Ó Ł" maxlength="900"></textarea>
                                <?php if($messageErr !=''){ ?>
                                  <span class="error">* <?php echo $messageErr;?></span>
                                <?php }else{ ?>
                                  <span><?php echo _x('Wiadomość','Formularz Kontaktowy'); ?></span>
                                <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <input class="btn btn-box" type="submit" name="submit" value="<?= _x('Wyślij','Contact Form');?>">
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-6 c-info">
          <?php if ($page->text_1): ?>
                      <h2 class='text-center'><?=$page->text_1;?></h2>
          <?php endif; ?>
              <?php if ($page->body): ?>
                        <?=$page->body;?>
              <?php endif; ?>
            <div class="contact-info">
              <?php if ($page->ph_number): ?>
                <p class='phone'><i class="fa fa-phone"></i><?=$page->ph_number;?></p>
              <?php endif; ?>
                <?php if ($page->mail_to): ?>
                  <p class='e-mail'><i class="fa fa-envelope"></i><?=$page->mail_to;?></p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

    <!-- end main-container -->
    <?php include("./_foot.php"); ?>
