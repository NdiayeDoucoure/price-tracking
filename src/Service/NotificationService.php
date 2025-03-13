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
        $this->twilio = new Client($sid, $token);
        $this->fromPhone = $phone;
    }

    public function sendPriceAlert(string $to, string $produit, float $oldPrice, float $newPrice): void
    {
        $message = "Alerte : Le prix de $produit est passé de $oldPrice FCFA à $newPrice FCFA.";

        try {
            $this->twilio->messages->create($to, [
                'from' => $this->fromPhone,
                'body' => $message
            ]);
        } catch (TwilioException $e) {
            // Log l'erreur pour éviter un crash
            error_log("Erreur lors de l'envoi du SMS : " . $e->getMessage());
        }
    }
}
