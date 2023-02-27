<?php

namespace Database\Factories\Module\Dokani\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Module\Dokani\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dokan_id' => 1,
            'created_by' => 1,
            'unit_id'      => 1,
            'category_id'      => 1,
            'name' => $this->faker->name(),
            'barcode' => rand(10000,99999),
            'purchase_price' => rand(100,999),
            'sell_price' => rand(100,999),
            'opening_stock' => rand(10,99),
            'alert_qty' => rand(10,99),
        ];
    }
}
