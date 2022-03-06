<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),

            ],
            [
                'name' => 'client',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'employee',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ]);

        DB::table('permissions')->insert([
           [
               'name' => 'add-employee',
               'guard_name' => 'api',
               'created_at' => Carbon::now()->toDateTimeString(),
               'updated_at' => Carbon::now()->toDateTimeString(),
           ],
            [
                'name' => 'delete-employee',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'add-client',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'delete-client',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'disable-user',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'force-list-categories',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'force-list-products',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'add-edit-employee',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'buy-products',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'show-products-in-cart',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'edit-products-in-cart',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ],
            [
                'name' => 'remove-products-from-cart',
                'guard_name' => 'api',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ]);

        $allPermissions = Permission::all(['id'])->pluck('id')->toArray();
        Role::query()->where('name','admin')->first()->syncPermissions($allPermissions);

        $somePermissions= Permission::query()
            ->whereIn('name',['force-list-categories', 'force-list-products'])
            ->get(['id'])->pluck('id')->toArray();
        Role::query()->where('name','employee')->first()->syncPermissions($somePermissions);

        $clientsPermissions = Permission::query()
            ->whereIn('name', ['buy-products', 'show-products-in-cart', 'edit-products-in-cart', 'remove-products-from-cart'])
            ->get(['id'])->pluck('id')->toArray();
        Role::query()->where('name','client')->first()->syncPermissions($clientsPermissions);
    }
}
