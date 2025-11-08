<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * DsoAlert Command
 *
 * Finds all products that could not fulfill their daily sale objective.
 */
class DsoAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dsoalert:find';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find all products who could not fulfill daily sale objective';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $date = date('Y-m-d', strtotime('-1 day'));

        config()->set('database.connections.mysql.strict', false);
        DB::reconnect();

        $dsoAlertProducts = DB::table('products')
            ->leftJoin('product_sales', function ($join): void {
                $join->on('products.id', '=', 'product_sales.product_id')
                    ->whereNotNull('products.daily_sale_objective');
            })
            ->where('products.daily_sale_objective', '>', function ($query): void {
                $query->select(DB::raw('sum(product_sales.qty)'));
            })
            ->whereDate('product_sales.created_at', $date)
            ->select('products.name', 'products.code')
            ->groupBy('products.code')
            ->get();

        config()->set('database.connections.mysql.strict', true);
        DB::reconnect();

        $numberOfProducts = $dsoAlertProducts->count();

        if ($numberOfProducts > 0) {
            DB::table('dso_alerts')->insert([
                'product_info' => json_encode($dsoAlertProducts),
                'number_of_products' => $numberOfProducts,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->info("Found {$numberOfProducts} products that did not meet daily sale objective.");
        } else {
            $this->info('No products found that did not meet daily sale objective.');
        }

        return Command::SUCCESS;
    }
}

