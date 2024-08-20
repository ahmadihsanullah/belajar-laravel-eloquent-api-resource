<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    public function testProductResource()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name
                    ],
                    "is_expensive"=> $product->price > 3000,
                    "price" => $product->price,
                    "created_at" => $product->created_at->toJSON(),
                    "updated_at" => $product->updated_at->toJSON(),
                ]
            ])
            ->assertHeader('X-Powered-By', "Programmer Zaman Now");
    }

    public function testResourceCollectionWrap()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $response = $this->get('/api/products')
            ->assertStatus(200)
            ->assertHeader('X-Powered-By', "Programmer Zaman Now");

        $names = $response->json("data.*.name");

        for ($i=0; $i < 1; $i++) { 
            self::assertContains("Product $i of gadget", $names);
        }

        for ($i=0; $i < 1; $i++) { 
            self::assertContains("Product $i of gadget", $names);
        }
    }

    public function testProductPaging()
    {

        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $response = $this->get('/api/products-paging')
            ->assertStatus(200);

        self::assertNotNull($response->json('links'));
        self::assertNotNull($response->json('meta'));
        self::assertNotNull($response->json('data'));
    }

    public function testAdditionalMetadata()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $this->get("/api/products-debug/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "author" => 'pzn',
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price,
                ]
            ]);
    }

    public function testAdditionalMetadataDynamic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $product = Product::first();
        $response = $this->get("/api/products-debug/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "author" => 'pzn',
                "data" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "price" => $product->price,
                ]
                ]);
        self::assertNotNull($response->json('server_time'));
    }
}
