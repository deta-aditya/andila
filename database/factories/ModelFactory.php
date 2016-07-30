<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'password' => bcrypt('secret'),
    ];
});

$factory->define(App\Models\Station::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'phone' => $faker->phoneNumber,
        'location' => [$faker->latitude, $faker->longitude],
        'type' => $faker->randomElement(['SPPBE', 'SPPEK', 'SPBU']),
    ];
});

$factory->define(App\Models\Agent::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
        'email' => $faker->companyEmail,
        'phone' => $faker->phoneNumber,
        'owner' => $faker->name,
        'location' => [$faker->latitude, $faker->longitude],
        'active' => (int)$faker->boolean(70),
    ];
});

$factory->define(App\Models\Subagent::class, function (Faker\Generator $faker) {

	// Fetch agent data randomly
	$agent = App\Models\Agent::orderByRaw('RAND()')->first();

    return [
    	'agent_id' => $agent->id,
        'name' => $faker->company,
        'email' => $faker->companyEmail,
        'phone' => $faker->phoneNumber,
        'owner' => $faker->name,
        'location' => [$faker->latitude, $faker->longitude],
        'contract_value' => $faker->numberBetween(50, 200),
        'active' => (int)$faker->boolean(70),
    ];
});

$factory->define(App\Models\Address::class, function (Faker\Generator $faker) {

	// Fetch geographic data from database in cascading order
	$province = App\Models\Indonesia\Province::orderByRaw('RAND()')->first();
	$regency = App\Models\Indonesia\Regency::where('province_id', $province->id)->orderByRaw('RAND()')->first();
	$district = App\Models\Indonesia\District::where('regency_id', $regency->id)->orderByRaw('RAND()')->first();
	$subdistrict = App\Models\Indonesia\Subdistrict::where('district_id', $district->id)->orderByRaw('RAND()')->first();

    return [
        'province' => $province->name,
        'regency' => $regency->name,
        'district' => $district->name,
        'subdistrict' => $subdistrict->name,
        'detail' => $faker->streetAddress,
        'postal_code' => $faker->postcode,
    ];
});
