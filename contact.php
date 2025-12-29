<?php
/**
 * Kontaktformular mit Cloudflare Turnstile Bot-Schutz
 * Moni's Fellschliff - Hundefriseur
 */

// SMTP/Mail-Konfiguration
$to_email = "info@monis-fellschliff.de"; // HIER IHRE E-MAIL EINTRAGEN
$from_email = "noreply@monis-fellschliff.de";

// Cloudflare Turnstile Konfiguration
$turnstile_secret_key = "0x4AAAAAAB69pevbL4mqFV_7mcFjFQ_Ku_g"; // HIER IHR SECRET KEY EINTRAGEN

// Fehler-Array
$errors = [];

// POST-Daten prüfen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Formular-Daten validieren
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : 'Neue Anfrage';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $turnstile_response = isset($_POST['cf-turnstile-response']) ? $_POST['cf-turnstile-response'] : '';
    
    // Validierung
    if (empty($name)) {
        $errors[] = "Name ist erforderlich";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Gültige E-Mail ist erforderlich";
    }
    
    if (empty($phone)) {
        $errors[] = "Telefonnummer ist erforderlich";
    }
    
    if (empty($message)) {
        $errors[] = "Nachricht ist erforderlich";
    }
    
    // Cloudflare Turnstile Verifizierung
    if (empty($turnstile_response)) {
        $errors[] = "Bot-Verifizierung fehlt";
    } else {
        // Turnstile Token an Cloudflare senden
        $verify_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        
        $data = [
            'secret' => $turnstile_secret_key,
            'response' => $turnstile_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context  = stream_context_create($options);
        $verify_response = file_get_contents($verify_url, false, $context);
        $response_data = json_decode($verify_response);
        
        if (!$response_data->success) {
            $errors[] = "Bot-Verifizierung fehlgeschlagen";
        }
    }
    
    // Wenn keine Fehler, E-Mails senden
    if (empty($errors)) {
        
        // ========================================
        // E-MAIL AN SIE (Monika) - HTML-Version
        // ========================================
        
        $email_subject = "🐾 Neue Terminanfrage: " . $subject;
        
        $email_body_html = '
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Neue Terminanfrage</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f0f8ff;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f8ff; padding: 20px;">
                <tr>
                    <td align="center">
                        <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            
                            <!-- Header -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #4A90E2 0%, #6BB6FF 100%); padding: 30px; text-align: center;">
                                    <h1 style="color: white; margin: 0; font-size: 28px;">🐾 Neue Terminanfrage</h1>
                                    <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 16px;">Moni\'s Fellschliff</p>
                                </td>
                            </tr>
                            
                            <!-- Content -->
                            <tr>
                                <td style="padding: 40px 30px;">
                                    
                                    <!-- Intro -->
                                    <p style="font-size: 16px; color: #333; margin: 0 0 25px 0;">
                                        <strong>Sie haben eine neue Terminanfrage über Ihre Website erhalten!</strong>
                                    </p>
                                    
                                    <!-- Customer Info Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f8ff; border-radius: 10px; margin-bottom: 25px;">
                                        <tr>
                                            <td style="padding: 20px;">
                                                <h2 style="color: #4A90E2; margin: 0 0 15px 0; font-size: 18px; border-bottom: 2px solid #6BB6FF; padding-bottom: 10px;">
                                                    📝 Kundendaten
                                                </h2>
                                                
                                                <table width="100%" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <td style="color: #666; font-weight: bold; width: 30%;">Name:</td>
                                                        <td style="color: #333;">' . htmlspecialchars($name) . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color: #666; font-weight: bold;">E-Mail:</td>
                                                        <td style="color: #333;"><a href="mailto:' . htmlspecialchars($email) . '" style="color: #4A90E2; text-decoration: none;">' . htmlspecialchars($email) . '</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color: #666; font-weight: bold;">Telefon:</td>
                                                        <td style="color: #333;"><a href="tel:' . htmlspecialchars($phone) . '" style="color: #4A90E2; text-decoration: none;">' . htmlspecialchars($phone) . '</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Message Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff8e1; border-left: 4px solid #FFB74D; border-radius: 10px; margin-bottom: 25px;">
                                        <tr>
                                            <td style="padding: 20px;">
                                                <h2 style="color: #F57C00; margin: 0 0 15px 0; font-size: 18px;">
                                                    💬 Nachricht
                                                </h2>
                                                <p style="color: #333; margin: 0; line-height: 1.6; white-space: pre-wrap;">' . htmlspecialchars($message) . '</p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Quick Actions -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin: 25px 0;">
                                        <tr>
                                            <td align="center">
                                                <a href="mailto:' . htmlspecialchars($email) . '" style="display: inline-block; background: linear-gradient(135deg, #4A90E2, #6BB6FF); color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; margin: 0 5px;">
                                                    ✉️ Antworten
                                                </a>
                                                <a href="tel:' . htmlspecialchars($phone) . '" style="display: inline-block; background: linear-gradient(135deg, #4CAF50, #45a049); color: white; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; margin: 0 5px;">
                                                    📞 Anrufen
                                                </a>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Meta Info -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f5f5f5; border-radius: 10px; margin-top: 30px;">
                                        <tr>
                                            <td style="padding: 15px; font-size: 12px; color: #666;">
                                                <strong>📅 Eingegangen:</strong> ' . date('d.m.Y') . ' um ' . date('H:i') . ' Uhr<br>
                                                <strong>🌐 IP-Adresse:</strong> ' . $_SERVER['REMOTE_ADDR'] . '<br>
                                                <strong>🛡️ Bot-Schutz:</strong> <span style="color: #4CAF50;">✓ Cloudflare Turnstile verifiziert</span>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td style="background-color: #4A90E2; padding: 20px; text-align: center;">
                                    <p style="color: white; margin: 0; font-size: 14px;">
                                        <strong>Moni\'s Fellschliff</strong><br>
                                        Jahnstr. 13, 90599 Dietenhofen<br>
                                        📞 0176 48852064
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
        
        // Plain-Text Alternative (für E-Mail-Clients ohne HTML)
        $email_body_plain = "
NEUE TERMINANFRAGE
===========================================

Kundendaten:
-------------------------------------------
Name:           $name
E-Mail:         $email
Telefon:        $phone

Nachricht:
-------------------------------------------
$message

-------------------------------------------
Eingegangen am: " . date('d.m.Y H:i:s') . "
IP-Adresse:     " . $_SERVER['REMOTE_ADDR'] . "
Bot-Schutz:     Cloudflare Turnstile ✓

-------------------------------------------
Moni's Fellschliff
Jahnstr. 13, 90599 Dietenhofen
Tel: 0176 48852064
        ";
        
        // E-Mail-Header für HTML + Plain Text
        $boundary = md5(time());
        $headers = "From: $from_email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$boundary\"\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        $email_body = "--$boundary\r\n";
        $email_body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $email_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $email_body .= $email_body_plain . "\r\n";
        $email_body .= "--$boundary\r\n";
        $email_body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $email_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $email_body .= $email_body_html . "\r\n";
        $email_body .= "--$boundary--";
        
        // E-Mail an Sie senden
        $mail_sent_to_you = mail($to_email, $email_subject, $email_body, $headers);
        
        // ========================================
        // BESTÄTIGUNGSMAIL AN KUNDEN
        // ========================================
        
        $customer_subject = "Vielen Dank für Ihre Anfrage - Moni's Fellschliff";
        
        $customer_body_html = '
        <!DOCTYPE html>
        <html lang="de">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Bestätigung Ihrer Anfrage</title>
        </head>
        <body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f0f8ff;">
            <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f8ff; padding: 20px;">
                <tr>
                    <td align="center">
                        <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                            
                            <!-- Header -->
                            <tr>
                                <td style="background: linear-gradient(135deg, #4A90E2 0%, #6BB6FF 100%); padding: 40px 30px; text-align: center;">
                                    <h1 style="color: white; margin: 0; font-size: 32px;">🐾</h1>
                                    <h2 style="color: white; margin: 10px 0 0 0; font-size: 24px;">Moni\'s Fellschliff</h2>
                                    <p style="color: rgba(255,255,255,0.9); margin: 5px 0 0 0;">Professionelle Hundepflege mit Herz</p>
                                </td>
                            </tr>
                            
                            <!-- Content -->
                            <tr>
                                <td style="padding: 40px 30px;">
                                    
                                    <h2 style="color: #4A90E2; margin: 0 0 20px 0; font-size: 24px;">
                                        Vielen Dank für Ihre Anfrage!
                                    </h2>
                                    
                                    <p style="font-size: 16px; color: #333; line-height: 1.6; margin: 0 0 20px 0;">
                                        Liebe/r ' . htmlspecialchars($name) . ',
                                    </p>
                                    
                                    <p style="font-size: 16px; color: #333; line-height: 1.6; margin: 0 0 20px 0;">
                                        herzlichen Dank für Ihre Terminanfrage! Ich habe Ihre Nachricht erhalten und werde mich <strong>schnellstmöglich</strong> bei Ihnen melden.
                                    </p>
                                    
                                    <!-- Info Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f8ff; border-radius: 10px; margin: 25px 0;">
                                        <tr>
                                            <td style="padding: 20px;">
                                                <h3 style="color: #4A90E2; margin: 0 0 15px 0; font-size: 18px;">
                                                    ✅ Ihre Anfrage im Überblick
                                                </h3>
                                                <p style="color: #666; margin: 0; line-height: 1.6;">
                                                    <strong>Ihre Nachricht:</strong><br>
                                                    <span style="color: #333; white-space: pre-wrap;">' . htmlspecialchars($message) . '</span>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <p style="font-size: 16px; color: #333; line-height: 1.6; margin: 0 0 20px 0;">
                                        In der Zwischenzeit können Sie mich bei dringenden Fragen gerne direkt anrufen.
                                    </p>
                                    
                                    <!-- Contact Box -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #4A90E2, #6BB6FF); border-radius: 10px; margin: 25px 0;">
                                        <tr>
                                            <td style="padding: 25px; text-align: center;">
                                                <h3 style="color: white; margin: 0 0 15px 0; font-size: 20px;">
                                                    📞 Kontakt
                                                </h3>
                                                <p style="color: white; margin: 0; font-size: 18px; font-weight: bold;">
                                                    0176 48852064
                                                </p>
                                                <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0; font-size: 14px;">
                                                    Jahnstr. 13, 90599 Dietenhofen
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <!-- Quote -->
                                    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff8e1; border-left: 4px solid #FFB74D; border-radius: 10px; margin: 25px 0;">
                                        <tr>
                                            <td style="padding: 20px;">
                                                <p style="color: #666; margin: 0; font-style: italic; text-align: center; font-size: 15px;">
                                                    "Mit Herz, mit Seele und mit der Freude aneinander.<br>
                                                    Mensch und Hund gehören zusammen."
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <p style="font-size: 16px; color: #333; line-height: 1.6; margin: 25px 0 0 0;">
                                        Herzliche Grüße<br>
                                        <strong style="color: #4A90E2;">Monika Burr</strong><br>
                                        <span style="color: #666; font-size: 14px;">Zertifizierte Hunde-Groomerin</span>
                                    </p>
                                    
                                </td>
                            </tr>
                            
                            <!-- Footer -->
                            <tr>
                                <td style="background-color: #f5f5f5; padding: 20px; text-align: center; border-top: 1px solid #e0e0e0;">
                                    <p style="color: #666; margin: 0; font-size: 12px; line-height: 1.6;">
                                        <strong>Moni\'s Fellschliff - Hundefriseur</strong><br>
                                        Jahnstr. 13, 90599 Dietenhofen<br>
                                        Tel: 0176 48852064<br>
                                        <a href="https://monis-fellschliff.de" style="color: #4A90E2; text-decoration: none;">www.monis-fellschliff.de</a><br>
                                        Facebook: Monis Fellschliff
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
        
        $customer_body_plain = "
Vielen Dank für Ihre Anfrage!
===========================================

Liebe/r $name,

herzlichen Dank für Ihre Terminanfrage! Ich habe Ihre Nachricht erhalten und werde mich schnellstmöglich bei Ihnen melden.

Ihre Nachricht:
-------------------------------------------
$message

-------------------------------------------

In der Zwischenzeit können Sie mich bei dringenden Fragen gerne direkt anrufen:

📞 0176 48852064

\"Mit Herz, mit Seele und mit der Freude aneinander.
Mensch und Hund gehören zusammen.\"

Herzliche Grüße
Monika Burr
Zertifizierte Hunde-Groomerin

-------------------------------------------
Moni's Fellschliff - Hundefriseur
Jahnstr. 13, 90599 Dietenhofen
Tel: 0176 48852064
www.monis-fellschliff.de
        ";
        
        // Header für Kundenmail
        $customer_boundary = md5(time() . 'customer');
        $customer_headers = "From: Monika Burr <$from_email>\r\n";
        $customer_headers .= "Reply-To: $from_email\r\n";
        $customer_headers .= "MIME-Version: 1.0\r\n";
        $customer_headers .= "Content-Type: multipart/alternative; boundary=\"$customer_boundary\"\r\n";
        
        $customer_body = "--$customer_boundary\r\n";
        $customer_body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $customer_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $customer_body .= $customer_body_plain . "\r\n";
        $customer_body .= "--$customer_boundary\r\n";
        $customer_body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $customer_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $customer_body .= $customer_body_html . "\r\n";
        $customer_body .= "--$customer_boundary--";
        
        // Bestätigungsmail an Kunden senden
        $mail_sent_to_customer = mail($email, $customer_subject, $customer_body, $customer_headers);
        
        // Erfolg prüfen
        if ($mail_sent_to_you && $mail_sent_to_customer) {
            // Erfolg
            echo json_encode([
                'success' => true,
                'message' => 'Nachricht erfolgreich versendet. Bestätigungsmail an Kunden gesendet.'
            ]);
            
            // Optional: Log-Datei schreiben
            $log_entry = date('Y-m-d H:i:s') . " - Anfrage von: $name ($email) - Beide E-Mails versendet\n";
            file_put_contents('contact_log.txt', $log_entry, FILE_APPEND);
            
        } else {
            // E-Mail-Fehler
            $error_details = [];
            if (!$mail_sent_to_you) $error_details[] = "Admin-Mail fehlgeschlagen";
            if (!$mail_sent_to_customer) $error_details[] = "Kunden-Mail fehlgeschlagen";
            
            echo json_encode([
                'success' => false,
                'message' => 'E-Mail-Versand-Problem: ' . implode(', ', $error_details)
            ]);
        }
        
    } else {
        // Validierungsfehler
        echo json_encode([
            'success' => false,
            'message' => 'Validierungsfehler',
            'errors' => $errors
        ]);
    }
    
} else {
    // Nicht-POST-Anfrage
    echo json_encode([
        'success' => false,
        'message' => 'Ungültige Anfragemethode'
    ]);
}
?>