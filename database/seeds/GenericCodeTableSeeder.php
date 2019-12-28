<?php

use Illuminate\Database\Seeder;

class GenericCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '1',
            'description' => 'Created',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '2',
            'description' => 'Approved',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '3',
            'description' => 'Purchased',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '4',
            'description' => 'Received',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '5',
            'description' => 'Sold',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '6',
            'description' => 'Delivered',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '7',
            'description' => 'Rejected',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'requisitions',
            'field_name' => 'status',
            'generic_code' => '9',
            'description' => 'Closed',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'ref_type',
            'generic_code' => 'P',
            'description' => 'Purchase',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'ref_type',
            'generic_code' => 'R',
            'description' => 'Requisition',
        ]);


        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'ref_type',
            'generic_code' => 'S',
            'description' => 'Sales Invoice',
        ]);
        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'ref_type',
            'generic_code' => 'I',
            'description' => 'Import',
        ]);
        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'ref_type',
            'generic_code' => 'D',
            'description' => 'Delivery',
        ]);
        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'ref_type',
            'generic_code' => 'E',
            'description' => 'Export',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '1',
            'description' => 'Created',
        ]);
    }
}
