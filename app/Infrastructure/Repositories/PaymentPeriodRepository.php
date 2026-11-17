<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\PaymentPeriod\Contracts\PaymentPeriodContract;
use App\Models\PaymentPeriod as PaymentPeriodModel;
use App\Domain\PaymentPeriod\PaymentPeriod;
use App\Domain\PaymentPeriod\Values\PaymentPeriodCreatedAt;
use App\Domain\PaymentPeriod\Values\PaymentPeriodDeletedAt;
use App\Domain\PaymentPeriod\Values\PaymentPeriodId;
use App\Domain\PaymentPeriod\Values\PaymentPeriodName;
use App\Domain\PaymentPeriod\Values\PaymentPeriodDescription;
use App\Domain\PaymentPeriod\Values\PaymentPeriodItemId;
use App\Domain\PaymentPeriod\Values\PaymentPeriodSettings;
use App\Domain\PaymentPeriod\Values\PaymentPeriodUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PaymentPeriodRepository implements PaymentPeriodContract
{
    private $paymentPeriodModel;

    public function __construct()
    {
        $this->paymentPeriodModel = new PaymentPeriodModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $paymentPeriods = $this->paymentPeriodModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 36)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $paymentPeriods->paginate($perPage ?? $paymentPeriods->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los períodos de pago:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los períodos de pago:' . $e->getMessage());
        }
    }

    public function find(PaymentPeriodId $paymentPeriodId): ?PaymentPeriod
    {
        try {
            $model = $this->paymentPeriodModel->withTrashed()
                ->with(['item'])->where('item_id', 36)
                ->whereKey($paymentPeriodId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El período de pago no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el período de pago:' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el período de pago:' . $e->getMessage());
        }
    }

    private function mapToEntity($model): PaymentPeriod
    {
        return new PaymentPeriod(
            new PaymentPeriodId($model->id),
            new PaymentPeriodItemId($model->item?->id ?? $model->item_id),
            new PaymentPeriodName($model->name),
            new PaymentPeriodDescription($model->description),
            new PaymentPeriodSettings($model->settings),
            new PaymentPeriodCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PaymentPeriodUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PaymentPeriodDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
