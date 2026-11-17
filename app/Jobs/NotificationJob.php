<?php

namespace App\Jobs;

use App\Models\NotificationChannel;
use App\Models\NotificationType;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotificationJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $notificationTypeId;
    public object $data;
    public array $notificationChannelIds;
    public string $format;

    public function __construct(int $notificationTypeId, object $data, array $notificationChannelIds, string $format)
    {
        $this->notificationTypeId = $notificationTypeId;
        $this->data = $data;
        $this->notificationChannelIds = $notificationChannelIds;
        $this->format = $format;
    }

    public function handle(): ?string
    {
        $notificationType = NotificationType::findOrFail($this->notificationTypeId);

        $channels = collect($notificationType->settings->channels)->whereIn('notification_channel_id', $this->notificationChannelIds);

        foreach ($channels as $channel) {
            $notificationChannel = NotificationChannel::findOrFail($channel->notification_channel_id);

            $platform = collect($notificationChannel->settings->platforms)->firstWhere('active', true);

            if (!$platform) continue;

            $content = $this->renderTemplate($channel->message, $notificationType->settings->variables, $this->data, $this->format);

            return match($channel->notification_channel_id) {
                335 => $this->sendSms($platform, $this->data->user->person->dialing_country->phone_code.$this->data->user->person->phone_number, $content),
                336 => $this->sendEmail($platform, $this->data->user->person->email, implode(' ', [$this->data->user->person->first_name, $this->data->user->person->first_last_name]), $channel->title, $content),
                default => null
            };
        }
        return null;
    }

    public function sendSms(object $platform, string $toPhoneNumber, string $content)
    {
        try {

            if($platform->notification_platform_id == 333) {
                $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Account' => $platform->variables->account->value,
                    'ApiKey' => $platform->variables->api_key->value,
                    'Token' => $platform->variables->token->value,
                ])->post($platform->variables->url->value, [
                    'toNumber' => $toPhoneNumber,
                    'sms' => $content,
                    'flash' => '0',
                    'sc' => '899991',
                    'request_dlvr_rcpt' => '0',
                ]);

                if ($response->successful()) {
                    return $response->json('data.smsId');
                }

                throw new HttpException($response->status(), 'No se pudo enviar el SMS: ' . $response->body());
            }

            if($platform->notification_platform_id == 334) {
                $response = Http::withBasicAuth($platform->variables->user->value, $platform->variables->password->value)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($platform->variables->url->value, [
                        'to' => $toPhoneNumber,
                        'text' => $content,
                        'customdata' => 'CUS_ID_0125',
                        'isPremium' => false,
                        'isFlash' => false,
                        'isLongmessage' => true,
                        'isRandomRoute' => false,
                    ]);

                if ($response->successful()) {
                    return $response->json('messageId');
                }

                throw new HttpException($response->status(), 'No se pudo enviar el SMS: ' . $response->body());
            }

            throw new HttpException(500, 'No se pudo enviar el correo debido a que no hay canales de notificación configurados.');
        } catch (RuntimeException $e) {
            throw new HttpException(400, 'Error de negocio al enviar SMS: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el SMS: ' . $e->getMessage());
        }
    }

    public function sendEmail(object $platform, string $toEmail, string $toName, string $subject, string $content)
    {
        try {
            $path = 'THUMAN.png';
            $image = Storage::disk('public')->get($path);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $logo = 'data:image/' . $type . ';base64,' . base64_encode($image);

            if($platform->notification_platform_id == 333) {
                $response = Http::withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'X-Hablame-Key' => $platform->variables->token->value,
                ])->post($platform->variables->url->value, [
                    "sender" => [
                        "email" => $platform->variables->email->value,
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
                    "subject" => $subject,
                    "htmlBody" => view('User.UserNotifyEmail', ['content' => $content, 'logo' => $logo])->render(),
                    "priority" => false
                ]);

                if ($response->successful()) {
                    return $response->json('data.payLoad.id');
                }

                throw new HttpException($response->status(), 'No se pudo enviar el correo: ' . $response->body());
            }

            if($platform->notification_platform_id == 334) {
                $response = Http::withBasicAuth($platform->variables->user->value, $platform->variables->password->value)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($platform->variables->url->value, [
                        "Subject" => $subject,
                        "From" => $platform->variables->email->value,
                        "Template" => [
                            'Type'  => "text/html",
                            'Value' => view('User.UserNotifyEmail', ['content' => $content, 'logo' => $logo])->render(),
                        ],
                        "Recipients" => [
                            [
                                "To" => $toEmail,
                                "Name" => $toName
                            ]
                        ],

                    ]);

                if ($response->successful()) {
                    return $response->json('data.deliveryId');
                }

                throw new HttpException($response->status(), 'No se pudo enviar el correo: ' . $response->body());
            }

            throw new HttpException(500, 'No se pudo enviar el correo debido a que no hay canales de notificación configurados.');
        } catch (RuntimeException $e) {
            throw new HttpException(400, 'Error de negocio al enviar correo: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el correo: ' . $e->getMessage());
        }
    }

    function renderTemplate(string $content, array $variablesConfig, object $data, string $format): string
    {
        $map = [];

        foreach ($variablesConfig as $variable) {
            $variable = (object) $variable;
            $basePath = $variable->path ?? null;
            $baseData = $basePath ? data_get($data, $basePath) : $data;
            foreach ($variable->groups as $group) {
                $group = (object) $group;
                $groupPath = $group->path ?? null;
                $groupData = $groupPath ? data_get($baseData, $groupPath) : $baseData;
                foreach ($group->fields as $field) {
                    $field = (object) $field;
                    $value = data_get($groupData, $field->field);
                    $map[$field->key] = $value ?? '';
                }
            }
        }

        return match ($format) {
            'html' => preg_replace_callback('/<span class="var-chip"[^>]*data-key="([^"]+)"[^>]*>.*?<\/span>/s', fn ($match) => $map[$match[1]] ?? '', $content),
            'text' => (function () use ($content, $map) {
                $text = preg_replace_callback('/<span class="var-chip"[^>]*data-key="([^"]+)"[^>]*>.*?<\/span>/s', fn ($match) => $map[$match[1]] ?? '', $content);
                $text = strip_tags($text);
                $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $text = preg_replace('/\s+/', ' ', $text);
                return trim($text);
            })(),
            default => $content,
        };
    }
}
