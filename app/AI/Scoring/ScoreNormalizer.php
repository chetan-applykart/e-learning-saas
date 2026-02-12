<?php

namespace App\AI\Scoring;

class ScoreNormalizer
{
    /**
     * LangCert / CELPIP style normalization
     * LLM → band estimate
     * PHP → final marks (FIXED)
     */
    public static function normalizeLangcert(array $raw): array
    {
        // --- TASK (0–16) ---
        $task = self::mapBand($raw['task_fulfilment'] ?? 0, [
            [0, 4, 4],
            [5, 8, 8],
            [9, 12, 12],
            [13, 16, 16],
        ]);

        // --- COHERENCE (0–8) ---
        $coherence = self::mapBand($raw['coherence'] ?? 0, [
            [0, 2, 2],
            [3, 4, 4],
            [5, 6, 6],
            [7, 8, 8],
        ]);

        // --- OTHER CRITERIA (0–8 each) ---
        $grammar       = self::mapBand($raw['grammar'] ?? 0);
        $vocabulary    = self::mapBand($raw['vocabulary'] ?? 0);
        $pronunciation = self::mapBand($raw['pronunciation'] ?? 0);

        return [
            'task_fulfilment' => $task,
            'coherence'       => $coherence,
            'grammar'         => $grammar,
            'vocabulary'      => $vocabulary,
            'pronunciation'   => $pronunciation,

            // 🔒 PHP decides total
            'total_score'     => $task + $coherence + $grammar + $vocabulary + $pronunciation,

            // feedback text (LLM allowed)
            'language_issues'  => $raw['language_issues']  ?? '',
            'overall_feedback' => $raw['overall_feedback'] ?? '',
            'improvement_tips' => $raw['improvement_tips'] ?? '',
        ];
    }

    /**
     * Generic 0–8 band mapper
     */
    private static function mapBand(int $value, ?array $custom = null): int
    {
        $bands = $custom ?? [
            [0, 2, 2],
            [3, 4, 4],
            [5, 6, 6],
            [7, 8, 8],
        ];

        foreach ($bands as [$min, $max, $fixed]) {
            if ($value >= $min && $value <= $max) {
                return $fixed;
            }
        }

        return max(0, min(8, $value));
    }
}
