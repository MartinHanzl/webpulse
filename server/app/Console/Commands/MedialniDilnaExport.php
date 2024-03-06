<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MedialniDilnaExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'md:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import missing data to medialni-dilna.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = storage_path('app/public/external/medialnidilna_data.xlsx');

        $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

        $spreadsheet = $reader->load($file);

        $data = $spreadsheet->getActiveSheet()->toArray();

        unset($data[0]);

        $string = null;
        $idString = '(';
        foreach ($data as $item) {
            if (!is_null($item[1]) && !is_null($item[2]) && !is_null($item[3]) && !is_null($item[4])) {
                $idString .= (int)$item[0] . ', ';
                $string .= sprintf("UPDATE accounts SET company='%s', street='%s', city='%s', postcode='%s' WHERE email='%s' AND id=%s;", $item[1], $item[2], $item[3], $item[4], $item[7], (int)$item[0]);
            }
        }
        $idString .= ')';
        $idString = str_replace(', )', ')', $idString);

        file_put_contents(storage_path(('app/public/external/sql_export.txt')), $idString);
        file_put_contents(storage_path(('app/public/external/sql_export.sql')), $string);
    }
}
