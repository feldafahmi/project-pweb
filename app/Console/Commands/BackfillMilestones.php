<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class BackfillMilestones extends Command
{
    protected $signature = 'milestones:backfill';

    protected $description = 'Buat milestone bawaan untuk user lama yang belum memilikinya';

    public function handle(): int
    {
        $users = User::doesntHave('milestones')->get();

        if ($users->isEmpty()) {
            $this->info('Semua user sudah memiliki milestone. Tidak ada yang perlu diisi.');
            return self::SUCCESS;
        }

        $this->withProgressBar($users, function (User $user) {
            $user->seedDefaultMilestones();
        });

        $this->newLine(2);
        $this->info("Selesai. Milestone bawaan dibuat untuk {$users->count()} user.");

        return self::SUCCESS;
    }
}
