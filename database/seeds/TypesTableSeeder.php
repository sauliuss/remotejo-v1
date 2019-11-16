<?php

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     	$json = File::get('database/data/types.json');
     	$data = json_decode($json);
     	foreach($data as $type){

     		Type::create([
     			'name' => $type->name,
     			'slug' => str_slug($type->name, '-')
     		]);
     	}
    }
}
