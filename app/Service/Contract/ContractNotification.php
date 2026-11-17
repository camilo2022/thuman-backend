<?php

namespace App\Service\Contract;

use Illuminate\Support\Facades\Http;
use Exception;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ContractNotification
{
    // public function sendEmail(string $toEmail, string $toName, array $data = []): string|bool
    // {
    //     try {
    //         $response = Http::withHeaders([
    //             'accept' => 'application/json',
    //             'content-type' => 'application/json',
    //             'X-Hablame-Key' => env('API_KEY', ''),
    //         ])->post('https://www.hablame.co/api/email/v5/send', [
    //             "sender" => [
    //                 "email" => env('EMAIL_FROM', 'no-reply@t-human.com'),
    //                 "name" => "T-HÜMAN"
    //             ],
    //             "recipients" => [
    //                 "to" => [
    //                     [
    //                         "email" => $toEmail,
    //                         "name" => $toName
    //                     ]
    //                 ]
    //             ],
    //             "subject" => "Código de verificación de T-HÜMAN",
    //             "htmlBody" => view('Contract.ContractNotifyEmail', $data)->render(),
    //             "priority" => false,
    //         ]);

    //         $data = (object) $response->json();

    //         if (isset($data->payLoad) && $response->status() == 202) {
    //             return $data->payLoad['id'];
    //         }
    //         throw new HttpException($response->status(), 'No se pudo enviar el correo: ' . $response->body());
    //     } catch (RuntimeException $e) {
    //         throw new HttpException(400, 'Error de negocio al enviar correo: ' . $e->getMessage());
    //     } catch (HttpException $e) {
    //         throw $e;
    //     } catch (Exception $e) {
    //         throw new HttpException(500, 'Error inesperado al enviar el correo: ' . $e->getMessage());
    //     }
    // }

    public function sendEmail(string $toEmail, string $toName, array $data = []): string
    {
        try {
            $htmlBody = view(
                'Contract.ContractNotifyEmail',
                $data
            )->render();
            $response = Http::withBasicAuth(
                config('services.masiv.user'),
                config('services.masiv.password')
            )
                ->acceptJson()
                ->asJson()
                ->timeout(30)
                ->post(
                    config('services.masiv.email_url'),
                    [
                        'Subject' => 'Código de verificación de T-HÜMAN',
                        'From' => config('services.masiv.email_from'),
                        'Template' => [
                            'Type' => 'text/html',
                            'Value' => $htmlBody,
                        ],
                        'Recipients' => [
                            [
                                'To' => $toEmail,
                                'Name' => $toName,
                            ],
                        ],
                    ]
                );
            logger()->info('Respuesta envío Masiv', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
                'destination' => $toEmail,
            ]);
            if (!$response->successful()) {
                throw new HttpException(
                    $response->status(),
                    'No se pudo enviar el correo por Masiv: ' .
                        $response->body()
                );
            }
            $deliveryId = $response->json('data.deliveryId');

            if (!$deliveryId) {
                throw new HttpException(
                    502,
                    'Masiv aceptó la petición, pero no retornó data.deliveryId. Respuesta: ' .
                        $response->body()
                );
            }
            return (string) $deliveryId;
        } catch (HttpException $e) {
            throw $e;
        } catch (RuntimeException $e) {
            throw new HttpException(
                400,
                'Error de configuración al enviar correo por Masiv: ' .
                    $e->getMessage()
            );
        } catch (Exception $e) {
            throw new HttpException(
                500,
                'Error inesperado al enviar el correo por Masiv: ' .
                    $e->getMessage()
            );
        }
    }
}
