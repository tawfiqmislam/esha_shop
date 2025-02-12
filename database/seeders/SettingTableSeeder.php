<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
            'description'=>"At Esha Shop, we are committed to providing top-quality products at the best prices. With a focus on customer satisfaction, we offer fast shipping, secure payments, and reliable support to ensure a seamless shopping experience. Why Choose Us? ✔ Premium Quality Products ✔ Secure &amp; Easy Checkout ✔ Fast &amp; Reliable Shipping ✔ 24/7 Customer Support Stay connected for exclusive offers and the latest updates! ",
            'short_des'=>"Esha Shop - Your one-stop destination for high-quality products. Enjoy secure shopping, fast delivery, and 24/7 customer support. Stay connected for exclusive deals and updates!",
            'photo'=>"image.jpg",
            'logo'=>'logo.jpg',
            'address'=>"Jamal Khan, Chattogram, Bangladesh",
            'email'=>"eshashop@gmail.com",
            'phone'=>"+880 186 120 0604",
        );
        DB::table('settings')->insert($data);
    }
}
