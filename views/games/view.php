<?php
use models\Library;
/** @var array $game */
/** @var array $libraryGameIds */

core\Core::getInstance()->pageParams['title'] = "{$game['name']}";

$isInLibrary = isset($libraryGameIds) && in_array($game['id'], $libraryGameIds);
$foto = 'files/game/' . $game['photo'];
?>

<h1 class="mb-5 fw-normal text-center"><?= htmlspecialchars($game['name']) ?></h1>
<hr class="featurette-divider mb-5">

<div class="row featurette">
    <div class="col-md-7 order-md-2">
        <h2 class="display-5 fw-bold lh-1 mb-5">Опис гри</h2>
        <p class="lead mb-5"><?= htmlspecialchars($game['short_text']) ?></p>
        <h3 class="fw-bold lh-1 mb-5"><b>Ціна : <?= htmlspecialchars($game['price']) ?> грн</b></h3>

        <?php if ($isInLibrary): ?>
            <a href="/library/delete?game_id=<?= $game['id'] ?>" class="btn btn-danger">Видалити з бібліотеки</a>
        <?php else: ?>
            <a href="/library/add?game_id=<?= $game['id'] ?>" class="btn btn-primary">Додати в бібліотеку</a>
        <?php endif; ?>
    </div>

    <div class="col-md-5 order-md-1">
        <img src="/<?= htmlspecialchars($foto) ?>" class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" alt="<?= htmlspecialchars($game['name']) ?>">
    </div>

</div>
