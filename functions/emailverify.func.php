<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/log.func.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../mail/Exception.php";
require_once __DIR__ . "/../mail/PHPMailer.php";
require_once __DIR__ . "/../mail/SMTP.php";

// 72h expiry, 120s cooldown
define('EMAIL_VERIFY_EXP_HOURS', 72);
define('EMAIL_VERIFY_COOLDOWN_SEC', 120);

function emailVerify_canResend($userId): array
{
    global $conn;

    $q = "SELECT email_verify_last_sent_at FROM korisnici WHERE idKorisnici='" . (int)$userId . "' LIMIT 1";
    $r = mysqli_query($conn, $q);
    $row = $r ? mysqli_fetch_assoc($r) : null;

    if (!$row || empty($row['email_verify_last_sent_at'])) {
        return [true, 0];
    }

    $last = strtotime($row['email_verify_last_sent_at']);
    $now  = time();
    $diff = $now - $last;

    if ($diff >= EMAIL_VERIFY_COOLDOWN_SEC) {
        return [true, 0];
    }

    return [false, EMAIL_VERIFY_COOLDOWN_SEC - $diff];
}

function emailVerify_createToken($userId): string
{
    // 32 bytes = 64 hex chars
    $token = bin2hex(random_bytes(32));

    $hash = password_hash($token, PASSWORD_DEFAULT);

    global $conn;
    $uid = (int)$userId;
    $ip  = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua  = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

    // (Opcija) invalidiraj prethodne neiskorištene tokene
    mysqli_query($conn, "UPDATE email_verifications SET used_at=NOW()
                         WHERE korisnikId={$uid} AND used_at IS NULL");

    $stmt = mysqli_prepare($conn, "
        INSERT INTO email_verifications (korisnikId, token_hash, expires_at, request_ip, user_agent)
        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL " . EMAIL_VERIFY_EXP_HOURS . " HOUR), ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "isss", $uid, $hash, $ip, $ua);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $token;
}

function sendEmailVerificationMail($email, $username, $token): bool
{
    $siteName = "Diskografija";
    $verifyUrl = SITE_URL . "/verify_email.php?token=" . urlencode($token);
    $logoUrl = "https://dlux.rs/images/Balkan-Rep-Diskografija-Logo.png";

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $mail->CharSet  = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
        $mail->addAddress($email, $username);

        $subject = "Potvrdite email adresu – {$siteName}";
        $mail->Subject = $subject;

        $bodyText  = "Zdravo {$username},\n\n";
        $bodyText .= "Molimo potvrdite svoju email adresu klikom na link:\n{$verifyUrl}\n\n";
        $bodyText .= "Potvrda emaila omogućava: email obavještenja i pouzdanije opcije oporavka naloga.\n";
        $bodyText .= "Link važi " . EMAIL_VERIFY_EXP_HOURS . "h.\n\n";
        $bodyText .= "Ako vi niste kreirali nalog, ignorišite ovu poruku.\n\n";
        $bodyText .= "Pozdrav,\n{$siteName} tim";

        $bodyHtml = '
        <!doctype html><html lang="sr"><head>
        <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>' . htmlspecialchars($subject, ENT_QUOTES, "UTF-8") . '</title></head>
        <body style="margin:0;padding:0;background:#f5f7fb;">
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px;background:#f5f7fb;">
            <tr><td align="center">
              <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;">
                <tr><td align="center" style="padding:12px 12px 18px 12px;">
                  <img src="' . htmlspecialchars($logoUrl, ENT_QUOTES, "UTF-8") . '" width="120" alt="Diskografija" style="display:block;border:0;height:auto;">
                </td></tr>
                <tr><td style="background:#fff;border:1px solid #e8ecf5;border-radius:16px;padding:24px 20px;">
                  <h2 style="margin:0 0 10px 0;font-family:Arial;font-size:20px;color:#111827;">Potvrdite email adresu</h2>
                  <p style="margin:0 0 14px 0;font-family:Arial;font-size:14px;line-height:1.6;color:#374151;">
                    Zdravo <b>' . htmlspecialchars($username, ENT_QUOTES, "UTF-8") . '</b>, kliknite na dugme ispod da potvrdite email.
                  </p>

                  <table role="presentation" cellpadding="0" cellspacing="0" style="margin:10px 0 12px 0;">
                    <tr><td bgcolor="#111827" style="border-radius:12px;">
                      <a href="' . htmlspecialchars($verifyUrl, ENT_QUOTES, "UTF-8") . '" target="_blank"
                         style="display:inline-block;padding:12px 18px;font-family:Arial;font-size:15px;color:#fff;text-decoration:none;font-weight:bold;">
                        Potvrdi email
                      </a>
                    </td></tr>
                  </table>

                  <p style="margin:0 0 10px 0;font-family:Arial;font-size:12px;line-height:1.6;color:#6b7280;">
                    Link važi ' . EMAIL_VERIFY_EXP_HOURS . 'h. Ako dugme ne radi, kopirajte link:
                    <br><span style="word-break:break-all;">' . htmlspecialchars($verifyUrl, ENT_QUOTES, "UTF-8") . '</span>
                  </p>

                  <p style="margin:0;font-family:Arial;font-size:12px;line-height:1.6;color:#6b7280;">
                    Potvrda emaila omogućava: email obavještenja i pouzdanije opcije oporavka naloga.
                  </p>
                </td></tr>
              </table>
            </td></tr>
          </table>
        </body></html>';

        $mail->isHTML(true);
        $mail->Body    = $bodyHtml;
        $mail->AltBody = $bodyText;

        $mail->send();
        return true;

    } catch (Exception $e) {
        if (function_exists('logMailFail')) {
            logMailFail('email_verify', $email, $username, $mail->ErrorInfo);
        }
        return false;
    }
}

function emailVerify_sendOrResend($userId, $email, $username): array
{
    global $conn;

    [$ok, $wait] = emailVerify_canResend($userId);
    if (!$ok) {
        return [false, "Sačekajte još {$wait} sekundi pa pokušajte ponovo."];
    }

    $token = emailVerify_createToken($userId);

    $sent = sendEmailVerificationMail($email, $username, $token);

    if ($sent) {
        mysqli_query($conn, "UPDATE korisnici SET email_verify_last_sent_at=NOW() WHERE idKorisnici=" . (int)$userId);
        if (function_exists('logMailSuccess')) {
            logMailSuccess('email_verify', $email, $username);
        }
        return [true, "Verifikacioni link je poslat na email."];
    }

    return [false, "Došlo je do greške prilikom slanja emaila. Pokušajte kasnije."];
}