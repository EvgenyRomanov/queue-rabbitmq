<?php

namespace App\Factories;

use App\Domain\Model\User;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

class TroubleTicketFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $faker = Faker::create();
        return [
        ];
    }
}
