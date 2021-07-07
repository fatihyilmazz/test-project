<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;

class ProductTest extends TestCase
{
    public function test_product_created_successfully() {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $productData = [
            'name' => "TESTPRODUCT01 NAME",
            'identifier' => "TESTPRODUCT01",
            'description' => "TESTPRODUCT01 DESC",
            'categories' => 'Möbel Indoor 2600, Polstermöbel',
            'prices' => '9.90|2021-02-01 00:00:00|2021-02-28 23:59:59',
            'images' => 'https://depot.dam.aboutyou.cloud/images/5fb123f3fb04c6.jpg?width=1200&height=1200,https://depot.dam.aboutyou.cloud/images/5fb50f3fb04a8.jpg?width=1200&height=1200',
        ];

        $this->json('POST', 'api/store-product', $productData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "message" => "Product created successfully."
            ]);
    }
}
