<?php

namespace App\Console\Commands;

use App\Models\Site\Site;
use App\Models\User\User;
use App\Services\FakturoidService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DemoReset extends Command
{
    protected $signature = 'demo:reset';

    protected $description = 'Reset demo data';

    public function handle(): int
    {
        $demoUser = User::query()
            ->where('email', 'demo@martinhanzl.cz')
            ->first();

        if ($demoUser) {
            $demoUser->password = Hash::make('rqsvF4s4l3Bj');
            $demoUser->save();
        }

        return self::SUCCESS;
    }
}
