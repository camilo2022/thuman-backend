<?php

namespace App\Observers\Enterprise\Entity;

use App\Jobs\NotificationJob;
use App\Models\Employee;
use App\Models\Entity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EntityObserver
{
    /**
     * Handle the Entity "created" event.
     */
    public function created(Entity $entity): void
    {
        $this->createUserIfNotExists($entity);
    }

    /**
     * Handle the Entity "updated" event.
     */
    public function updated(Entity $entity): void
    {
        $this->createUserIfNotExists($entity);
    }

    /**
     * Handle the Entity "deleted" event.
     */
    public function deleted(Entity $entity): void
    {
        //
    }

    /**
     * Handle the Entity "restored" event.
     */
    public function restored(Entity $entity): void
    {
        //
    }

    /**
     * Handle the Entity "force deleted" event.
     */
    public function forceDeleted(Entity $entity): void
    {
        //
    }

    protected function createUserIfNotExists(Entity $entity): void
    {
        $person = $entity->person;

        if (!$person) return;

        if ($person->user) return;

        $password = Str::random(16);

        $user = new User();
        $user->person_id = $person->id;
        $user->password = Hash::make($password);
        $user->save();

        $employee = new Employee();
        $employee->person_id = $person->id;
        $employee->headquarter_id = null;
        $employee->position_id = null;
        $employee->start_date = Carbon::now()->format('Y-m-d');
        $employee->end_date = null;
        $employee->settings = [];
        $employee->save();

        $data = (object) [
            'user' => $user,
            'password' => $password
        ];

        NotificationJob::dispatchSync(360, $data, [336], 'html');
        NotificationJob::dispatchSync(361, $data, [336], 'html');
    }
}
