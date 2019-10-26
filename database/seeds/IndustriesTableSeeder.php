<?php

use Illuminate\Database\Seeder;
use App\Models\Industry;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get('database/data/industries.json');
        $data = json_decode($json, true);
        foreach($data['industries'] as $industry){
        	Industry::create([
        		'name' => $industry,
                'slug' => str_slug($industry, '-')
        	]);
        }
    }
}
