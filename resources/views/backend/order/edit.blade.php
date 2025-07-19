@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <h5 class="card-header">Order Edit</h5>
  <div class="card-body">
    <form action="{{route('order.update',$order->id)}}" method="POST">
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
      </div>
      <div class="form-group" id="refund_amount_section" style="display: none;">
        <label for="status">Refund Amount :</label>
        <input type="number" name="refund_amount" class="form-control" value="{{(int)($order->total_amount + $order->delivery_charge)}}" disabled />
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</div>

<script>
  function handleStatusChange(value){
    $("#refund_amount_section").hide();
    $("#refund_amount_section input").attr("disabled","disabled");

    if(value!="refunded") return;
    
    $("#refund_amount_section").show();
    $("#refund_amount_section input").removeAttr("disabled");
  }

  document.addEventListener("DOMContentLoaded",function(){
    handleStatusChange($("#order-status").val());
  })
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
