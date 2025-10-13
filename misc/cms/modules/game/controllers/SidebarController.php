<?php
namespace modules\game\controllers;

use modules\game\models\Sidebar;

class SidebarController
{
    public function index()
    {
        $tab = $_GET['tab'] ?? 'tba';
        $sidebar = new Sidebar();

        switch ($tab) {
            case 'metacritic':
                $games = $sidebar->getTopMetacritic();
                break;

            default:
                $games = $sidebar->getGames();
                break;
        }

        require __DIR__ . '/../views/sidebar.php';
    }
}
