<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $users = factory(App\User::class, 10)->create();
        $prods = factory(App\Product::class, 10)->create();
        $rates = factory(App\Rating::class, 10)->create();
        $adds = factory(App\OrderAddress::class, 10)->create();
        $carts = factory(App\Cart::class, 10)->create();
        // $ords = factory(App\Order::class, 10)->create();
    }
}
