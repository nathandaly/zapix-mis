<?php

namespace Database\Factories;

use App\Models\ContactDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ContactDetailFactory extends Factory
{
    protected $model = ContactDetail::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
