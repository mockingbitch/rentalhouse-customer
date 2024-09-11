<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportSQLSeeder extends Seeder
{
    /**
     * Run
     *
     * @return void
     */
    public function run(): void
    {
        // Path to file SQL
        $sqlCreateTablesVNUnitsFile = database_path('seeders/SqlFiles/CreateTables_vn_units.sql');
        $sqlImportDataVNUnitsFile = database_path('seeders/SqlFiles/ImportData_vn_units.sql');

        // Read file SQL
        $sqlCreateTablesVNUnits = File::get($sqlCreateTablesVNUnitsFile);
        $sqlImportDataVNUnits = File::get($sqlImportDataVNUnitsFile);

        // Execute SQL command
        DB::unprepared($sqlCreateTablesVNUnits);
        DB::unprepared($sqlImportDataVNUnits);

        $this->command->info('Database seeding from SQL file completed!');
    }
}
