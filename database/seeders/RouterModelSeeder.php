<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouterModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routers = [
            // **Huawei GPON Models**
            ['brand' => 'Huawei', 'model_name' => 'HG8240H', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8245H', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8247H', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8546M', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8310M', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8346M', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8245Q', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'HG8247Q', 'default_username' => 'root', 'default_password' => 'admin'],

            // **Huawei EPON Models**
            ['brand' => 'Huawei', 'model_name' => 'MA5608T', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5680T', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5683T', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5671', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5675', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5626', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5620', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'MA5628', 'default_username' => 'root', 'default_password' => 'admin'],

            // **Additional Huawei ONU Models**
            ['brand' => 'Huawei', 'model_name' => 'EchoLife EG8145V5', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'EchoLife HG8245A', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'EchoLife HG8342R', 'default_username' => 'root', 'default_password' => 'admin'],
            ['brand' => 'Huawei', 'model_name' => 'EchoLife HG8045Q', 'default_username' => 'root', 'default_password' => 'admin'],
        ];

        // Insert data into the router_models table
        DB::table('router_models')->insert($routers);
    }
}
