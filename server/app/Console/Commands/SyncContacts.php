<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Revolution\Google\Sheets\Facades\Sheets;
use Spatie\SimpleExcel\SimpleExcelWriter;

class SyncContacts extends Command
{
    use ResponseTrait;

    protected $signature = 'contacts:sync';

    protected $description = 'Sync contacts from XLXS file and import them to google spreadsheet.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->output->title('Syncing contacts...');

        $rows = SimpleExcelReader::create(storage_path('app/Kontakty-3102023.xlsx'))->getRows();

        $writer = SimpleExcelWriter::create(storage_path('app/contacts.xlsx'));

        $rows->each(function (array $rowProperties) use ($writer) {
            $row = $this->parseRow($rowProperties);
            $writer->addRow($row);

            /*Sheets::spreadsheet('1wRQfN39iXeznrIfhZPjKC5an27hQSRgCvHBPj_5SHUk')
                ->sheet('test')->append([$row]);*/
        });
    }

    private function parseRow(array $row): array
    {
        $phase = 'Nekontaktován/a';
        if ($row['KM1'] == 'Ano' || $row['KM1'] == 'ano') {
            $phase = 'PO KM1';
        } else if ($row['Kontaktován/a'] == 'Ano' || $row['Kontaktován/a'] == 'ano') {
            $phase = 'Kontaktován/a';
        } else if ($row['Kontaktován/a'] == 'Ne' || $row['Kontaktován/a'] == 'ne') {
            $phase = 'Nekontaktován/a';
        }

        $source = null;
        switch ($row['Skupina']) {
            case 'Brigáda':
            case 'brigáda':
                $source = "McDonald's";
                break;
            case 'Wondermakers':
                $source = "Wonder Makers";
                break;
            default:
                $source = Str::ucfirst(Str::lower($row['Skupina']));
        }

        $data = [
            $row['Příjmení'] . ' ' . $row['Jméno'],
            !in_array($row['Telefon'], ['0', '1', '2', '3', '4', '-', 0, 1, 2, 3, 4]) ? $row['Telefon'] : null,
            $phase,
            $row['Co dělá'] !== '' ? ucfirst(Str::lower($row['Co dělá'])) : '-',
            $row['Kam míří'] !== '' ? ucfirst(Str::lower($row['Kam míří'])) : '-',
            $source,
            null,
            $row['Poslední kontakt'] !== '' ? Carbon::parse($row['Poslední kontakt'])->format('d. m. Y') : null,
            $row['Poznámky'] !== '' ? $row['Poznámky'] : null,
        ];
        //dd($data);

        return $data;
    }
}
