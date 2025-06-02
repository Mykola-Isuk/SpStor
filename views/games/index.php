<?php
/** @var array $rows */
/** @var array $category */

use models\User;
use models\Library;

core\Core::getInstance()->pageParams['title'] = 'Список Ігор';

$libraryGameIds = [];
if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $libraryRows = Library::getGame($userId);
    $libraryGameIds = array_column($libraryRows, 'game_id');
}
?>

<h2 class="h3 mb-5 fw-normal text-center">Список Ігор</h2>
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


<?php if (User::isAdmin()) : ?>
    <a href="/games/add/0" class="btn btn-success mb-3">Додати гру</a>
<?php endif; ?>

<?php if (!empty($rows)) : ?>
    <div class="row row-cols-1 row-cols-md-3 g-5 categoris-list"style="margin-left: 15px;">
        <?php foreach ($rows as $row) : ?>
            <?php
            $photoPath = 'files/game/' . $row['photo'];
            $photoUrl = is_file($photoPath) ? '/' . $photoPath : '/static/imges/no-imag.png';
            ?>
            <div class="col">
                <div class="card h-100 game-card" style="width: 18rem; background-color: #58515c;">
                    <a href="/games/view/<?= $row['id'] ?>">
                        <img src="<?= $photoUrl ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>">
                    </a>
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>

                        <?php if (!empty($row['short_text'])) : ?>
                            <p class="card-text"><?= htmlspecialchars($row['short_text']) ?></p>
                        <?php endif; ?>

                        <p class="card-text fw-bold"><?= number_format($row['price'], 2) ?> грн</p>
                    </div>


                    <div class="game-actions">
                        <?php if (isset($_SESSION['user'])) : ?>
                            <?php if (in_array($row['id'], $libraryGameIds)) : ?>
                                <a href="/library/delete?game_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Видалити з бібліотеки</a>
                            <?php else : ?>
                                <a href="/library/add?game_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Додати в бібліотеку</a>
                            <?php endif; ?>
                        <?php else : ?>
                            <a href="/games/view/<?= $row['id'] ?>" class="btn btn-primary btn-sm">Детальніше</a>
                        <?php endif; ?>

                        <?php if (User::isAdmin()) : ?>
                            <a href="/games/delete/<?= $row['id'] ?>" class="btn btn-danger btn-sm">Видалити</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-warning text-center">Немає доступних ігор.</div>
<?php endif; ?>
