<?php

namespace App\Traits;

use Throwable;

trait HandlesPrismToolResponse
{
    /**
     * Safely extract tool response from Prism AI output
     *
     * @param  mixed  $response
     * @param  string $key  (evaluation | analysis | score etc.)
     * @return array
     */
    protected function extractToolData($response, string $key = 'evaluation'): array
    {
        try {
            if (
                empty($response->toolCalls) ||
                !isset($response->toolCalls[0]->arguments)
            ) {
                return $this->toolError(
                    'TOOL_CALL_MISSING',
                    'AI did not return tool output.'
                );
            }

            $arguments = $response->toolCalls[0]->arguments;

            if (is_string($arguments)) {
                $arguments = json_decode($arguments, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $this->toolError(
                        'INVALID_JSON',
                        'AI returned malformed JSON.'
                    );
                }
            }

            if (!isset($arguments[$key]) || !is_array($arguments[$key])) {
                return $this->toolError(
                    'KEY_MISSING',
                    "Expected '{$key}' not found in AI response."
                );
            }

            return $arguments[$key];

        } catch (Throwable $e) {
            return $this->toolError(
                'EXCEPTION',
                $e->getMessage()
            );
        }
    }

   
    protected function toolError(string $code, string $message): array
    {
        return [
            'error' => true,
            'error_code' => $code,
            'error_message' => $message,
        ];
    }
}
