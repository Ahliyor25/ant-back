<?php

namespace App\Console\Commands;

use App\Events\Bitrix24\ONCRMPRODUCTUPDATEEvent;
use App\Services\Bitrix24\Fabric;
use Illuminate\Console\Command;

class SyncProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:products
        {--s|sleep=0.5 : Iteration sleep time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Комманда вытаскивает товары из Б24 и запускает события';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productService = Fabric::getServiceBuilder()->getCRMScope()->product();

        $productCount = $productService->countByFilter([]);
        $products = [];

        do {
            $products = array_merge($products, $productService->list([], [], ['*'], count($products))->getProducts());
        } while (count($products) < $productCount);

        $bar = $this->output->createProgressBar(count($products));
        $bar->start();

        foreach ($products as $product) {
            event(new ONCRMPRODUCTUPDATEEvent([
                'product' => $product
            ]));
            $bar->advance();
            sleep((float)$this->option('sleep'));
        }
        $bar->finish();
    }
}
