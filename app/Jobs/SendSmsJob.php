<?php

namespace App\Jobs;

use App\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function handle()
    {
        $response = Http::post('https://infocenter-dampalit.site/api/send-sms-all', [
            'type' => $this->sms->type,
            'message' => $this->sms->message,
        ]);

        Log::info($response->json());
    }
}
