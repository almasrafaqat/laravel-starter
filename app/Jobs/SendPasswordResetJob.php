<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\PasswordResetNotification;

class SendPasswordResetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $resetUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lang = $this->user->language ?? config('app.fallback_locale');
        App::setLocale($lang);
        $this->user->notify(new PasswordResetNotification($this->resetUrl));
    }
}