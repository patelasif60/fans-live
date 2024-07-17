<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductCategory;
use Carbon\Carbon as Carbon;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('product_category')->truncate();
        DB::table('product_options')->truncate();
        DB::table('products')->truncate();
        $data = [
            [
	            "club_id" => 12,
	            "title" => "Margherita pizza",
	            "short_description" => "Margherita pizza",
	            "description" => "<p>A widespread belief says that in June 1889 the pizzaiolo Raffaele Esposito, Pizzeria Brandi's chef, invented a dish called Pizza Margherita in honor of the Queen of Italy, Margherita of Savoy, and the Italian unification, since toppings are tomato (red), mozzarella (white) and basil (green).</p>",
	            "image" => "https://fanslive-dev.s3.amazonaws.com/product/Margherita-Pizza-018.jpg",
	            "image_file_name" => "Margherita-Pizza-018.jpg",
	            "price" => 50,
	            "rewards_percentage_override" => null,
	            "vat_rate" => 10,
	            "is_restricted_to_over_age" => 0,
	            "status" => "Published",
	            'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
			],
            [
                "club_id" => 12,
	            "title" => "Wembley London Dry Gin",
	            "short_description" => "Wembley London Dry Gin",
	            "description" => "<p>Wembley London Dry Gin - a perfect harmony between the London identity, the royal elegance and the authentic English recipe.</p>",
	            "image" => "https://fanslive-dev.s3.amazonaws.com/product/wembley_dry_gin.jpg",
	            "image_file_name" => "wembley_dry_gin.jpg",
	            "price" => 30,
	            "rewards_percentage_override" => null,
	            "vat_rate" => 10,
	            "is_restricted_to_over_age" => 18,
	            "status" => "Published",
	            'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                "club_id" => 12,
	            "title" => "Madrid Souvenirs",
	            "short_description" => "Madrid Souvenirs",
	            "description" => "<p>Olive oil is one of those souvenirs that never can go wrong (if not damaged in your suitcase). It’s delicious, it’s affordable, and it’s useful. This is a souvenir that you can buy for anyone who loves to cook, or simply loves food.<br/>In the supermarkets in Madrid, you have a big selection of delicious olive oils, as well as in local olive oil shops.</p>",
	            "image" => "https://fanslive-dev.s3.amazonaws.com/product/Olive_oil_Madrid_souvenirs.jpg",
	            "image_file_name" => "Olive_oil_Madrid_souvenirs.jpg",
	            "price" => 30,
	            "rewards_percentage_override" => null,
	            "vat_rate" => 10,
	            "is_restricted_to_over_age" => 18,
	            "status" => "Published",
	            'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
            ],
        ];
        foreach ($data as $key => $value) {
 			$productPage = Product::create($value);       	
 			if ($productPage) {
                ProductCategory::insert([
                    [
                        'product_id' => $productPage->id,
                        'category_id' => $productPage->id,
                    ],
                ]);
                if($productPage->id == 1){
		             ProductOption::insert([
		                [
		                    'product_id' => $productPage->id,
		                    'additional_cost' => 1.20,
		                    'Name'=>'sauce',
		                ],
		            ]);
             	}
             	elseif($productPage->id==2)
             	{
             		ProductOption::insert([
		                [
		                    'product_id' => $productPage->id,
		                    'additional_cost' => 2.20,
		                    'Name'=>'Ice cubes',
		                ],
		            ]);
             	}
             	else
             	{
             		ProductOption::insert([
		                [
		                    'product_id' => $productPage->id,
		                    'additional_cost' => 0.20,
		                    'Name'=>'Box'
		                ],
		            ]);
             	}
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
