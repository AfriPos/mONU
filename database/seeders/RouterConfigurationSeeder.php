<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouterConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define issues
        $issues = [
            'MAC Configuration' => 'su\nset wanmac mac {base_mac}\ndisplay wanmac\nshell\nreboot',
            'Firmware Update' => 'Download the latest firmware from Huawei support and update via web interface.',
        ];

        // Insert issues into issue_types and get their IDs
        $issueIds = [];
        foreach ($issues as $issue => $config) {
            $issueIds[$issue] = DB::table('issue_types')->insertGetId([
                'issue' => $issue,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Get all router models
        $routerModels = DB::table('router_models')->get();

        // Insert router configurations
        $data = [];
        foreach ($routerModels as $router) {
            foreach ($issues as $issue => $config) {
                $data[] = [
                    'router_model_id' => $router->id,
                    'issue_id' => $issueIds[$issue], // Use issue_id instead of issue text
                    'configuration' => $config,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert data into router_configurations table
        DB::table('router_configurations')->insert($data);
    }
}
