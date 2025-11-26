<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            // MOBILE LEGENDS
            ['game'=>'mobile_legends','name'=>'14 Diamonds','amount'=>14,'price'=>4000,'base_price'=>3000],
            ['game'=>'mobile_legends','name'=>'28 Diamonds','amount'=>28,'price'=>8000,'base_price'=>6500],
            ['game'=>'mobile_legends','name'=>'86 Diamonds','amount'=>86,'price'=>20000,'base_price'=>17000],
            ['game'=>'mobile_legends','name'=>'172 Diamonds','amount'=>172,'price'=>39000,'base_price'=>34000],
            ['game'=>'mobile_legends','name'=>'257 Diamonds','amount'=>257,'price'=>60000,'base_price'=>54000],
            ['game'=>'mobile_legends','name'=>'344 Diamonds','amount'=>344,'price'=>78000,'base_price'=>72000],
            ['game'=>'mobile_legends','name'=>'514 Diamonds','amount'=>514,'price'=>118000,'base_price'=>110000],

            // FREE FIRE
            ['game'=>'free_fire','name'=>'50 Diamonds','amount'=>50,'price'=>8000,'base_price'=>6000],
            ['game'=>'free_fire','name'=>'100 Diamonds','amount'=>100,'price'=>15000,'base_price'=>13000],
            ['game'=>'free_fire','name'=>'310 Diamonds','amount'=>310,'price'=>42000,'base_price'=>38000],
            ['game'=>'free_fire','name'=>'520 Diamonds','amount'=>520,'price'=>70000,'base_price'=>60000],
            ['game'=>'free_fire','name'=>'1060 Diamonds','amount'=>1060,'price'=>135000,'base_price'=>120000],

            // GENSHIN
            ['game'=>'genshin_impact','name'=>'Welkin Moon','amount'=>300,'price'=>65000,'base_price'=>60000],
            ['game'=>'genshin_impact','name'=>'Genesis Crystal 60','amount'=>60,'price'=>15000,'base_price'=>13000],
            ['game'=>'genshin_impact','name'=>'Genesis Crystal 330','amount'=>330,'price'=>75000,'base_price'=>68000],
            ['game'=>'genshin_impact','name'=>'Genesis Crystal 1090','amount'=>1090,'price'=>225000,'base_price'=>210000],

            // PUBGM
            ['game'=>'pubgm','name'=>'60 UC','amount'=>60,'price'=>14000,'base_price'=>12000],
            ['game'=>'pubgm','name'=>'300 UC','amount'=>300,'price'=>65000,'base_price'=>58000],
            ['game'=>'pubgm','name'=>'600 UC','amount'=>600,'price'=>120000,'base_price'=>110000],

            // VALORANT
            ['game'=>'valorant','name'=>'125 VP','amount'=>125,'price'=>15000,'base_price'=>13000],
            ['game'=>'valorant','name'=>'420 VP','amount'=>420,'price'=>45000,'base_price'=>42000],
            ['game'=>'valorant','name'=>'700 VP','amount'=>700,'price'=>75000,'base_price'=>70000],

            // HIGGS DOMINO
 // COD MOBILE
[
    'game'       => 'cod_mobile',
    'name'       => '50 CP',
    'amount'     => 50,
    'price'      => 12000,
    'base_price' => 10000,
],
[
    'game'       => 'cod_mobile',
    'name'       => '120 CP',
    'amount'     => 120,
    'price'      => 28000,
    'base_price' => 25000,
],
[
    'game'       => 'cod_mobile',
    'name'       => '240 CP',
    'amount'     => 240,
    'price'      => 55000,
    'base_price' => 50000,
],
[
    'game'       => 'cod_mobile',
    'name'       => '420 CP',
    'amount'     => 420,
    'price'      => 90000,
    'base_price' => 83000,
],
[
    'game'       => 'cod_mobile',
    'name'       => '880 CP',
    'amount'     => 880,
    'price'      => 185000,
    'base_price' => 175000,
],


            // APEX
            ['game'=>'apex_legends','name'=>'1000 Coins','amount'=>1000,'price'=>120000,'base_price'=>110000],
            ['game'=>'apex_legends','name'=>'2150 Coins','amount'=>2150,'price'=>250000,'base_price'=>230000],

            // HONKAI STAR RAIL
            ['game'=>'hsr','name'=>'60 Stellar Jade','amount'=>60,'price'=>15000,'base_price'=>13000],
            ['game'=>'hsr','name'=>'300 Stellar Jade','amount'=>300,'price'=>75000,'base_price'=>70000],
            ['game'=>'hsr','name'=>'980 Stellar Jade','amount'=>980,'price'=>240000,'base_price'=>220000],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
