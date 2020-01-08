<?php

namespace App\Models\Accounts\Statement;

use Illuminate\Database\Eloquent\Model;

class StmtLine extends Model
{
    protected $table = "stmt_lines";

    protected $guarded = ['id', 'created_at','updated_at'];

    protected $fillable = [
        'company_id',
        'file_no',
        'file_desc',
        'import_file',
        'import_line',
        'into_line',
        'base_formula',
        'import_value',
        'order_sequence',
        'user_id',
        'value_date',
    ];
}
