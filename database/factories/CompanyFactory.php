<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition()
    {
        return [
            'cnpj' => $this->faker->unique()->numerify('##############'),
            'name' => $this->faker->company,
            'is_active' => $this->faker->boolean,
        ];
    }
}
