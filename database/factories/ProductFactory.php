<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name'        => $faker->name,
        'sku'         => $faker->word,
        'description' => $faker->text,
        'photo'       => 'https://www.madeinturkey.com.ua/wp-content/uploads/2020/02/125-fall-in-love.jpg',
        'qty'         => $faker->numberBetween($min = 1, $max = 100),
        'price'       => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 2000)
    ];
});
