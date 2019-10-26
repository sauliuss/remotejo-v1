<?php

use Illuminate\Database\Seeder;
use App\Models\HiringRegion;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     	$json = File::get('database/data/regions.json');
     	$data = json_decode($json);
     	foreach($data as $region){


     		HiringRegion::create([
     			'name' => $region->name,
     			'type' => isset($region->type) ? $region->type : 'country',
     			'emoji' => isset($region->emoji) ? $region->emoji : NULL,
     			'code' => isset($region->code) ? $region->code : NULL,
     			'slug' => str_slug($region->name, '-')
     		]);
     	}
    }
}
