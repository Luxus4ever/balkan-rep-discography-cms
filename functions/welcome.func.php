<?php
require_once __DIR__ . "/../config/config.php"; //MORA BITI PRODUKCIONI MAIL
require_once __DIR__ . "/log.func.php";

/************************************* FAJL KOJI ŠALJE REGISTRACIONI MAIL NOVOM KORISNIKU *************************************/


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../mail/Exception.php";
require_once __DIR__ . "/../mail/PHPMailer.php";
require_once __DIR__ . "/../mail/SMTP.php";

/**
 * Slanje welcome maila nakon registracije
 */
function sendWelcomeMail($email, $username, $displayName = "")
{
    $siteName = "Diskografija";
    $display  = trim($displayName) !== "" ? trim($displayName) : $username;

    $loginUrl = SITE_URL . "/login.php";
    $resetUrl = SITE_URL . "/forgot_password.php";
    $logoUrl = "localhost/images/Balkan-Rep-Diskografija-Logo.png";
    $year     = date('Y');

    // Ako želiš datum/vrijeme registracije u mailu:
    $registeredAt = date('d.m.Y H:i');

    $mail = new PHPMailer(true);
    $mail->isSMTP();

    try {
        // SMTP podešavanja (prilagodi svom hostingu)
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE; // 'ssl'
        $mail->Port       = SMTP_PORT;   // 465



        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // Pošiljalac
        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $display);
        
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Naslov
        $subject = "Dobrodošli na {$siteName} – nalog je kreiran";
        $mail->Subject = $subject;

        // Plain text
        $bodyText  = "Zdravo {$display},\n\n";
        $bodyText .= "Dobrodošli na {$siteName}!\n";
        $bodyText .= "Vaš nalog je uspješno kreiran.\n\n";
        $bodyText .= "Korisničko ime: {$username}\n\n";
        $bodyText .= "Prijava: {$loginUrl}\n";
        $bodyText .= "Zaboravljena šifra: {$resetUrl}\n\n";
        $bodyText .= "Napomena: Iz bezbjednosnih razloga ne šaljemo šifru putem emaila.\n\n";
        $bodyText .= "Ako vi niste kreirali nalog, ignorišite ovu poruku.\n\n";
        $bodyText .= "Pozdrav,\n{$siteName} tim";

        // HTML verzija
        /*$bodyHtml = "
        <p>Zdravo <b>{$display}</b>,</p>
        <p>Dobrodošli na <b>{$siteName}</b>! 🎶</p>
        <p>Vaš nalog je uspješno kreiran.</p>
        <p><b>Korisničko ime:</b> {$username}</p>
        <p>
            <a href='{$loginUrl}'>Prijavite se na nalog</a><br>
            <a href='{$resetUrl}'>Zaboravljena šifra</a>
        </p>
        <p style='font-size:12px;color:#777;'>
            Napomena: Iz bezbjednosnih razloga ne šaljemo šifru putem emaila.
        </p>
        <p>Pozdrav,<br>{$siteName} tim</p>
        ";*/
        /*********************** Standardna poruka ***********************/

        $preheader = "Vaš nalog je kreiran. Korisničko ime: {$username}";

        $bodyHtml = '
        <!doctype html>
        <html lang="sr">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($subject, ENT_QUOTES, "UTF-8") . '</title>
        </head>
        <body style="margin:0;padding:0;background:#f5f7fb;">
        <!-- Preheader (skriven u emailu, vidi se u inbox preview) -->
        <div style="display:none;max-height:0;overflow:hidden;opacity:0;color:transparent;">
            ' . htmlspecialchars($preheader, ENT_QUOTES, "UTF-8") . '
        </div>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f5f7fb;padding:24px 12px;">
            <tr>
            <td align="center">

                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:600px;max-width:600px;">
                <!-- Header -->
                <tr>
                    <td align="center" style="padding:12px 12px 18px 12px;">
                    <img src="' . htmlspecialchars($logoUrl, ENT_QUOTES, "UTF-8") . '" width="120" alt="' . htmlspecialchars($siteName, ENT_QUOTES, "UTF-8") . '" style="display:block;border:0;outline:none;text-decoration:none;height:auto;">
                    </td>
                </tr>

                <!-- Card -->
                <tr>
                    <td style="background:#ffffff;border-radius:16px;padding:28px 22px;border:1px solid #e8ecf5;">
                    <h1 style="margin:0 0 10px 0;font-family:Arial,Helvetica,sans-serif;font-size:22px;line-height:1.3;color:#111827;">
                        Dobrodošli, ' . htmlspecialchars($display, ENT_QUOTES, "UTF-8") . ' 👋
                    </h1>

                    <p style="margin:0 0 14px 0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:#374151;">
                        Vaš nalog na <b>' . htmlspecialchars($siteName, ENT_QUOTES, "UTF-8") . '</b> je uspješno kreiran.
                    </p>

                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:14px 0 18px 0;background:#f8fafc;border:1px solid #eef2ff;border-radius:12px;">
                        <tr>
                        <td style="padding:14px 14px;">
                            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.6;color:#111827;">
                            <b>Korisničko ime:</b> <span style="font-family:Consolas,Monaco,monospace;">' . htmlspecialchars($username, ENT_QUOTES, "UTF-8") . '</span><br>
                            <b>Vrijeme registracije:</b> ' . htmlspecialchars($registeredAt, ENT_QUOTES, "UTF-8") . '
                            </p>
                        </td>
                        </tr>
                    </table>

                    <!-- Button -->
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin:8px 0 10px 0;">
                        <tr>
                        <td align="center" bgcolor="#111827" style="border-radius:12px;">
                            <a href="' . htmlspecialchars($loginUrl, ENT_QUOTES, "UTF-8") . '" target="_blank"
                            style="display:inline-block;padding:12px 18px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#ffffff;text-decoration:none;font-weight:bold;">
                            Prijavi se
                            </a>
                        </td>
                        </tr>
                    </table>

                    <p style="margin:10px 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:1.6;color:#374151;">
                        Zaboravili ste šifru? <a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, "UTF-8") . '" target="_blank" style="color:#2563eb;text-decoration:none;">Resetujte je ovdje</a>.
                    </p>

                    <hr style="border:0;border-top:1px solid #eef2ff;margin:18px 0;">

                    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.6;color:#6b7280;">
                        Napomena: Iz bezbjednosnih razloga ne šaljemo šifru putem emaila. Ako vi niste kreirali nalog, slobodno ignorišite ovu poruku.
                    </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="padding:14px 12px 0 12px;">
                    <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.6;color:#9ca3af;">
                        © ' . htmlspecialchars($year, ENT_QUOTES, "UTF-8") . ' ' . htmlspecialchars($siteName, ENT_QUOTES, "UTF-8") . '
                    </p>
                    </td>
                </tr>
                </table>

            </td>
            </tr>
        </table>
        </body>
        </html>
        ';


        $mail->isHTML(true);
        $mail->Body    = $bodyHtml;
        $mail->AltBody = $bodyText;

        $mail->send();

        // Log success (tvoj stil)
        if (function_exists('logMailSuccess')) {
        logMailSuccess('welcome', $email, $username);
        }
        return true;

    } catch (Exception $e) {

        if (function_exists('logMailFail')) {
        logMailFail('welcome', $email, $username, $mail->ErrorInfo);
        }
        return false;
    }
}