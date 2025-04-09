<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;

class DevCommand extends Command
{
    protected $signature = 'manual:dev-command';

    protected $description = 'Command description';

    public function handle()
    {
        $this->output->title('Start PW Links redirects');

        $linksRaw = [];
        $rows = SimpleExcelReader::create(storage_path('app/pw-hu-links.xlsx'))->getRows();

        $rows->each(function (array $rowProperties) use (&$linksRaw) {
            $linksRaw[] = $rowProperties['URL'];
        });

        $correctLinks = [];
        $this->output->progressStart(count($linksRaw));
        foreach ($linksRaw as $key => $linkRaw) {
            $link = str_replace('https://party-world.hu', '', $linkRaw);
            $path = '/';
            $correctLinks[] = [
                'from' => $link,
                'to' => $path,
            ];
            $this->output->progressAdvance();
        }
        $correctLinks = json_encode($correctLinks, JSON_PRESERVE_ZERO_FRACTION);
        file_put_contents(storage_path('app/pw-links.json'), $correctLinks);

        $this->output->progressFinish();
    }
}
