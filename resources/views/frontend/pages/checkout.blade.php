@extends('frontend.layouts.master')

@section('title', 'Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <form class="form" method="POST" action="{{ route('cart.order') }}" id="cart-order-form">
                @csrf
                <div class="row">

                    <div class="col-lg-8 col-12">
                        <div class="checkout-form">
                            <h2>Make Your Checkout Here</h2>
                            <p>Please register in order to checkout more quickly</p>
                            <!-- Form -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>First Name<span>*</span></label>
                                        <input type="text" name="first_name" placeholder=""
                                            value="{{ old('first_name') }}" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Last Name<span>*</span></label>
                                        <input type="text" name="last_name" placeholder="" value="{{ old('lat_name') }}" required>
                                        @error('last_name')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Email Address<span>*</span></label>
                                        <input type="email" name="email" placeholder="" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Phone Number <span>*</span></label>
                                        <input type="hidden" name="phone" id="phone" value="{{ old('phone') }}">
                                        <input type="tel" 
                                            id="formatted_phone"
                                            name="formatted_phone"
                                            placeholder="e.g. 01712345678" 
                                            maxlength="13"
                                            value="{{ old('phone') }}"
                                            required>
                                        @error('phone')
                                            <span id="phone_error" class='text-danger'>{{ $message }}</span>
                                        @else
                                            <span id="phone_error" class='text-danger' style="display: none"></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>District<span>*</span></label>
                                        <select name="district" id="district" class="required">
                                            <option value="">Select District</option>
                                            <option>Dhaka</option>
                                            <option>Faridpur</option>
                                            <option>Gazipur</option>
                                            <option>Gopalganj</option>
                                            <option>Jamalpur</option>
                                            <option>Kishoreganj</option>
                                            <option>Madaripur</option>
                                            <option>Manikganj</option>
                                            <option>Munshiganj</option>
                                            <option>Mymensingh</option>
                                            <option>Narayanganj</option>
                                            <option>Narsingdi</option>
                                            <option>Netrokona</option>
                                            <option>Rajbari</option>
                                            <option>Shariatpur</option>
                                            <option>Sherpur</option>
                                            <option>Tangail</option>
                                            <option>Bogura</option>
                                            <option>Joypurhat</option>
                                            <option>Naogaon</option>
                                            <option>Natore</option>
                                            <option>Nawabganj</option>
                                            <option>Pabna</option>
                                            <option>Rajshahi</option>
                                            <option>Sirajgonj</option>
                                            <option>Dinajpur</option>
                                            <option>Gaibandha</option>
                                            <option>Kurigram</option>
                                            <option>Lalmonirhat</option>
                                            <option>Nilphamari</option>
                                            <option>Panchagarh</option>
                                            <option>Rangpur</option>
                                            <option>Thakurgaon</option>
                                            <option>Barguna</option>
                                            <option>Barishal</option>
                                            <option>Bhola</option>
                                            <option>Jhalokati</option>
                                            <option>Patuakhali</option>
                                            <option>Pirojpur</option>
                                            <option>Bandarban</option>
                                            <option>Brahmanbaria</option>
                                            <option>Chandpur</option>
                                            <option>Chattogram</option>
                                            <option>Cumilla</option>
                                            <option>Cox's Bazar</option>
                                            <option>Feni</option>
                                            <option>Khagrachari</option>
                                            <option>Lakshmipur</option>
                                            <option>Noakhali</option>
                                            <option>Rangamati</option>
                                            <option>Habiganj</option>
                                            <option>Maulvibazar</option>
                                            <option>Sunamganj</option>
                                            <option>Sylhet</option>
                                            <option>Bagerhat</option>
                                            <option>Chuadanga</option>
                                            <option>Jashore</option>
                                            <option>Jhenaidah</option>
                                            <option>Khulna</option>
                                            <option>Kushtia</option>
                                            <option>Magura</option>
                                            <option>Meherpur</option>
                                            <option>Narail</option>
                                            <option>Satkhira</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Address Line 1<span>*</span></label>
                                        <input type="text" name="address1" placeholder=""
                                            value="{{ old('address1') }}" required>
                                        @error('address1')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Address Line 2 (optional)</label>
                                        <input type="text" name="address2" placeholder=""
                                            value="{{ old('address2') }}">
                                        @error('address2')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Postal Code (optional)</label>
                                        <input type="text" name="post_code" placeholder=""
                                            value="{{ old('post_code') }}">
                                        @error('post_code')
                                            <span class='text-danger'>{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            <!--/ End Form -->
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <div class="order-details">
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>CART TOTALS</h2>
                                <div class="content">
                                    <ul>
                                        <li class="order_subtotal" data-price="{{ Helper::totalCartPrice() }}">Cart
                                            Subtotal<span><symbol>৳</symbol>{{ number_format(Helper::totalCartPrice(), 2) }}</span></li>
                                        <li class="shipping">
                                            @if (count(Helper::shipping()) > 0 && Helper::cartCount() > 0)
                                                <div>Shipping Cost <span class="text-danger" style="float: unset !important;">*</span></div>
                                                <select name="shipping" class="required">
                                                    <option value="">Select Shipping</option>
                                                    @foreach (Helper::shipping() as $shipping)
                                                        <option value="{{ $shipping->id }}" class="shippingOption"
                                                            data-price="{{ $shipping->price }}">{{ $shipping->type }}:
                                                            <symbol>৳</symbol>{{ $shipping->price }}</option>
                                                    @endforeach
                                                </select>
                                                {{-- @else 
                                                <span>Free</span> --}}
                                            @endif
                                        </li>

                                        @if (session('coupon'))
                                            <li class="coupon_price" data-price="{{ session('coupon')['value'] }}">You
                                                Save<span><symbol>৳</symbol>{{ number_format(session('coupon')['value'], 2) }}</span></li>
                                        @endif
                                        @php
                                            $total_amount = Helper::totalCartPrice();
                                            if (session('coupon')) {
                                                $total_amount = $total_amount - session('coupon')['value'];
                                            }
                                        @endphp
                                        @if (session('coupon'))
                                            <li class="last" id="order_total_price">
                                                Total<span><symbol>৳</symbol>{{ number_format($total_amount, 2) }}</span></li>
                                        @else
                                            <li class="last" id="order_total_price">
                                                Total<span><symbol>৳</symbol>{{ number_format($total_amount, 2) }}</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <!--/ End Order Widget -->
                            <!-- Order Widget -->
                            <div class="single-widget">
                                <h2>Payments</h2>
                                <div class="content">
                                    <div class="checkbox">
                                        <p>
                                            For Cash on Delivery, you need to pay {{ config('app.delivery_charge') }} BDT
                                            in as advance.
                                        </p>
                                        {{-- <label class="checkbox-inline" for="1"><input name="updates" id="1" type="checkbox"> Check Payments</label> --}}
                                        <form-group>
                                            <input name="payment_method" type="radio" value="cod"> <label> Cash On
                                                Delivery</label><br>
                                            <input name="payment_method" type="radio" value="sslcommerz"> <label>
                                                Sslcommerz </label>
                                        </form-group>

                                    </div>
                                </div>
                            </div>
                            <!--/ End Payment Method Widget -->
                            <!-- Button Widget -->
                            <div class="single-widget get-button">
                                <div class="content">
                                    <div class="button">
                                        <button type="submit" class="btn">proceed to checkout</button>
                                    </div>
                                </div>
                            </div>
                            <!--/ End Button Widget -->
                        </div>
                    </div>
                </div>
            </form>
            <form action="{{ route('coupon-store') }}" method="POST" class="apply-coupon-form">
                @csrf
                <input name="code" placeholder="Enter Your Coupon">
                <button class="btn">Apply</button>
            </form>
        </div>
    </section>
    <!--/ End Checkout -->
    
    <!-- Start Shop Services Area  -->
    <section class="shop-services section home">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-rocket"></i>
                        <h4>Free shiping</h4>
                        <p>Orders over $100</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>Free Return</h4>
                        <p>Within 30 days returns</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-lock"></i>
                        <h4>Sucure Payment</h4>
                        <p>100% secure payment</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-tag"></i>
                        <h4>Best Peice</h4>
                        <p>Guaranteed price</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Services -->

    <!-- Start Shop Newsletter  -->
    <section class="shop-newsletter section">
        <div class="container">
            <div class="inner-top">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-12">
                        <!-- Start Newsletter Inner -->
                        <div class="inner">
                            <h4>Newsletter</h4>
                            <p> Subscribe to our newsletter and get <span>10%</span> off your first purchase</p>
                            <form action="mail/mail.php" method="get" target="_blank" class="newsletter-inner">
                                <input name="EMAIL" placeholder="Your email address" required="" type="email">
                                <button class="btn">Subscribe</button>
                            </form>
                        </div>
                        <!-- End Newsletter Inner -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Newsletter -->
@endsection
@push('styles')
	<style>
	</style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            $('select[name=shipping]').change(function() {
                let cost = parseFloat($(this).find('option:selected').data('price')) || 0;
                let subtotal = parseFloat($('.order_subtotal').data('price'));
                let coupon = parseFloat($('.coupon_price').data('price')) || 0;
                // alert(coupon);
                $('#order_total_price span').text('৳' + (subtotal + cost - coupon).toFixed(2));
            });

            const $orgPhone = $('#phone');
            const $phoneInput = $('#formatted_phone');
            const $phoneErrorSection = $('#phone_error');

            const getCleanPhone = () => $phoneInput.val().replace(/\D/g, '');

            $phoneInput.on('focusout', validatePhoneNumber);

            $phoneInput.on('input', function(e) {
                let value = getCleanPhone();
                let valueLen = value.length;

                $orgPhone.val(valueLen > 11 ? value.substring(0, 11) : value);

                let formattedValue = value;
                
                // Limit to 11 digits
                if (valueLen > 11) formattedValue = formattedValue.substring(0, 11);
                
                // Auto-format with space after 3 and 8 digits for readability
                if (valueLen > 3) 
                    formattedValue = formattedValue.substring(0, 3) + ' ' + formattedValue.substring(3);

                if (valueLen > 8) 
                    formattedValue = formattedValue.substring(0, 8) + ' ' + formattedValue.substring(8);
                
                $(this).val(formattedValue);
                
                // Auto-validate as user types
                if (valueLen >= 11) validatePhoneNumber();
                else $phoneErrorSection.hide();
            });

            function validatePhoneNumber() {
                const cleanPhone = getCleanPhone();
                if(cleanPhone.length == 0) return null;

                const operatorCodes = ['3', '4', '5', '6', '7', '8', '9'];
                const message = {
                    [cleanPhone.length !== 11]: "Phone number must be 11 digits",
                    [cleanPhone.length > 2 && !operatorCodes.includes(cleanPhone.charAt(2))]: "Invalid operator code",
                    [!cleanPhone.startsWith('01')]: "Phone number must start with 01",
                }[true] || null;
                
                $phoneErrorSection.text(message || '');

                if (message) $phoneErrorSection.show();
                else $phoneErrorSection.hide();
                
                // console.log(message);
                return message;
            }

            $("#cart-order-form").on('submit', function(e) {
                let phoneError = validatePhoneNumber();
                if (phoneError) 
                    e.preventDefault();
            });
        });
    </script>
@endpush
