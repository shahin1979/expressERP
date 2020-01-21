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
        'line_no',
        'text_position',
        'font_size',
        'texts',
        'acc_type',
        'bal_type',
        'note',
        'ac11',
        'ac12',
        'ac21',
        'ac22',
        'figure_position',
        'sub_total',
        'formula',
        'range_val1',
        'print_val1',
        'range_val2',
        'print_val2',
        'range_val3',
        'print_val3',
        'print_val',
        'pcnt',
        'negative_value',
        'import_line',
        'user_id',
    ];

    public function name()
    {
        return $this->belongsTo(StmtList::class,'file_no' ,'file_no');
    }

//    public function getFormulaAttribute($value)
//    {
//        return str_replace('+', '', $value);
//    }


}
