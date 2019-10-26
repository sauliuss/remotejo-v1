<?php

use Illuminate\Database\Seeder;
use App\Models\Benefit;

class BenefitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$json = File::get('database/data/benefits.json');
    	$data = json_decode($json);
    	foreach($data as $item){

    		$benefit_category = Benefit::create([
    			'name' => $item->category,
                'slug' => str_slug($item->category, '-')
    		]);

            foreach ($item->benefits as $benefit) {
                Benefit::create([
                    'name' => $benefit,
                    'parent_id' => $benefit_category->id,
                    'slug' => str_slug($benefit, '-')

                ]);
            }
    	}
    }
}
