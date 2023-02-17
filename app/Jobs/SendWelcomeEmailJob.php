<?php

namespace App\Jobs;

use App\Mail\WelcomeEmail;
use App\Models\Tenant;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private array $details;

    /**
     * Create a new job instance.
     *
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            if (isEmailValid($this->details['email'])) {
                $tenant = Tenant::find($this->details['tenant_id']);
                $loginUrl = makeTenantUrl(route('login'), $tenant);

                Mail::to($this->details['email'])->send(new WelcomeEmail([
                    'email' => $this->details['email'],
                    'password' => $this->details['password'],
                    'login_url' => $loginUrl,
                ]));
            }
        } catch (Exception $e) {
            Log::error("Send welcome email error: " . $e->getMessage());
        }
    }
}
