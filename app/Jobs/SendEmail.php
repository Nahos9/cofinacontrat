<?php

namespace App\Jobs;

use App\Mail\EmailSkeleton;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $receiverEmail;
    protected $subject;
    protected $content;

    /**
     * Create a new job instance.
     */
    public function __construct(string $receiverEmail, string $subject, string $content)
    {
        $this->receiverEmail = $receiverEmail;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to("charles.gamligo@cofinacorp.com")->send(
                new EmailSkeleton(
                    $this->subject,
                    $this->content
                )
            );
            // Mail::to($this->receiverEmail)->send(
            //     new EmailSkeleton(
            //         $this->subject,
            //         $this->content
            //     )
            // );
        } catch (Exception $ex) {
            dd($ex);
        }
    }
}
