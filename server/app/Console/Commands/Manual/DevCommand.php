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
        $this->output->title('Start Links redirects');

        $linksRaw = [];
        $rows = SimpleExcelReader::create(storage_path('app/etool-redirects.xlsx'))->getRows();

        $rows->each(function (array $rowProperties) use (&$linksRaw) {
            $linksRaw[] = [
                'origin' => $rowProperties['Origin'],
                'redirect' => $rowProperties['Redirect'],
            ];
        });

        $correctLinks = [];
        $this->output->progressStart(count($linksRaw));
        foreach ($linksRaw as $key => $linkRaw) {
            $this->output->progressAdvance();
            $originLink = $linkRaw['origin'];
            $restrictedValues = ['png', 'jpg', 'jpeg', 'webp', 'gif', 'mp4', 'avi', 'mov', 'mkv', 'flv', 'wmv', 'wp-content', 'css', 'js', 'fonts', 'font', 'woff', 'woff2', 'ttf', 'eot', 'svg', 'ico', 'pdf'];
            foreach ($restrictedValues as $restrictedValue) {
                if (str_contains($originLink, $restrictedValue)) {
                    continue 2;
                }
            }

            $linkRaw['redirect'] = $linkRaw['redirect'] == 'CHYBÍ STRÁNKA' ? '/' : $linkRaw['origin'];
            $linkRaw['redirect'] = str_contains('wp-', $linkRaw['redirect']) ? '/' : $linkRaw['redirect'];

            $correctLinks[] = [
                'from' => str_replace(['https://www.etool.cz', 'http://www.etool.cz'], '/', $linkRaw['origin']),
                'to' => $linkRaw['redirect'],
            ];
        }
        $correctLinks = json_encode($correctLinks, JSON_PRESERVE_ZERO_FRACTION);
        file_put_contents(storage_path('app/etool-links.json'), $correctLinks);

        $this->output->progressFinish();
    }
}
