<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $locations = Location::all()->pluck("id")->toArray();

        return [
            /// get random id from locations
            "location_id" => $locations[array_rand($locations)],
            "first_name" => $this->faker->firstName(),
            "last_name" => $this->faker->lastName(),
            "home_phone" => $this->faker->tollFreePhoneNumber(),
            "cell_phone" => $this->faker->tollFreePhoneNumber(),
            "email" => $this->faker->email(),
            "date_of_birth" =>  $this->faker->date(),
            "address" => $this->faker->address(),
            "state" => $this->faker->stateAbbr(),
            "city" => $this->faker->city(),
            "zip" => $this->faker->postcode(),
            "high_blood_pressure" => $this->faker->numberBetween(0, 1),
            "high_cholesterol" => $this->faker->numberBetween(0, 1),
            "diabetes" => $this->faker->numberBetween(0, 1),
            "how_did_hear_about_clinic" => 0,
            "patient_note" => "Similique quo eligen",
        ];
    }
}
