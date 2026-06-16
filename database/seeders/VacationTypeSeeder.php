<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class VacationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        DB::table('vacation_types')->insert([
            [
                'id' => 1,
                'name' => 'مبيت',
            ],
            [
                'id' => 2,
                'name' => 'مستشفى',
            ],
            [
                'id' => 3,
                'name' => 'ميدانية',
            ],
            [
                'id' => 4,
                'name' => 'منحة قائد',
            ],
            [
                'id' => 5,
                'name' => 'مأمورية',
            ],
            [
                'id' => 6,
                'name' => 'راحة',
            ],
            [
                'id' => 7,
                'name' => 'سفر خارج البلاد',
            ],
            [
                'id' => 8,
                'name' => 'اجازة مرضى',
            ],
            [
                'id' => 9,
                'name' => 'اجازة بدون مرتب',
            ],
            [
                'id' => 10,
                'name' => 'اجازة سنوية',
            ],
            [
                'id' => 11,
                'name' => 'اجازة عارضة',
            ],
            [
                'id' => 12,
                'name' => 'غير موجود',
            ],
            
        ]);
    }

}
