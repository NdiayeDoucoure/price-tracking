<?php
namespace App\Service;

use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class NotificationService
{
    private Client $twilio;
    private string $fromPhone;

    public function __construct(string $sid, string $token, string $phone)
    {
        error_log("SID: $sid, Token: $token, Phone: $phone");
        $this->twilio = new Client($sid, $token);
        $this->fromPhone = $phone;
    }

    public function sendPriceAlert(string $to, string $produit, float $oldPrice, float $newPrice): void
    {
        // SessionStartIfNotStarted
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $message = "Alerte : Le prix de $produit est passé de $oldPrice FCFA à $newPrice FCFA.";

        // KeyGeneration
        $key = md5($to . $produit . $oldPrice . $newPrice);

        if (isset($_SESSION['smsSent'][$key])) {
            error_log("Le SMS a déjà été envoyé pour cette alerte.");
            return;
        }

        try {
            $messageSent = $this->twilio->messages->create($to, [
                'from' => $this->fromPhone,
                'body' => $message
            ]);

            error_log("Message envoyé avec succès, SID: " . $messageSent->sid);

            // AddKeyToSession
            $_SESSION['smsSent'][$key] = true;

        } catch (TwilioException $e) {
            // GenerateTwilioError
            if ($e->getCode() == 429) {
                $errorMessage = [
                    'status' => 429,
                    'error' => 'Limite quotidienne des messages dépassée. Veuillez réessayer plus tard.',
                    'detail' => $e->getMessage()
                ];
                header('Content-Type: application/json');
                http_response_code(429);
                echo json_encode($errorMessage);
                exit;
            } else {
                error_log("Erreur lors de l'envoi du SMS : " . $e->getMessage());

                // GenerateInternalError
                $errorMessage = [
                    'status' => 500,
                    'error' => 'Une erreur interne est survenue lors de l\'envoi du SMS.',
                    'detail' => $e->getMessage()
                ];
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode($errorMessage);
                exit;
            }
        }
    }
}
