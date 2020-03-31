<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Stripe as stripeModel;
use Auth;
use App\User;
use App\UserParent;

class BillingAndPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:teacher|admin|parent');
    }

    public function plans(){
        $user = Auth::user();
        $emailId = $user->email;
        if($emailId == null || $emailId == ""){
            return view('billing_and_payment.parents_email');
        }
        else{
            $plansData = stripeModel::all();
            $plans = array();
            foreach($plansData as $plan){
                $url = "/codewithus/plan/".$plan->product_id."";
                $dataArray = ["id" =>$plan->id,
                            "product_id" => $plan->product_id,
                            "product_name" => $plan->product_name,
                            "url" => $url,
                            ];
                array_push($plans,$dataArray);           
            }
            return view('billing_and_payment.parents_billing', compact('plans'));
        }
    }

    public function showPlan($planId, Request $request){
        $user = Auth::user();
        $emailId = $user->email;
        // $plan = stripeModel::where('product_id',$planId)->first();
        return view('billing_and_payment.plan')->withPlanid($planId)->withEmailid($emailId);    
    }
    
    public function addEmailId(Request $request){
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        return redirect()->route('plans.index');
    }

    public function makePayment(Request $request){

        \Stripe\Stripe::setApiKey('sk_test_eFoYt2xRkwYbgQPtDwm952ko');

        //Check if customer already exists in Asana
        $user = Auth::user();
        $userId = $user->id;
        $emailId = $user->email;
        $parent = UserParent::where('user_id',$userId)->first();
        $parentStripeId = "";

        if($parent != null){
            $parentStripeId = $parent->stripe_customer_id;
        }    
        if($parent == null || $parentStripeId == null || $parentStripeId == ""){
            // This creates a new Customer and attaches the default PaymentMethod in one API call.
            $stripeCustomer = \Stripe\Customer::create([
                'payment_method' => $request->payment_method,
                'email' => $emailId,
                'invoice_settings' => [
                    'default_payment_method' => $request->payment_method
                ]
            ]);
            $stripeCustomerId = $stripeCustomer->id;

            $newParentRecord = new UserParent();
            $newParentRecord->user_id = $userId;
            $newParentRecord->stripe_customer_id = $stripeCustomerId;
            $newParentRecord->save();
        }
        else{
            $stripeCustomerId = $parentStripeId;
        }
        
        $subscription = \Stripe\Subscription::create([
            'customer' => $stripeCustomerId,
            'items' => [
                [
                    'plan' => $request->plan_id,
                ],
            ],
            'expand' => ['latest_invoice.payment_intent'],
        ]);  
    }
}
