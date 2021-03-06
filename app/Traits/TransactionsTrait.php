<?php


namespace App\Traits;


use App\Models\Accounts\Ledger\GeneralLedger;
use App\Models\Accounts\Trans\Transaction;
use App\Models\Company\FiscalPeriod;
use App\Models\Inventory\Product\ItemTax;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait TransactionsTrait
{
    use AccountTrait;

    public function get_fiscal_year($date,$company_id)
    {
        $cal_date = Carbon::createFromFormat('d-m-Y',$date)->format('Y-m-d');

        $data = FiscalPeriod::query()->where('company_id',$company_id)
            ->whereDate('start_date','<=',$cal_date)
            ->whereDate('end_date','>=',$cal_date)->first();

        return $data->fiscal_year;

    }

    public function get_fiscal_data_from_current_date($company_id)
    {
        $cal_date = Carbon::now()->format('Y-m-d');

        $fiscal = FiscalPeriod::query()->where('company_id',$company_id)
            ->whereDate('start_date','<=',$cal_date)
            ->whereDate('end_date','>=',$cal_date)->first();

        return $fiscal;

    }

    public function create_fiscal_year($date)
    {
        $f_y1 = Carbon::createFromFormat('d-m-Y',$date)->format('Y');
        $f_y2 = Carbon::createFromFormat('d-m-Y',$date)->addMonth(11)->format('Y');
        $fiscal_year = $f_y1.'-'.$f_y2;

        return $fiscal_year;
    }

    // Date comes from Database

    public function get_fiscal_year_db_date($company_id, $date)
    {
        $line = FiscalPeriod::query()->where('company_id',$company_id)
            ->whereDate('start_date','<=',$date)
            ->whereDate('end_date','>=',$date)->first();

        return $line->fiscal_year;
    }

    public function create_fiscal_period($company_id,$date)
    {

        $start_date = Carbon::createFromFormat('d-m-Y',$date)->format('Y-m-d');
        $fiscal_period = $this->create_fiscal_year($date);

        for ($m=1; $m <=12; $m++) {

            $year = Carbon::parse($start_date)->format('Y');
            $fpNo= $m;

            $month_sl = Carbon::parse($start_date)->format('m');
            $month = date('F', mktime(0,0,0,$month_sl, 1, date('Y')));
            $status = 'A';


            FiscalPeriod::query()->create([
                'company_id' => $company_id,
                'fiscal_year' => $fiscal_period,
                'year' =>$year,
                'fp_no' => $fpNo,
                'month_serial' => $month_sl,
                'month_name' => $month,
                'start_date' => Carbon::parse($start_date)->format('Y-m-d'),
                'end_date' => Carbon::parse($start_date)->format( 'Y-m-t' ),
                'status' => $status,
                'depreciation' => False,
            ]);

            $start_date = Carbon::parse($start_date)->addMonth(1);

        }

        return $start_date;
    }

    public function transaction_entry(array $input)

    {
        $id = null;

        if($input['trans_amt'] > 0)
        {
            $id = Transaction::query()->create($input);

            if($input['dr_amt'] > 0)
            {
                GeneralLedger::query()->where('acc_no',$input['acc_no'])
                    ->where('company_id',$this->company_id)
                    ->increment('dr_00', $input['dr_amt']);

                GeneralLedger::query()->where('acc_no',$input['acc_no'])
                    ->where('company_id',$this->company_id)
                    ->increment('curr_bal', $input['dr_amt']);

                GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->increment('dr_00', $input['dr_amt']);


                GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->increment('curr_bal', $input['dr_amt']);
            }
//
            if($input['cr_amt'] > 0)
            {
                GeneralLedger::query()->where('acc_no',$input['acc_no'])
                    ->where('company_id',$this->company_id)
                    ->increment('cr_00', $input['cr_amt']);

                GeneralLedger::query()->where('acc_no',$input['acc_no'])
                    ->where('company_id',$this->company_id)
                    ->decrement('curr_bal', $input['cr_amt']);

                GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->increment('cr_00', $input['cr_amt']);

                GeneralLedger::query()->where('ledger_code',$input['ledger_code'])
                    ->where('company_id',$this->company_id)
                    ->where('is_group',true)
                    ->decrement('curr_bal', $input['cr_amt']);

            }
        }
        return $id;
    }

    public function tax_amount($tax_id, $price, $quantity)
    {
        $tax = ItemTax::query()->where('id',$tax_id)->first();
        $item_tax_total = 0;
        if(!empty($tax))
        {
            switch ($tax->calculating_mode)
            {
                case 'P':
                    $item_tax_total = (($price * $quantity) / 100) * $tax->rate;
                    break;

                case 'F':
                    $item_tax_total = $quantity*$tax->rate;
                    break;

                default:
                    $item_tax_total = 0;
            }
        }

        return $item_tax_total;
    }

    public function get_delivery_transactions_array(Collection $items, $company_id)
    {
        $temp = collect();

        foreach ($items as $product)
        {
            $acc_cr = $this->get_in_stock_gl_head($product->product_id,$company_id);// $product->item->subcategory->acc_in_stock;
            $acc_dr = $this->get_stock_out_gl_head($product->product_id,$company_id); //$product->item->subcategory->acc_out_stock
            $input = [];
            $input['acc_cr'] = $acc_cr;
            $input['acc_dr'] = $acc_dr;
            $input['amount'] = $this->get_average_price($product->product_id,$company_id) * $product->quantity;
            $temp->push($input);
        }

        $temp1 = $temp->groupBy('acc_cr')->map(function ($row)  {
            $grouped = Collect();
            $grouped->tr_amount = $row->sum('amount');
            $grouped->push($row);
            return $grouped;
        });

        $temp2 = $temp->groupBy('acc_dr')->map(function ($row)  {
            $grouped = Collect();
            $grouped->tr_amount = $row->sum('amount');
            $grouped->push($row);
            return $grouped;
        });

        $deliveries = collect();

        foreach ($temp1 as $head=>$id)
        {
            $acc_name = $this->get_account_name($this->company_id,$head);
            $line = [];
            $line['gl_head'] = $head;
            $line['acc_name'] = $line['gl_head'].' : '.$acc_name;
            $line['debit_amt'] = 0;
            $line['credit_amt'] = $id->tr_amount;
            $deliveries->push($line);
        }

        foreach ($temp2 as $head=>$id)
        {
            $acc_name = $this->get_account_name($this->company_id,$head);
            $line = [];
            $line['gl_head'] = $head;
            $line['acc_name'] = $line['gl_head'].' : '.$acc_name;
            $line['debit_amt'] = $id->tr_amount;
            $line['credit_amt'] = 0;
            $deliveries->push($line);
        }

        return $deliveries;
    }


}
