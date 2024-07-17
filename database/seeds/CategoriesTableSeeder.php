<?php

use Illuminate\Database\Seeder;
use App\Models\Category;
use Carbon\Carbon as Carbon;
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();
        Category::insert([
            [
            	'club_id' => 12,
            	'title' => 'Pizza',
            	'type' => 'food_and_drink',
            	'image' => 'https://fanslive-dev.s3.amazonaws.com/category/Eq_it-na_pizza-margherita_sep2005_sml_1595921471.jpg',
            	'image_file_name' => 'Eq_it-na_pizza-margherita_sep2005_sml_1595921471.jpg',
            	'status' =>'Published',
            	'rewards_percentage_override' =>10,
            	'is_restricted_to_over_age' =>1,
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
            	'club_id' => 12,
            	'title' => 'Alcoholic drinks',
            	'type' => 'food_and_drink',
            	'image' => 'https://fanslive-dev.s3.amazonaws.com/category/alcoholic_drink.jpg',
            	'image_file_name' => 'alcoholic_drink.jpg',
            	'status' =>'Published',
            	'rewards_percentage_override' =>10,
            	'is_restricted_to_over_age' =>1,
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
            ],
             [
                'club_id' => 12,
            	'title' => 'Souvenirs',
            	'type' => 'merchandise',
            	'image' => 'https://fanslive-dev.s3.amazonaws.com/category/madrid-souvenirs.jpg',
            	'image_file_name' => 'madrid-souvenirs.jpg',
            	'status' =>'Published',
            	'rewards_percentage_override' =>10,
            	'is_restricted_to_over_age' =>1,
                'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
