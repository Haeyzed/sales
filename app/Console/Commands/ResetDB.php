<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ResetDB Command
 *
 * Resets the database in the demo environment.
 * WARNING: This command will drop all tables and import from SQL file.
 */
class ResetDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset DB in the demo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (!$this->confirm('Are you sure you want to reset the database? This will delete all data!')) {
            $this->info('Database reset cancelled.');
            return Command::SUCCESS;
        }

        // Clear all cached queries
        $cacheKeys = [
            'biller_list',
            'brand_list',
            'category_list',
            'coupon_list',
            'customer_list',
            'customer_group_list',
            'product_list',
            'product_list_with_variant',
            'warehouse_list',
            'table_list',
            'tax_list',
            'currency',
            'general_setting',
            'pos_setting',
            'user_role',
            'permissions',
            'role_has_permissions',
            'role_has_permissions_list',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        $tables = DB::select('SHOW TABLES');
        $databaseName = config('database.connections.mysql.database');
        $key = 'Tables_in_' . $databaseName;

        foreach ($tables as $table) {
            Schema::drop($table->$key);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // Import data from SQL file
        $sqlFile = base_path('salepropos.sql');
        if (file_exists($sqlFile)) {
            DB::unprepared(file_get_contents($sqlFile));
            $this->info('Database reset completed successfully.');
        } else {
            $this->error('SQL file not found: ' . $sqlFile);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

