<?php

namespace App\Jobs;

use App\Mail\ContactFormMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendContactFormEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $name;
    public $email;
    public $phone;
    public $contactMessage;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct($name, $email, $phone, $message)
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->contactMessage = $message;
    }

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all admin users
        $adminUsers = User::where('role', 'admin')->get();

        if ($adminUsers->isEmpty()) {
            Log::warning('SendContactFormEmail: No admin users found to send contact form email to.');
            return;
        }

        $successCount = 0;
        $failedCount = 0;
        $failedEmails = [];

        // Send email to each admin user individually to prevent one failure from stopping others
        foreach ($adminUsers as $admin) {
            try {
                Mail::to($admin->email)->send(
                    new ContactFormMail(
                        $this->name,
                        $this->email,
                        $this->phone,
                        $this->contactMessage
                    )
                );
                
                $successCount++;
                Log::info("SendContactFormEmail: Successfully sent contact form email to admin: {$admin->email} (ID: {$admin->id})");
            } catch (\Exception $e) {
                $failedCount++;
                $failedEmails[] = $admin->email;
                Log::error("SendContactFormEmail: Failed to send email to admin {$admin->email} (ID: {$admin->id}). Error: " . $e->getMessage(), [
                    'exception' => $e,
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'contact_name' => $this->name,
                    'contact_email' => $this->email,
                ]);
            }
        }

        // Log summary
        if ($successCount > 0) {
            Log::info("SendContactFormEmail: Successfully sent {$successCount} out of {$adminUsers->count()} contact form email(s).");
        }

        // If all emails failed, throw exception to trigger retry
        if ($failedCount === $adminUsers->count()) {
            $errorMessage = "SendContactFormEmail: All emails failed. Failed emails: " . implode(', ', $failedEmails);
            Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }

        // If some emails failed but at least one succeeded, log warning but don't throw
        if ($failedCount > 0 && $successCount > 0) {
            Log::warning("SendContactFormEmail: {$failedCount} email(s) failed to send. Failed emails: " . implode(', ', $failedEmails));
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendContactFormEmail: Job failed after all retries.', [
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'contact_name' => $this->name,
            'contact_email' => $this->email,
            'contact_phone' => $this->phone,
        ]);
    }
}
