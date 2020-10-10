<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenericCodeTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
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

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '2',
            'description' => 'Approved',
        ]);
        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '3',
            'description' => 'Purchased',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '4',
            'description' => 'Received',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '5',
            'description' => 'Delivered',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '6',
            'description' => 'Rejected',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'trans_products',
            'field_name' => 'status',
            'generic_code' => '7',
            'description' => 'Closed',
        ]);


//        PURCHASE TABLE
        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'CR',
            'description' => 'Created',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'AP',
            'description' => 'Approved',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'RC',
            'description' => 'Received',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'PR',
            'description' => 'Purchased',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'DL',
            'description' => 'Delivered',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'RJ',
            'description' => 'Rejected',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'RT',
            'description' => 'Returned',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'status',
            'generic_code' => 'CL',
            'description' => 'Closed',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'purchase_type',
            'generic_code' => 'CP',
            'description' => 'Cash Purchase',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'purchase_type',
            'generic_code' => 'CP',
            'description' => 'Cash Purchase',
        ]);

        DB::table('generic_codes')->insert([
            'table_name' => 'purchases',
            'field_name' => 'purchase_type',
            'generic_code' => 'LP',
            'description' => 'Local Credit Purchase',
        ]);

// Cost Types Table


    }
}
