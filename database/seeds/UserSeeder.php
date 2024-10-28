<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    {
        DB::table('users')->insert(
            [
                [
                    'user_type' => 'admin',
                    'name' => 'Super',
                    'last_name' => 'Admin',
                    'email' => 'admin@medical.com',
                    'password' => bcrypt('12345678'),
                    'date_of_birth' => '18/02/1890',
                    'phone_number' => '374682348',
                    'office_address' => 'asdhgkshf',
                    'nip_number' => '',
                    'upin' => '',
                    'specialization' => '',
                    'status' => '',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'user_type' => 'doctor',
                    'name' => 'Dr. Amir',
                    'last_name' => 'Pare',
                    'email' => 'doctor@medical.com',
                    'password' => bcrypt('12345678'),
                    'date_of_birth' => '18/02/1890',
                    'phone_number' => '374682348',
                    'office_address' => 'asdhgkshf',
                    'nip_number' => '489353',
                    'upin' => '3748264912',
                    'specialization' => 'none',
                    'status' => 'offline',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'user_type' => 'doctor',
                    'name' => 'Dr. Jane',
                    'last_name' => 'Edward',
                    'email' => 'doc2@medical.com',
                    'password' => bcrypt('12345678'),
                    'date_of_birth' => '18/02/1890',
                    'phone_number' => '374682348',
                    'office_address' => 'asdhgkshf',
                    'nip_number' => '489395',
                    'upin' => '3748264914',
                    'specialization' => 'none',
                    'status' => 'online',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'user_type' => 'patient',
                    'name' => 'Sara',
                    'last_name' => 'Khan',
                    'email' => 'patient@medical.com',
                    'password' => bcrypt('12345678'),
                    'date_of_birth' => '18/02/1890',
                    'phone_number' => '374682348',
                    'office_address' => 'asdhgkshf',
                    'nip_number' => '',
                    'upin' => '',
                    'specialization' => '',
                    'status' => '',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'user_type' => 'patient',
                    'name' => 'Ali',
                    'last_name' => 'Malik',
                    'email' => 'pat2@medical.com',
                    'password' => bcrypt('12345678'),
                    'date_of_birth' => '18/02/1890',
                    'phone_number' => '374682348',
                    'office_address' => 'asdhgkshf',
                    'nip_number' => '',
                    'upin' => '',
                    'specialization' => '',
                    'status' => '',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]
        );
    }

}
