<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Permission;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $person = new Person();
        $person->first_name = 'Super';
        $person->second_name = null;
        $person->first_last_name = 'Administrador';
        $person->second_last_name = null;
        $person->birth_date = '1990-01-01';
        $person->birth_city_id = 20772;
        $person->document_type_id = 12;
        $person->document_issue_city_id = 20772;
        $person->document_number = '1000000000';
        $person->document_issue_date = '2010-01-01';
        $person->city_id = 20772;
        $person->address = 'Dirección';
        $person->neighborhood = 'Barrio';
        $person->dialing_country_id = 48;
        $person->phone_number = '3000000000';
        $person->phone_number_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $person->email = 'superadmin@thuman.com';
        $person->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
        $person->sex_id = 42;
        $person->gender_id = 44;
        $person->blood_type_id = 56;
        $person->marital_status_id = 57;
        $person->save();

        $user = new User();
        $user->person_id = $person->id;
        $user->password = Hash::make(env('SUPERADMIN_PASSWORD', 'CAMBIAR_CLAVE_SUPERADMIN'));
        $user->save();

        $employee = new Employee();
        $employee->person_id = $person->id;
        $employee->headquarter_id = null;
        $employee->position_id = null;
        $employee->start_date = Carbon::now()->format('Y-m-d');
        $employee->end_date = null;
        $employee->settings = [];
        $employee->save();

        $roles = Role::all();
        $user->syncRoles($roles);

        $permissions = Permission::all();
        $user->syncPermissions($permissions);
    }
}
