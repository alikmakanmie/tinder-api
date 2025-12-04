<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Swipe;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CheckPopularUsers extends Command
{
    protected $signature = 'check:popular-users';
    protected $description = 'Check if any user got more than 50 likes and send email to admin';

    public function handle()
    {
        $popularUsers = Swipe::select('target_user_id', DB::raw('COUNT(*) as like_count'))
            ->where('action', 'like')
            ->groupBy('target_user_id')
            ->having('like_count', '>', 50)
            ->with('targetUser')
            ->get();

        if ($popularUsers->count() > 0) {
            foreach ($popularUsers as $swipe) {
                $user = $swipe->targetUser;
                
                // Send email to admin
                Mail::raw(
                    "User {$user->name} (ID: {$user->id}) has received {$swipe->like_count} likes!",
                    function ($message) {
                        $message->to('admin@example.com')
                            ->subject('Popular User Alert');
                    }
                );
                
                $this->info("Email sent for user: {$user->name}");
            }
        } else {
            $this->info('No users with more than 50 likes found.');
        }

        return 0;
    }
}
