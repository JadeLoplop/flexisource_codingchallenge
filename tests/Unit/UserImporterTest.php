<?php

namespace Tests\Unit;

use App\Services\UserImporter;
use App\Models\User;
use App\Services\CustomerImportService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserImporterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_store_customer_successful()
    {
        // Mock the HTTP client to simulate a successful API response
        Http::fake([
            'https://randomuser.me/api/*' => Http::response([
                'results' => [
                    [
                        'email' => 'johndoe@example.com',
                        'name' => ['first' => 'John', 'last' => 'Doe'],
                        'login' => ['username' => 'johndoe', 'password' => 'password123'],
                        'phone' => '123-456-7890',
                        'gender' => 'male',
                        'location' => [
                            'country' => 'Australia',
                            'city' => 'Sydney'
                        ],
                    ]
                ]
            ], 200),
        ]);

        $importer = new CustomerImportService();
        $importer->import(1); // Import one user

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'country' => 'Australia',
        ]);
    }

    public function test_store_customer_api_failure()
    {
        // Mock the HTTP client to simulate an API failure
        Http::fake([
            'https://randomuser.me/api/*' => Http::response(null, 500), // 500 Internal Server Error
        ]);

        $importer = new CustomerImportService();

        $this->expectException(\Exception::class);
        $importer->import(1); // Attempt to import with failure response

        // Assertions to ensure no data was imported
        $this->assertDatabaseCount('users', 0);
    }
}
