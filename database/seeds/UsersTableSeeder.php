<?php
 
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Faker\Provider\en_HK\Phone;
use Faker\Provider\en_US\PhoneNumber;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker\Factory::create(); 
        $faker->addProvider(new Faker\Provider\en_US\PhoneNumber($faker));
        foreach (range(1,50) as $index) {
        $data = [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName, 
        'address' => $faker->address,
        'city' => 'Cebu City',
        'province' => 'Cebu',
        'mobile_no' => $faker->e164PhoneNumber,
        
        
        ];
        User::create($data);
        }
    
    }
}
