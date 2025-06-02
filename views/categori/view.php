<?php
/** @var array|null $categori */
/** @var array $games */

use models\User;
use models\Library;

if ($categori) {
    core\Core::getInstance()->pageParams['title'] = "Список ігор {$categori['name']}";
} else {
    core\Core::getInstance()->pageParams['title'] = "Категорія не знайдена";
}

$libraryGameIds = [];
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $libraryRows = Library::getGame($userId);
    $libraryGameIds = array_column($libraryRows, 'game_id');
}
?>

<?php if ($categori): ?>
    <h1 class="h3 mb-5 fw-normal text-center"><?= htmlspecialchars($categori['name']) ?></h1>

    <?php if (User::isAdmin()): ?>
        <a href="/games/add/<?= $categori['id'] ?>" class="btn btn-success mb-3">Додати гру</a>
    <?php endif; ?>

    <link rel="stylesheet" href="/thems/MainThem/css/cat.css" />
    <style>
        .game-card {
            position: relative;
            transition: transform 0.3s;
            overflow: hidden;
        }

        .game-card:hover .game-actions {
            opacity: 1;
            visibility: visible;
        }

        .game-actions {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: center;
            z-index: 2;
        }

        .game-actions a {
            opacity: 0.85;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
        }

        .game-actions a:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        .card-img-top {
            width: 100%;
            height: auto;
            display: block;
        }
    </style>

    <div class="row row-cols-1 row-cols-md-3 g-5 categoris-list"style="margin-left: 15px;">
        <?php foreach ($games as $game): ?>
            <?php
            $filePath = 'files/game/' . $game['photo'];
            $photoUrl = is_file($filePath) ? '/' . $filePath : '/static/imges/no-imag.png';
            ?>
            <div class="col">
                <div class="card h-100 game-card" style="width: 18rem; background-color: #58515c;">
                    <a href="/games/view/<?= $game['id'] ?>">
                        <img src="<?= $photoUrl ?>" class="card-img-top" alt="<?= htmlspecialchars($game['name']) ?>">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($game['name']) ?></h5>

                        <?php if (!empty($game['short_text'])): ?>
                            <p class="card-text"><?= htmlspecialchars($game['short_text']) ?></p>
                        <?php endif; ?>

                        <p class="card-text fw-bold"><?= (int)$game['price'] ?>.00 грн</p>
                    </div>

                    <div class="game-actions">
                        <?php if (isset($_SESSION['user'])): ?>
                            <?php if (in_array($game['id'], $libraryGameIds)): ?>
                                <a href="/library/delete?game_id=<?= $game['id'] ?>" class="btn btn-danger btn-sm">Видалити з бібліотеки</a>
                            <?php else: ?>
                                <a href="/library/add?game_id=<?= $game['id'] ?>" class="btn btn-success btn-sm">Додати в бібліотеку</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="/library/add?game_id=<?= $game['id'] ?>" class="btn btn-success btn-sm">Додати в бібліотеку</a>
                        <?php endif; ?>

                        <?php if (User::isAdmin()): ?>
                            <a href="/games/delete/<?= $game['id'] ?>" class="btn btn-danger btn-sm">Видалити</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-center">Категорія не знайдена.</p>
<?php endif; ?>
