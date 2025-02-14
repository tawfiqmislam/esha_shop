<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SslCommerzPaymentController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->session()->get('id'), 403);

        $order = Order::find($request->session()->get('id'));
        // return $order;
        
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $order->total_amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $order->order_number; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $order->first_name.' '.$order->last_name;
        $post_data['cus_email'] = $order->email ?? 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $order->phone ?? '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = $order->order_number;
        $post_data['product_category'] = "Ecommerce";
        $post_data['product_profile'] = "physical-goods";


        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order tabel against the transaction id or order id.
        $order_details = Order::where('order_number', $tran_id)->first();

        if ($order_details->payment_status == 'pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $order_details->update(['payment_status' => 'paid']);
                
                session()->forget('cart');
                session()->forget('coupon');
                Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order_details->id]);

                session()->flash('success', 'Transaction is successfully Completed');
            } else {
                
                $order_details->update(['payment_status' => 'failed']);

                session()->flash('error', 'Validation Fail');
            }
        } else if ($order_details->payment_status == 'processing' || $order_details->payment_status == 'paid') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            if($order_details->payment_status == 'processing') {
                $order_details->update(['payment_status' => 'paid']);
                
                session()->forget('cart');
                session()->forget('coupon');
                Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order_details->id]);
            }
            session()->flash('success', 'Transaction is successfully Completed');
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            session()->flash('error', 'Invalid Transaction');
        }
        return redirect()->route('home');
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = Order::where('order_number', $tran_id)->first();

        if ($order_details->payment_status == 'pending') {
            $order_details->update(['payment_status' => 'failed']);
            session()->flash('error', 'Transaction is Failed');
        } else if ($order_details->payment_status == 'processing' || $order_details->payment_status == 'paid') {
            session()->flash('error', 'Transaction is already Successful');
        } else {
            session()->flash('error', 'Transaction is Invalid');
        }
        return redirect()->route('home');
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = Order::where('order_number', $tran_id)->first();

        if ($order_details->payment_status == 'pending') {
            $order_details->update(['payment_status' => 'cancelled']);
            session()->flash('error', 'Transaction is Cancelled');
        } else if ($order_details->payment_status == 'processing' || $order_details->payment_status == 'paid') {
            session()->flash('error', 'Transaction is already Successful');
        } else {
            session()->flash('error', 'Transaction is Invalid');
        }
        return redirect()->route('home');
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = Order::where('order_number', $tran_id)->first();

            if ($order_details->payment_status == 'pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $order_details->update(['payment_status' => 'paid']);
                }
            } else if ($order_details->payment_status == 'processing' || $order_details->payment_status == 'paid') {

                #That means Order status already updated. No need to udate database.

                // echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                // echo "Invalid Transaction";
            }
        } else {
            // echo "Invalid Data";
        }
    }

}
