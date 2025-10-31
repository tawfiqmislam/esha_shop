@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <div class="row">
      <div class="col-md-12">
        @include('backend.layouts.notification')
      </div>
  </div>
  <h5 class="card-header">Order Edit</h5>
  <div class="card-body">
    <form action="{{route('order.update',$order->id)}}" method="POST" id="order-update-form">
      @csrf
      @method('PATCH')
      <div class="form-group">
        <label for="status">Status :</label>
        <select name="status" id="order-status" class="form-control" onchange="handleStatusChange(this.value)">
          <option value="new" {{($order->status=='delivered' || $order->status=="process" || $order->status=="cancel") ? 'disabled' : ''}}  {{(($order->status=='new')? 'selected' : '')}}>New</option>
          <option value="process" {{($order->status=='delivered'|| $order->status=="cancel") ? 'disabled' : ''}}  {{(($order->status=='process')? 'selected' : '')}}>process</option>
          <option value="delivered" {{($order->status=="cancel") ? 'disabled' : ''}}  {{(($order->status=='delivered')? 'selected' : '')}}>Delivered</option>
          <option value="cancel" {{($order->status=='refunded') ? 'disabled' : ''}}  {{(($order->status=='cancel')? 'selected' : '')}}>Cancel</option>
          <option value="refunded" {{($order->status=='refunded') ? 'disabled' : ''}}  {{(($order->status=='refunded')? 'selected' : '')}}>Refunded</option>
        </select>
        <span class="text-info" id="send-sms-label" style="display: none;">We try to send a delivered confirmation SMS to {{ $order->phone }}</span>
      </div>
      <div class="form-group" id="send-sms-section" style="display: none;">
        <button class="btn btn-info d-block" type="button" id="send-sms">
          Send SMS 
          <img src="{{ asset('images/loader.svg') }}" alt="..." style="max-width: 30px; display: none;" />
        </button>
      </div>
      <div class="form-group" id="sms-code-section" style="display: none;">
        <label for="sms_code">
          SMS Code : 
          <span class="text-danger" id="sms-code-error"></span>
        </label>
        <input type="number" name="sms_code" id="sms-code" class="form-control" required disabled />
      </div>
      <div class="form-group" id="refund_amount_section" style="display: none;">
        <label for="status">Refund Amount :</label>
        <input type="number" name="refund_amount" class="form-control" value="{{ $order->payment_method == 'cod' ? $order->delivery_charge : (int)$order->total_amount }}" disabled />
      </div>
      <div class="form-group" id="submit-btn-section">
        <button type="submit" class="btn btn-primary">
          Update 
          <img src="{{ asset('images/loader.svg') }}" alt="..." style="max-width: 30px; display: none;" />
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  const _orderStatus = "{{ $order->status }}";
  
  function handleStatusChange(value, firstLoad=false){
    $("#submit-btn-section").show();
    $("#refund_amount_section").hide();
    $("#refund_amount_section input, #sms-code").attr("disabled","disabled");
    $("#send-sms-label, #send-sms-section, #sms-code-section").hide();

    if(!firstLoad && value=="delivered") {
      $("#send-sms-label, #send-sms-section").show();
      $("#submit-btn-section").hide();
    }

    if(value!="refunded") return;
    
    $("#refund_amount_section").show();
    $("#refund_amount_section input").removeAttr("disabled");
  }

  const orderStatusField = document.getElementById("order-status");

  document.addEventListener("DOMContentLoaded",function() {
    const orderStatus = orderStatusField.value;
    handleStatusChange(orderStatus, _orderStatus == orderStatus);
  })

  const orderUpdateForm = document.getElementById("order-update-form");
  const submitBtnSection = document.getElementById("submit-btn-section");
  const submitBtn = document.querySelector('#submit-btn-section button');
  const submitBtnLoader = document.querySelector('#submit-btn-section button img');
  const sendSmsSection = document.getElementById("send-sms-section");
  const sendSmsBtn = document.getElementById("send-sms");
  const sendSmsBtnLoader = document.querySelector('#send-sms img')
  const smsCodeSection = document.getElementById("sms-code-section");
  const smsCodeInput = document.getElementById("sms-code");
  const smsCodeError = document.getElementById("sms-code-error");

  sendSmsBtn.addEventListener("click", async function() {

    sendSmsBtn.setAttribute('disabled', 'disabled');
    sendSmsBtnLoader.style.display = 'inline-block';
    smsCodeError.innerText = '';

    let apiStatus = false;
    try {
      const response = await sendSmsRequest();
      
      if(response.success == 1) {
        apiStatus = true;
        return;
      }
      alert(response.message);

    } catch (error) {
      alert("Something went wrong, please try again.");
      console.error(error);

    } finally {
      sendSmsBtn.removeAttribute('disabled');
      sendSmsBtnLoader.style.display = 'none';

      if(apiStatus) {
        smsCodeInput.removeAttribute('disabled');
        sendSmsSection.style.display = 'none';
        smsCodeSection.style.display = 'block';
        submitBtnSection.style.display = 'block';
      }
    }
  })

  orderUpdateForm.addEventListener("submit", async function(e) {

    if(orderStatusField.value != "delivered") return;

    e.preventDefault();
    try {
      submitBtn.setAttribute('disabled', 'disabled');
      submitBtnLoader.style.display = 'inline-block';

      const response = await checkSmsCodeRequest();
      if(response.success == 1) {
        orderUpdateForm.submit();
        return;
      }
      smsCodeError.innerText = response.message;
      
    } catch (error) {
      alert("Something went wrong, please try again.");
      console.error(error);
    } finally {
      submitBtn.removeAttribute('disabled');
      submitBtnLoader.style.display = 'none';
    }
  })

  async function sendSmsRequest() {
    const response = await fetch("{{ $sendCodeUri }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    });
    return response.json();
  }

  async function checkSmsCodeRequest() {
    const response = await fetch("{{ $checkCodeUri }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        code: smsCodeInput.value
      })
    });
    return response.json();
  }

</script>

@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
