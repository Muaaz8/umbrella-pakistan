<?php

namespace App\Jobs;

use App\Http\Controllers\WhatsAppController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadLabMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath,$user)
    {
        $this->filePath = $filePath;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $whatsapp = new WhatsAppController();
        $res = $whatsapp->upload_media($this->filePath);
        $res = $whatsapp->send_lab_receipt($this->user,$res);
    }

    public function failed(\Throwable $exception)
    {
        \Log::error('UploadMediaJob failed: '.$exception->getMessage());
    }
}
