<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HL7Controller;

class ReportsPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To get the payment status of fetched lab reports';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reports = DB::table('quest_results')->where('flag','0')->get();
        if(count($reports)!=0)
        {
            $hl7_obj = new HL7Controller();
            foreach($reports as $report)
            {
                $decoded = $hl7_obj->hl7Decode($report->hl7_message);
                $test_info = DB::table('quest_data_test_codes')
                ->where('TEST_NAME',$decoded['arrOBR'][0]['name'])->first();
                if($decoded['performed'][0]=="Test performed")
                {
                    $lab_order = DB::table('lab_orders')->where('product_id',$test_info->TEST_CD)
                    ->where('order_id',$decoded['arrOBR'][0]['placer_order_num'])
                    ->update(['vendor_fee'=>'unpaid','status'=>'completed']);
                    DB::table('quest_results')->where('id',$report->id)->update(['flag'=>'1']);
                    $lab_order = DB::table('lab_orders')->where('product_id',$test_info->TEST_CD)
                    ->where('order_id',$decoded['arrOBR'][0]['placer_order_num'])
                    ->first();
                    $profit = $test_info->SALE_PRICE - $test_info->PRICE - 8;
                    DB::table('quest_invoices')->insert([
                        "Order_id"=>$lab_order->order_id,
                        "Services"=>$test_info->TEST_NAME,
                        "Amount"=>$test_info->PRICE,
                        "Draw_fee"=>8,
                        "Profit"=>$profit,
                        "Status"=>'incomplete',
                        "created_at"=>date('Y-m-d H:i:s'),
                        "updated_at"=>date('Y-m-d H:i:s'),
                    ]);
                }
                else
                {
                    $lab_order = DB::table('lab_orders')->where('product_id',$test_info->TEST_CD)
                    ->where('order_id',$decoded['arrOBR'][0]['placer_order_num'])
                    ->update(['vendor_fee'=>'not performed','status'=>'not_performed']);
                    DB::table('quest_results')->where('id',$report->id)->update(['flag'=>'1']);
                }
            }
        }
    }
}
