<?php
declare(strict_types=1);

// Offline regression test: real Home -> GameDataService fallback, fake IGDB transport.
namespace app\services {
    class IgdbClient {
        public static array $calls = [];
        public function games(array $params): array {
            self::$calls[] = ['games', $params];
            return ['results' => [[
                'id' => 'igdb-42', 'name' => 'Fixture game',
                'background_image' => 'https://example.org/cover.jpg',
                'rating_source' => 'IGDB',
            ]]];
        }
        public function game(int $id): array {
            self::$calls[] = ['game', $id];
            return ['id' => 'igdb-' . $id, 'rating_source' => 'IGDB'];
        }
        public function trailers(int $id): array { return []; }
        public function screenshots(int $id): array { return []; }
    }
}
namespace {
    $root = dirname(__DIR__);
    require $root . '/app/services/GameDataAdapter.php';
    require $root . '/app/services/GameDataService.php';
    require $root . '/modules/home/models/Home.php';
    require $root . '/app/widgets/GalleryWidget.php';

    function check(bool $condition, string $message): void {
        if (!$condition) throw new \RuntimeException($message);
    }
    // Turn PHP warnings (such as the removed apiKey property) into failures.
    set_error_handler(static function ($severity, $message, $file, $line) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    });
    putenv('RAWG_DISABLED=1');
    $home = new \modules\home\models\Home();
    foreach (['getGames', 'getTop10', 'getUpcoming'] as $method) {
        check($home->$method()[0]['id'] === 'igdb-42', $method . ' must use IGDB fallback');
    }
    $before = count(\app\services\IgdbClient::$calls);
    check($home->getTrailerGames() === [], 'Homepage must avoid IGDB trailer fan-out');
    check(count(\app\services\IgdbClient::$calls) === $before + 1, 'Only one trailer list request');
    $service = new \app\services\GameDataService();
    check($service->fetch('games/igdb-42')['id'] === 'igdb-42', 'IGDB detail ID must survive');
    $before = count(\app\services\IgdbClient::$calls);
    check($service->fetch('games/42') === null, 'Never use a RAWG ID against IGDB');
    check(count(\app\services\IgdbClient::$calls) === $before, 'RAWG detail must not hit IGDB');

    $gallery = \app\widgets\GalleryWidget::render([[
        'id' => 'igdb-42', 'image' => 'https://example.org/cover.jpg', 'title' => 'Fixture',
    ]]);
    check(str_contains($gallery, "href='/games/igdb-42'"), 'Gallery must preserve IGDB ID');

    $router = file_get_contents($root . '/public/index.php');
    check(preg_match("~'games/(\\(.*?\\))' => .*?'method' => 'show'~", $router, $match) === 1, 'Detail route exists');
    foreach (['42', 'igdb-42'] as $id) {
        check(preg_match('~^games/' . $match[1] . '$~', 'games/' . $id) === 1, 'Route accepts ' . $id);
    }
    check(preg_match('~^games/' . $match[1] . '$~', 'games/loadMore') === 0, 'Pagination route remains separate');

    $controller = file_get_contents($root . '/modules/home/controllers/HomeController.php');
    check(!str_contains($controller, "(int) \$game['id']"), 'Homepage must preserve provider-prefixed IDs');
    check(!str_contains($controller, "\$data['results']"), 'Homepage must inspect its actual games array');

    echo "Game data regression tests passed.\n";
}
