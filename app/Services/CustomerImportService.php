<?php

namespace App\Services;

use App\Models\User as Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerImportService
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('customerImporter.api.url');
    }

    public function import($count = 100)
    {
        try {
            $response = Http::get($this->apiUrl, [
                'results' => $count
            ]);

            if ($response->successful()) {
                $customers = $response->json()['results'];

                foreach ($customers as $customerData) {
                    $this->storeCustomer($customerData);
                }
            } else {
                $message = 'Ops! Error encountered when calling the RandomUser API: ' . $response->status();
                Log::error($message);
                throw new \Exception($message);
            }
        } catch (\Throwable $th) {
            Log::error('Error during customer import: ' . $th->getMessage(), ['exception' => $th]);
            throw $th;
        }
    }


    protected function storeCustomer($data)
    {
        Customer::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name']['first'] . ' ' . $data['name']['last'],
                'first_name' => $data['name']['first'],
                'last_name' => $data['name']['last'],
                'username' => $data['login']['username'],
                'phone' => $data['phone'],
                'gender' => $data['gender'],
                'country' => $data['location']['country'],
                'city' => $data['location']['city'],
                'password' => md5($data['login']['password']), // Hash the password using md5
            ]
        );
    }
}
