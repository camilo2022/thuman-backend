<?php

namespace App\Service\Contract\Addendum;

use Illuminate\Support\Facades\Http;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AddendumNotification
{
    public function sendEmail(string $toEmail, string $toName, array $data = []): string|bool
    {
        try {
            $response = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'X-Hablame-Key' => env('API_KEY', ''),
            ])->post('https://www.hablame.co/api/email/v5/send', [
                "sender" => [
                    "email" => env('EMAIL_FROM', 'no-reply@t-human.com'),
                    "name" => "T-HÜMAN"
                ],
                "recipients" => [
                    "to" => [
                        [
                            "email" => $toEmail,
                            "name" => $toName
                        ]
                    ]
                ],
                "subject" => "Código de verificación de T-HÜMAN",
                "htmlBody" => view('Contract.Addendum.AddendumNotifyEmail', $data)->render(),
                "priority" => false,
            ]);

            $data = (object) $response->json();

            if (isset($data->payLoad) && $response->status() == 202) {
                return $data->payLoad['id'];
            }
            throw new HttpException($response->status(), 'No se pudo enviar el correo: ' . $response->body());
        } catch (RuntimeException $e) {
            throw new HttpException(400, 'Error de negocio al enviar correo: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el correo: ' . $e->getMessage());
        }
    }
}
