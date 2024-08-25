<?php

namespace App\Console\Commands;

use App\Services\CustomerImportService;
use Illuminate\Console\Command;

class ImportCustomers extends Command
{
    protected $signature = 'customers:import {count=100}';
    protected $description = 'Import customers from a third-party API (https://randomuser.me/)';

    protected $importService;

    public function __construct(CustomerImportService $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    public function handle()
    {
        $count = $this->argument('count');
        $this->importService->import($count);

        $this->info("Imported $count customers successfully.");
    }
}
