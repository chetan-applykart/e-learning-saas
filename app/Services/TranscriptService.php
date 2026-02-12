<?php

namespace App\Services;

use Prism\Prism\Facades\Prism;
use Prism\Prism\ValueObjects\Media\Audio;
use Prism\Prism\ValueObjects\Media\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use App\AI\Tools\LangcertSpeakingScoreTool;
use App\Traits\UsesPrismConfig;

class TranscriptService
{
     use UsesPrismConfig;
    public static function transcript(?string $questionText, string $audioPath, ?string $imagePath = null): array
    {
        $llmProvider = env('ACTIVE_LLM_PROVIDER', 'openai');
        $llmModel    = env('ACTIVE_LLM_MODEL', 'gpt-4o-mini');
        $temperature = (float) env('ACTIVE_LLM_TEMPERATURE', 0.1);

        $sttProvider = env('ACTIVE_STT_PROVIDER', 'openai');
        $sttModel    = env('ACTIVE_STT_MODEL', 'whisper-1');


        $stt = Prism::audio()
            ->using($sttProvider, $sttModel)
            ->withInput(Audio::fromLocalPath($audioPath))
            ->asText();

        $transcript = trim($stt->text ?? '');
    }
}
