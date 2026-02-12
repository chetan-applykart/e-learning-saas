<?php

namespace App\Traits;

trait UsesPrismConfig
{
    protected function getPrismProvider(): string
    {
        return config('services.prism.provider', env('ACTIVE_LLM_PROVIDER'));
    }

    protected function getPrismModel(): string
    {
        return config('services.prism.model', env('ACTIVE_LLM_MODEL'));
    }
    protected function getSttProvider(): string
    {
        return config('services.prism.stt_provider', env('ACTIVE_STT_PROVIDER', 'openai'));
    }

    protected function getSttModel(): string
    {
        return config('services.prism.stt_model', env('ACTIVE_STT_MODEL', 'whisper-1'));
    }
}
