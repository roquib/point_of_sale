<?php

use App\Model\Others\Category;
use App\Model\Others\Customer;
use App\Model\Others\Supplier;
use App\Model\Others\Unit;
use Illuminate\Database\Seeder;

class HelpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = array("Food", "Fruit", "Spices", "Vegetable", "Fish", "Toys");
        $units      = array("Pcs", "Kg", "Gm", "Litter", "Ft", "Inch");

        for ($i = 0; $i < count($categories); $i++) {
            Category::create([
                'name' => $categories[$i],
            ]);
            Unit::create([
                'name' => $units[$i],
            ]);
        }

        // Supplier 
        Supplier::create([
            'name'    => "Cash",
            'email'   => "cash@gmail.com",
            'mobile'  => "01754863245",
            'user_id' => 1,
        ]);
        Supplier::create([
            'name'    => "Mr Alen",
            'email'   => "alen26@gmail.com",
            'mobile'  => "01758963245",
            'user_id' => 1,
        ]);
        Supplier::create([
            'name'    => "Mr Masud",
            'email'   => "masud14@gmail.com",
            'mobile'  => "01758243245",
            'user_id' => 1,
        ]);
        Supplier::create([
            'name'    => "Hasan Sikder",
            'email'   => "hasan.sikdar@gmail.com",
            'mobile'  => "01758243276",
            'user_id' => 1,
        ]);
        Supplier::create([
            'name'    => "Shahana Tasnim",
            'email'   => "tasnim.shahana@gmail.com",
            'mobile'  => "0175824145",
            'user_id' => 1,
        ]);
        // Customer 
        Customer::create([
            'name'    => "Cash",
            'email'   => "cash@gmail.com",
            'mobile'  => "01754863245",
            'user_id' => 1,
        ]);
        Customer::create([
            'name'    => "Mr Alen",
            'email'   => "alen26@gmail.com",
            'mobile'  => "01758963245",
            'user_id' => 1,
        ]);
        Customer::create([
            'name'    => "Mr Masud",
            'email'   => "masud14@gmail.com",
            'mobile'  => "01758243245",
            'user_id' => 1,
        ]);
        Customer::create([
            'name'    => "Hasan Sikder",
            'email'   => "hasan.sikdar@gmail.com",
            'mobile'  => "01758243276",
            'user_id' => 1,
        ]);
        Customer::create([
            'name'    => "Shahana Tasnim",
            'email'   => "tasnim.shahana@gmail.com",
            'mobile'  => "0175824145",
            'user_id' => 1,
        ]);
    }
}
