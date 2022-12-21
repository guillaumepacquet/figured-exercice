<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Stock;
use App\Services\ProductApplicationService;
use Illuminate\Console\Command;

class LoadStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load stock to database from file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        list($purchaseTypeRow, $applicationTypeRow) = $this->loadFile();

        $product = Product::firstOrCreate([
            'name' => 'Fertiliser'
        ]);

        $this->loadStockForProduct($product, $purchaseTypeRow);

        $this->applyApplicationForProduct($product, $applicationTypeRow);

        return Command::SUCCESS;
    }

    /**
     * @return array[]
     */
    private function loadFile(): array
    {
        $file = fopen('fertiliser-movements.csv', 'r');

        $purchaseTypeRow = [];
        $applicationTypeRow = [];
        while (($row = fgetcsv($file, 200, ',')) !== false)
        {
            if(count($row) !== 4) {
                continue;
            }

            if($row[1] === 'Application') {
                $applicationTypeRow[] = $row;
            }

            if($row[1] === 'Purchase') {
                $purchaseTypeRow[] = $row;
            }
        }
        fclose($file);

        return [$purchaseTypeRow, $applicationTypeRow];
    }

    /**
     * @param Product $product
     * @param array $stocks
     * @return void
     */
    private function loadStockForProduct(Product $product, array $stocks)
    {
        $product->stocks()->saveMany(array_map(function($stock) {
            return new Stock([
                'quantity' => intval($stock[2]),
                'price' => floatval($stock[3]),
            ]);
        }, $stocks));
    }

    /**
     * @param Product $product
     * @param array $purchases
     * @return void
     */
    private function applyApplicationForProduct(Product $product, array $applications)
    {
        $productApplicationService  = new ProductApplicationService();

        foreach ($applications as $application) {
            $productApplicationService->useProduct($product, abs($application[2]));
        }
    }
}
