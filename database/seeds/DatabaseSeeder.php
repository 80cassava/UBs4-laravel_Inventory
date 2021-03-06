<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	factory(\App\User::class, 5)->create();
        $this->call(FakultasSeeder::class);
        $this->call(JurusanSeeder::class);
        $this->call(RuanganSeeder::class);
        $this->call(BarangSeeder::class);
    }
}
