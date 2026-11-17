<?php

namespace App\Service\Person;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PersonNotification
{
    protected string $message = 'Por motivos de seguridad, necesitamos confirmar su identidad. Su código para acceder a T-HÜMAN es: :code. Este código es válido por 5 minutos. Ingréselo para continuar. Al hacerlo, usted acepta nuestras políticas de seguridad y tratamiento de datos personales.';

    public function sendSms(string $phone, array $data = []): string|bool
    {
        try {
            $template = $this->interpolate($this->message, $data);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Account' => env('SMS_ACCOUNT', ''),
                'ApiKey' => env('SMS_API_KEY', ''),
                'Content-Type' => 'application/json',
                'Token' => env('SMS_TOKEN', ''),
            ])->post("https://api103.hablame.co/api/sms/v3/send/priority", [
                'toNumber' => "57$phone",
                'sms' => $template,
                'flash' => '0',
                'sc' => '899991',
                'request_dlvr_rcpt' => '0',
            ]);

            $data = (object) $response->json();

            if (isset($data->status) && $data->status === '1x000') {
                return $data->smsId;
            }

            throw new HttpException(422, 'No se pudo enviar el SMS: ' . ($data->error_description ?? 'Error desconocido') . ' (Código: ' . ($data->status ?? 'Sin código') . ')');
        } catch (RuntimeException $e) {
            throw new HttpException(400, 'Error de negocio al enviar SMS: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el SMS: ' . $e->getMessage());
        }
    }

    public function sendEmail(string $toEmail, string $toName, array $data = []): string|bool
    {
        try {
            $path = 'THUMAN.png';
            $image = Storage::disk('public')->get($path);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data['logo'] = 'data:image/' . $type . ';base64,' . base64_encode($image);

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
                "htmlBody" => view('Person.PersonNotifyEmail', $data)->render(),
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

    protected function interpolate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace(":$key", $value, $template);
        }

        return $template;
    }
}
