<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Stripe\Stripe;
use App\Stripe as stripeModel;
use Auth;
use App\User;
use App\UserParent;
use App\Configuration;
use App\Role;

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
            $plans = $this->getPlans();
            return view('billing_and_payment.parents_billing', compact('plans'))->withError("");
        }
    }

    public function getPlans(){
        $user = Auth::user();
        $phoneNumber = $user->phone_number;
        $roleId = Role::where('role','student')->value('id');
        $getStudentsOfThisParent = User::where(['phone_number' => $phoneNumber,
                                                'role_id' =>  $roleId ])->get();
        $plans = array();
        foreach($getStudentsOfThisParent as $student){
            $locations = $student->locations()->get();
            foreach($locations as $location){
                $plansData = stripeModel::where('location_id', $location->id)->get();
                foreach($plansData as $plan){
                    $url = "/codewithus/plan/".$plan->product_id."";
                    $dataArray = ["id" =>$plan->id,
                                "product_id" => $plan->product_id,
                                "product_name" => $plan->product_name,
                                "url" => $url,
                                ];
                    array_push($plans,$dataArray);           
                }
            }
        }
        return $plans;
    }
    public function showPlan($planId, Request $request){
        $user = Auth::user();
        $emailId = $user->email;
     
        return view('billing_and_payment.plan')->withPlanid($planId)->withEmailid($emailId);    
    }
    
    public function addEmailId(Request $request){
        $user = Auth::user();
        $user->email = $request->email;
        $user->save();

        return redirect()->route('plans.index');
    }

    public function makePayment(Request $request){

        $stripeTestSecretKey = Configuration::where('key', 'stripe_test_secret_key')->value('value');
        \Stripe\Stripe::setApiKey($stripeTestSecretKey);

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
        
        // $subscription = \Stripe\Subscription::create([
        //     'customer' => $stripeCustomerId,
        //     'items' => [
        //         [
        //             'plan' => $request->plan_id,
        //         ],
        //     ],
        //     'expand' => ['latest_invoice.payment_intent'],
        // ]);  
        
        return redirect()->route('thankyou.page');
    }
    public function thankyouPage(){
        return view('billing_and_payment.parents_thankyou');
    }

    public function showPromo(Request $request){
        $promo = stripeModel::where(['password' => $request->promo_code,
                                     'is_promo' => true])->first();
        if($promo != null){
            $promo_id = $promo->product_id;
            $user = Auth::user();
            $emailId = $user->email;    
            return view('billing_and_payment.plan')->withPlanid($promo_id)->withEmailid($emailId);    
        }
        else{
            $plans = $this->getPlans();
            return view('billing_and_payment.parents_billing', compact('plans'))->withError("No such promotion exists.");
        }
    }
}
