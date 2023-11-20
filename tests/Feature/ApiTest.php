<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 19.99,
            'discount' => 10,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201); // 201 Created
        $this->assertDatabaseHas('products', $productData);
    }

    public function test_can_get_all_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

       // Assert the response structure
       

    $response->assertStatus(200); // 200 OK

    $response->assertJsonStructure(['*' => [
        'id',
        'name',
        'description',
        'price',
        'discount',
        'created_at',
        'updated_at',
    ]]);

    // Assert the count of products in the response
    $response->assertJsonCount(5);
    }

    public function test_can_get_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200); // 200 OK

        $response->assertJson($product->toArray());
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();

        $updatedData = [
            'name' => 'Updated Product',
            'description' => 'This product has been updated',
            'price' => 25.99,
            'discount' => 15,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updatedData);

        $response->assertStatus(200); // 200 OK
        $this->assertDatabaseHas('products', $updatedData);
    }

    public function test_can_delete_product()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204); // 204 No Content
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
