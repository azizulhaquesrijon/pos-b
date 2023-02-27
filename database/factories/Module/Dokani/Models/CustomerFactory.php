<?php


namespace Database\Factories\Module\Dokani\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Dokani\Models\Customer;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

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
            'name' => $this->faker->name(),
            'mobile' => rand(1000000,9999999),
            'opening_due'=> 0,
        ];
    }
}
