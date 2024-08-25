<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_customers_successful()
    {
        // Mock the HTTP client to simulate API behavior if necessary
        Http::fake([
            'https://randomuser.me/api/*' => Http::response([
                'results' => [
                    [
                        'email' => 'janedoe@example.com',
                        'name' => ['first' => 'Jane', 'last' => 'Doe'],
                        'login' => ['username' => 'janedoe', 'password' => 'password456'],
                        'phone' => '987-654-3210',
                        'gender' => 'female',
                        'location' => [
                            'country' => 'Australia',
                            'city' => 'Melbourne'
                        ],
                    ]
                ]
            ], 200),
        ]);

        $response = $this->get('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['full_name', 'email', 'country'],
            ]);
    }

    public function test_get_customer_details_successful()
    {
        $customer = User::create([
                    'email' => 'johndoe@example.com',
                    'name' => 'John Doe',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'username' => 'johndoe',
                    'password' => md5('password123'),
                    'phone' => '123-456-7890',
                    'gender' => 'male',
                    'country' => 'Australia',
                    'city' => 'Sydney',
                ]);


        $response = $this->get('/api/customers/' . $customer->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'full_name' => 'John Doe',
                    'email' => 'johndoe@example.com',
                    'username' => 'johndoe',
                    'gender' => 'male',
                    'country' => 'Australia',
                    'city' => 'Sydney',
                    'phone' => '123-456-7890',
                ]
            ]);
    }

    public function test_get_customer_not_found()
    {
        // Mock the HTTP client to simulate API behavior if necessary
        Http::fake();

        $response = $this->get('/api/customers/9999'); // Non-existing ID

        $response->assertStatus(404)
            ->assertJson(['message' => 'User not found']);
    }

    public function test_import_customers_successful()
    {
        // Mock a successful API response
        Http::fake([
            'https://randomuser.me/api/*' => Http::response([
                'results' => [
                    [
                        'email' => 'janedoe@example.com',
                        'name' => ['first' => 'Jane', 'last' => 'Doe'],
                        'login' => ['username' => 'janedoe', 'password' => 'password456'],
                        'phone' => '987-654-3210',
                        'gender' => 'female',
                        'location' => [
                            'country' => 'Australia',
                            'city' => 'Melbourne'
                        ],
                    ]
                ]
            ], 200),
        ]);

        // Call the import endpoint
        $response = $this->post('/api/customers/import', ['count' => 1]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'janedoe@example.com',
            'name' => 'Jane Doe',
            'username' => 'janedoe',
            'country' => 'Australia',
        ]);
    }

    public function test_import_customers_api_failure()
    {
        // Mock an API failure response
        Http::fake([
            'https://randomuser.me/api/*' => Http::response(null, 500),
        ]);

        $response = $this->post('/api/customers/import', ['count' => 1]);

        $response->assertStatus(500);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_get_customers_empty_database()
    {
        // Ensure the database is empty
        User::truncate();

        $response = $this->get('/api/customers');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    public function test_get_customer_invalid_id()
    {
        // Pass an invalid ID that does not exist in the database
        $response = $this->get('/api/customers/abc');

        $response->assertStatus(404)
            ->assertJson(['message' => 'User not found']);
    }
}
