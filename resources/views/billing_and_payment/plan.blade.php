@section('title') 
CodeWithUs - Dashboard
@endsection 
@extends('layouts.main')
@section('style')
<!-- Chartist Chart CSS -->
<link href="{{ asset('assets/plugins/chartist-js/chartist.min.css') }}" rel="stylesheet" type="text/css" />
<!-- Datepicker CSS -->
<link href="{{ asset('assets/plugins/datepicker/datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<style>
  .MyCardElement {
  height: 40px;
  padding: 10px 12px;
  width: 100%;
  color: #32325d;
  background-color: white;
  border: 1px solid transparent;
  border-radius: 4px;

  box-shadow: 0 1px 3px 0 #A9A9A9	;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.MyCardElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.MyCardElement--invalid {
  border-color: #fa755a;
}

.MyCardElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>

@endsection 
@section('leftbar')
    @include('layouts.parent_menu') 
@endsection 
@section('rightbar-content')
<!-- Start XP Breadcrumbbar -->                    
<div class="xp-breadcrumbbar">
    <div class="row">
        <div class="col-md-6 col-lg-6">
            <h4 class="xp-page-title">Billing And Payment</h4>
        </div>
        <div class="col-md-6 col-lg-6">
        </div>
    </div>          
</div>
<!-- End XP Breadcrumbbar -->
<!-- Start XP Contentbar -->    

<div class="xp-contentbar">
  <div class="xp-social-profile-middle text-center">
    <div class="row">
      <div class="col-12">
      <div class="card m-b-30">
        <div class="card-body">
          <form id="subscription-form" >
            <input type="hidden" name="emailId" id="emailId" value="{{ $emailid }}" />
            <input type="hidden" name="planId" id="planId" value="{{ $planid }}" />
            <div id="card-element" class="MyCardElement" >
            <!-- Elements will create input elements here -->
            </div>

            <!-- We'll put the error messages in this element -->
            <div id="card-errors" role="alert"></div>
            <br/><br/>
              <button class="btn btn-primary add"  type="submit">Enroll</button>
           
</form></div>
          
        </div> 
      </div> 
    </div>   
  </div>
</div>
<!-- End XP Contentbar -->
@endsection 
@section('script')
<script>
var stripe = Stripe('pk_test_KNoYDbEKY7eHypByTXzGPWZ1');
var elements = stripe.elements();
var style = {
  base: {
    color: "#32325d",
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: "antialiased",
    margin: "5px",
    fontSize: "16px",
    "::placeholder": {
      color: "#aab7c4"
    }
  },
  invalid: {
    color: "#fa755a",
    iconColor: "#fa755a"
  }
};

var cardElement = elements.create("card", { style: style });
cardElement.mount("#card-element");

var form = document.getElementById('subscription-form');

form.addEventListener('submit', function(event) {
  // We don't want to let default form submission happen here,
  // which would refresh the page.
  event.preventDefault();
  var emailId = document.getElementById('emailId').value;
  stripe.createPaymentMethod({
    type: 'card',
    card: cardElement,
    billing_details: {
      email: emailId.value,
    },
  }).then(stripePaymentMethodHandler);
});
function stripePaymentMethodHandler(result, email) {
  var emailId = document.getElementById('emailId').value;
  var planId = document.getElementById('planId').value;

  let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  if (result.error) {
    // Show error in payment form
  } else {
    // Otherwise send paymentMethod.id to your server
    fetch('/codewithus/make-payment', {
      method: 'post',
      headers: {
          'Content-Type': 'application/json',
          "Accept": "application/json, text-plain, */*",
          "X-Requested-With": "XMLHttpRequest",
          "X-CSRF-TOKEN": token
        },
      body: JSON.stringify({
        payment_method: result.paymentMethod.id,
        plan_id : planId
      }),
    }).then(function(result) {
      debugger;
      window.location = result.url;
     return result.json();
    }).then(function(customer) {
      // The customer has been created
    });
  }
}
</script>
<!-- Chartist Chart JS -->
<script src="{{ asset('assets/plugins/chartist-js/chartist.min.js') }}"></script>
<script src="{{ asset('assets/plugins/chartist-js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- To Do List JS -->
<script src="{{ asset('assets/js/init/to-do-list-init.js') }}"></script>
<!-- Datepicker JS -->
<script src="{{ asset('assets/plugins/datepicker/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datepicker/i18n/datepicker.en.js') }}"></script>
<!-- Dashboard JS -->
<script src="{{ asset('assets/js/init/dashborad.js') }}"></script>
@endsection 