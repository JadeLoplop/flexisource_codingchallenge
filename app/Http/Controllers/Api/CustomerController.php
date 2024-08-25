<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\User as Customer;
use App\Services\CustomerImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{

    private $userImporter;

    public function __construct(CustomerImportService $userImporter)
    {
        $this->userImporter = $userImporter;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Customer::select('name', 'email', 'country')
                ->get()
                ->map(function ($customer) {
                    return [
                        'full_name' => $customer->name,
                        'email' => $customer->email,
                        'country' => $customer->country,
                    ];
                });
        } catch (\Throwable $th) {
            Log::error('Error fetching customers: ' . $th->getMessage());
            return response()->json(['message' => 'Error fetching customers'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {

            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json(['message' => 'User not found'], 404);
            }

            return new CustomerResource($customer);
        } catch (\Throwable $th) {
            Log::error('Error fetching customer details: ' . $th->getMessage());
            return response()->json(['message' => 'Error fetching customer details'], 500);
        }
    }

    /**
     * Similar result from customer:import command.
     */
    public function import()
    {
        try {
            $this->userImporter->import();
            return response()->json(['message' => 'Users imported successfully']);
        } catch (\Throwable $th) {
            Log::error('Error importing users: ' . $th->getMessage());
            return response()->json(['message' => 'Error importing users'], 500);
        }
    }
}
