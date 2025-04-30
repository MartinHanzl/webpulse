<?php

namespace App\Console\Commands;

use App\Services\FakturoidService;
use Illuminate\Console\Command;

class SyncFakturoid extends Command
{
    protected $signature = 'fakturoid:sync';

    protected $description = 'Sync data with fakturoid';

    protected FakturoidService $fakturoidService;

    public function __construct()
    {
        parent::__construct();
        $this->fakturoidService = new FakturoidService();
    }

    public function exportSubjects(): void
    {
        $this->output->title('Exporting subjects to fakturoid');


    }
}
