<?php
declare(strict_types=1);

namespace modules\guide\models;

/** Read locally generated guide drafts for a specific RAWG game ID. */
class Guide
{
    public function forGame(int $gameId, bool $includeDrafts = false): array
    {
        if ($gameId <= 0) {
            return [];
        }
        // This file lives in modules/game/models; three levels up is project root.
        $directory = dirname(__DIR__, 3) . '/storage/guides/' . $gameId;
        if (!is_dir($directory)) {
            return [];
        }
        $guides = [];
        foreach (glob($directory . '/*.json') ?: [] as $file) {
            $raw = file_get_contents($file);
            if ($raw === false) {
                continue;
            }
            $item = json_decode($raw, true);
            if (!is_array($item) || (int)($item['game_id'] ?? 0) !== $gameId || !isset($item['title'], $item['slug'])) {
                continue;
            }
            // Explicitly permit drafts for local tests only.
            if (($item['status'] ?? '') !== 'published' && !$includeDrafts) {
                continue;
            }
            $guides[] = $item;
        }
        usort($guides, static fn(array $a, array $b): int => strcmp((string)$a['title'], (string)$b['title']));
        return $guides;
    }


    public function find(
        int $gameId,
        string $slug,
        bool $includeDrafts = false
    ): ?array {

        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            return null;
        }

        // Reuse the existing guide loader
        $guides = $this->forGame($gameId, $includeDrafts);

        foreach ($guides as $guide) {
            if ($guide['slug'] === $slug) {
                return $guide;
            }
        }

        return null;
    }

}

