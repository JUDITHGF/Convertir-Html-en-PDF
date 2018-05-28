<?php

// le but c'est que le mail arrive aussi en PDF au destinataire
//tous mes 'print' ont été transformé de la façon suivante
    $nomPre = $_POST['nom'];
    $courriel =  $_POST['courriel'];
    $phone =  $_POST['phone'];
    $fonction = $_POST['système'];
    $comm = $_POST['txt_comm'];


    foreach ($_POST['ck_profil'] as $value){
        $jour .=  "<li>". $value ."</li>";
    }


    foreach($_POST['ck_moment'] as $b){
        $hour .=  "<li>". $b ."</li>";
    }
//la suite c'est du html 'style color' c'est pour colorer les lettres, c'est le contenu de mon mail 'ul' donne des . ol donne des chiffres

    $content = '
        <html>
            <body>
                <b style="color: blue;">Votre nom, prénom : </b>'. $nomPre .' <br> 
                <b style="color: blue;">Votre mail : </b>'. $courriel .' <br>
                <b style="color: blue;">Votre téléphone : </b>'. $phone .' <br>
                <b style="color: blue;">Votre fonction : </b>'. $fonction .' <br>
                <b style="color: blue;">Votre commentaire : </b>'. $comm .' <br>
                <b style="color: blue;">Jour de la semaine : </b> <ol>'. $jour .'</ol> <br>
                <b style="color: blue;">Moment de disponible dans la journée : </b> <ul>'. $hour .'</ul>
            </body>
        </html>
    ';



// print"Nom - Prénom : ". $_POST['nom'];
// print "<br>";
// Print"Votre courriel : ". $_POST['courriel'];
// print "<br>";
// Print"Votre téléphone : ". $_POST['phone'];
// print "<br>";
// Print"Vous êtes... : ". $_POST['système'];
// print "<br>";
// Print"Votre commentaire : ". $_POST['txt_comm'];
// print "<br>";
// print "<br>";

// foreach ($_POST['ck_profil'] as $value){
//     print("<b>Jour de la semaine : </b>".$value );
//     print("<br>");
// }


// foreach($_POST['ck_moment'] as $b){
// 	Print"Heure appropriée pour un rendez-vous : ". $b;
// 	print "<br>";
// }

// print "<br>";print "<br>";print "<br>";print "<br>";


require_once dirname(__FILE__).'/html2pdf/vendor/autoload.php';// html2pdf est le fichier dans lequel est placé le code ci-dessous. on appel ce code par cette fonction "require_once"

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// for display the post information
if (isset($_POST['test'])) {
    echo '<pre>';
    echo htmlentities(print_r($_POST, true));
    echo '</pre>';
    exit;
}

try {
    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->output(dirname(__FILE__).'/pdf/forms.pdf', 'F'); //'dirname' chemin du fichier où se trouve FILE
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}





require_once('phpmailer/src/PHPMailer.php');
use PHPMailer\PHPMailer\PHPMailer;


$mail = new PHPMailer();
$mail->CharSet = "utf-8";
$mail->Host = 'ssl://smtp.gmail.com';
$mail->SMTPAuth   = true;//protocle réseau système qui gère les email
$mail->Port = 465; // Par défaut
// Expéditeur
$mail->SetFrom('allonsy.judith@gmail.com', 'FRANCOIS Judith');
// Destinataire
$mail->AddAddress($_POST['courriel'], $_POST['nom']);
// Objet
$mail->Subject = 'PHP en cours';
// Votre message
$mail->MsgHTML ($content);
$mail->addAttachment(dirname(__FILE__).'/pdf/forms.pdf'); 

// Envoi du mail avec gestion des erreurs
if(!$mail->Send()) {
  print 'Erreur : ' . $mail->ErrorInfo;
} else {
  print 'Message envoyé !';
} 




?>