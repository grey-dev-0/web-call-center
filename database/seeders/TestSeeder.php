<?php

namespace Database\Seeders;

use GreyZero\WebCallCenter\Models\Agent;
use GreyZero\WebCallCenter\Models\Customer;
use GreyZero\WebCallCenter\Models\Organization;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder{
    /**
     * @var \Faker\Generator $faker Fake data generator.
     */
    private \Faker\Generator $faker;

    public function __construct(){
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        if(
            config('web-call-center.customer_model')     != Customer::class      ||
            config('web-call-center.organization_model') != Organization::class  ||
            config('web-call-center.agent_model')        != Agent::class
        ){
            $this->command->error('This is seeder is meant for testing web call center with its default database configuration');
            return;
        }

        $organizationIds = [];
        for($i = 0; $i < 2; $i++)
            $organizationIds[] = Organization::create(['name' => $this->faker->domainName()])->id;

        for($j = 0; $j < 2; $j++)
            for($i = 0; $i < 5; $i++)
                Agent::create(['organization_id' => $organizationIds[$j], 'name' => $this->faker->name()]);

        for($i = 0; $i < 10; $i++)
            Customer::create(['name' => $this->faker->name()]);
    }
}
