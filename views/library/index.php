<?php
/** @var array $games */
/** @var array $rows */

use models\Game;
core\Core::getInstance()->pageParams['title'] = "Бібліотека";
?>

<h2 class="h3 mb-5 fw-normal text-center">Список Ігор в Бібліотеці</h2>
<link rel="stylesheet" href="/thems/MainThem/css/cat.css" />

<style>
    .game-card.selected {
        border: 2px solid #ffc107;
        background-color: #6c5c74 !important;
        transition: 0.2s;
    }

    .multi-actions {
        display: none;
        gap: 10px;
        margin-bottom: 15px;
    }

    .multi-actions.visible {
        display: flex;
        justify-content: end;
    }

    .select-mode .selectable {
        cursor: pointer;
    }

    .select-checkbox {
        position: absolute;
        top: 10px;
        left: 10px;
        display: none;
        z-index: 2;
    }

    .select-mode .select-checkbox {
        display: block;
    }

    .card {
        position: relative;
    }

    .card-link {
        text-decoration: none;
        color: inherit;
    }

    .btn-custom {
        background-color: #35204e;
        color: white;
        border: 1px solid white;
        transition: all 0.3s ease;
    }

    .btn-custom:hover {
        background-color: white;
        color: #35204e;
        border: 1px solid white;
    }

    .btn-invert-hover {
        transition: all 0.3s ease;
    }

    .btn-secondary.btn-invert-hover:hover {
        background-color: white !important;
        color: #6c757d !important;
        border-color: #6c757d;
    }

    .btn-info.btn-invert-hover:hover {
        background-color: white !important;
        color: #0dcaf0 !important;
        border-color: #0dcaf0;
    }

    .btn-danger.btn-invert-hover:hover {
        background-color: white !important;
        color: #dc3545 !important;
        border-color: #dc3545;
    }
</style>

<div class="text-end mb-3" id="selectBtnContainer">
    <button type="button" id="selectModeBtn" class="btn btn-custom" style="margin-right: 15px;">Вибрати</button>
</div>

<form method="post" action="/library/deleteMultiple" id="deleteForm" novalidate>
    <div class="multi-actions mb-3" id="multiActions">
        <button type="button" class="btn btn-secondary btn-invert-hover" id="cancelSelection">Відмінити вибір</button>
        <button type="button" class="btn btn-info btn-invert-hover" id="selectAll">Вибрати все</button>
        <button type="submit" class="btn btn-danger btn-invert-hover">Видалити вибрані</button>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-4 categoris-list" id="gamesContainer">
        <?php foreach ($rows as $row): ?>
            <?php $game = Game::getGameId($row['game_id']); ?>
            <?php $foto = 'files/game/' . $game['photo']; ?>
            <?php $fotoUrl = is_file($foto) ? '/' . $foto : '/static/imges/no-imag.png'; ?>
            <div class="col selectable" data-game-id="<?= $game['id'] ?>">
                <div class="card h-100 game-card" style="background-color: #58515c; width: 18rem; margin: 0 auto;">
                    <input type="checkbox" class="select-checkbox" name="selected_games[]" value="<?= $game['id'] ?>">
                    <a href="/games/view/<?= $game['id'] ?>" class="card-link game-link">
                        <img src="<?= $fotoUrl ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($game['name']) ?>">
                        <div class="card-body text-center text-white">
                            <h5 class="card-title"><?= htmlspecialchars($game['name']) ?></h5>
                        </div>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</form>

<script>
    const selectModeBtn = document.getElementById('selectModeBtn');
    const selectBtnContainer = document.getElementById('selectBtnContainer');
    const multiActions = document.getElementById('multiActions');
    const gamesContainer = document.getElementById('gamesContainer');
    const cancelSelectionBtn = document.getElementById('cancelSelection');
    const selectAllBtn = document.getElementById('selectAll');
    const deleteForm = document.getElementById('deleteForm');

    let selectMode = false;

    const updateLinksState = () => {
        document.querySelectorAll('.game-link').forEach(link => {
            link.classList.toggle('disabled', selectMode);
            if (selectMode) {
                link.addEventListener('click', preventLinkClick);
            } else {
                link.removeEventListener('click', preventLinkClick);
            }
        });
    };

    const preventLinkClick = (e) => e.preventDefault();

    const enterSelectionMode = () => {
        selectMode = true;
        document.body.classList.add('select-mode');
        multiActions.classList.add('visible');
        selectBtnContainer.style.display = 'none';
        updateLinksState();
    };

    const exitSelectionMode = () => {
        selectMode = false;
        document.body.classList.remove('select-mode');
        multiActions.classList.remove('visible');
        selectBtnContainer.style.display = 'block';
        updateLinksState();

        document.querySelectorAll('.game-card').forEach(card => card.classList.remove('selected'));
        document.querySelectorAll('.select-checkbox').forEach(cb => cb.checked = false);
    };

    selectModeBtn.addEventListener('click', enterSelectionMode);
    cancelSelectionBtn.addEventListener('click', exitSelectionMode);
    deleteForm.addEventListener('submit', () => setTimeout(exitSelectionMode, 300));

    selectAllBtn.addEventListener('click', () => {
        document.querySelectorAll('.game-card').forEach(card => card.classList.add('selected'));
        document.querySelectorAll('.select-checkbox').forEach(cb => cb.checked = true);
    });

    gamesContainer.addEventListener('click', (e) => {
        if (!selectMode) return;

        const col = e.target.closest('.col.selectable');
        if (!col) return;

        const checkbox = col.querySelector('.select-checkbox');
        const card = col.querySelector('.game-card');

        checkbox.checked = !checkbox.checked;
        card.classList.toggle('selected', checkbox.checked);
    });
</script>
