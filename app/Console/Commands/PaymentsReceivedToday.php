<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Stripe\Stripe;
use Carbon\Carbon;
use App\Stripe as StripeModel;
use App\Credit;
use App\TaskClassType;

class PaymentsReceivedToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments_received:today';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Job fetches details of all the payments which were made today by the parents';

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
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::today();
        $todayUT = strtotime($today);
        $now = Carbon::now();
        $nowDateTime =date('Y-m-d H:i:s',strtotime($now));
        \Stripe\Stripe::setApiKey('sk_live_0VSsL4U2pIIGNevF5xmQwCJJ');

        $listOfPayments = \Stripe\Charge::all(['created[gte]' => $todayUT]);

        foreach($listOfPayments as $payment){
            $emailId = $payment->receipt_email;
            $amount = ($payment->amount)/100;
            $paymentId = $payment->payment_method;

            $invoice = \Stripe\Invoice::retrieve($payment->invoice);
            $product_id = $invoice->lines->data[0]->plan->product;
            
            $stripeData = StripeModel::where('product_id',$product_id)->first();
            $credits = $stripeData->number_of_credits;
            $taskClassTypeId = $stripeData->task_class_type_id;

            $creditModelObj = Credit::where(['customer_email' =>$emailId ,
                                             'task_class_type_id' =>$taskClassTypeId])->first();
            if($creditModelObj == null){
                $creditObj = new Credit();
                $creditObj->remaining_credits = $credits;
                $creditObj->credits_given_date =$nowDateTime;
                $creditObj->customer_email = $emailId;
                $creditObj->payment_id = $paymentId;
                $creditObj->product_id = $product_id;
                $creditObj->task_class_type_id = $taskClassTypeId;
                $creditObj->save();
            }
            else{
                $alreadyAvailableCredits = $creditModelObj->remaining_credits;
                $creditModelObj->remaining_credits = $alreadyAvailableCredits + $credits;
                $creditModelObj->credits_given_date =$nowDateTime;
                $creditModelObj->payment_id = $paymentId;
                $creditModelObj->product_id = $product_id;
                $creditModelObj->save();
            }
        }
    }
}
