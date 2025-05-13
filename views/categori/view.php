<?php
/** @var array|null $categori */
/** @var array $games */

use models\User;

// Перевірка, чи категорія існує
if ($categori) {
    core\Core::getInstance()->pageParams['title'] = "Список ігор {$categori['name']}";
} else {
    core\Core::getInstance()->pageParams['title'] = "Категорія не знайдена";
}
?>
<?php if($categori): ?>
    <h1 class="h3 mb-5 fw-normal text-center"><?= htmlspecialchars($categori['name']) ?></h1>

    <?php if(User::isAdmin()) : ?>
        <a href="/games/add/<?= $categori['id'] ?>" class="btn btn-success mb-3">Додати гру</a>
    <?php endif; ?>

    <link rel="stylesheet" href="/thems/MainThem/css/cat.css" />
    <div class="row row-cols-1 row-cols-md-3 g-4 categoris-list">
        <?php foreach ($games as $game) : ?>
            <div class="col">
                <a href="/games/view/<?= $game['id'] ?>" class="card-link">
                    <center>
                        <div class="card" style="width: 18rem; background-color: #58515c;">
                            <?php $filePath = 'files/game/' . $game['photo']; ?>
                            <?php if (is_file($filePath)) : ?>
                                <img src="/<?= $filePath ?>" class="img-fluid rounded-start" alt="">
                            <?php else : ?>
                                <img src="/static/imges/no-imag.png" class="img-fluid rounded-start" alt="">
                            <?php endif; ?>

                            <div class="card-body">
                                <h4 class="card-title"><?= htmlspecialchars($game['name']) ?></h4>
                            </div>
                            <p class="card-text"><?= htmlspecialchars($game['short_text']) ?></p>

                            <div class="card-body">
                                <p class="card-text"><?= (int)$game['price'] ?>.00 грн.</p>
                            </div>

                            <?php if(User::isAdmin()) : ?>
                                <div class="card-body">
                                    <a href="/games/delete/<?= $game['id'] ?>" class="btn btn-danger">Видалити</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </center>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p class="text-center">Категорія не знайдена.</p>
<?php endif; ?>
