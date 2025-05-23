<?php
/**@var array $game */
core\Core::getInstance()->pageParams['title'] = 'Видаленя Гри'
?>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
  </symbol>
</svg>
<div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
  <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:">
    <use xlink:href="#exclamation-triangle-fill" />
  </svg>
  <div>
    <b>УВАГА!</b>
    <p>Ви видаляєте гру (<?= $game['name'] ?>), ви впевнені у своїх діях ?</p>
  </div>
</div>
<div class="card-body ">
  <a href="/categori" class="btn btn-primary me-5">Відмінити</a>
  <a href="/games/delete/<?= $game['id'] ?>/yes" class="btn btn-danger ">Видалити</a>
</div>