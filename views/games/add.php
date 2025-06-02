<?php
/** @var array $errors */
/** @var array $model */
/** @var array $categoris */
/** @var int|null $categori_id */
core\Core::getInstance()->pageParams['title'] = 'Додавання Гри';
?>

<h2 class="h3 mb-5 fw-normal text-center">Додавання Гри</h2>

<form action="" method="post" enctype="multipart/form-data" style="min-width: 500px;" class="p-4 p-md-5 border rounded-3 form-signin m-auto">
    <div class="mb-2">
        <label for="name" class="form-label">Назва Гри</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($model['name'] ?? '') ?>">
        <?php if (!empty($errors['name'])) : ?>
            <span class="error text-danger"><?= $errors['name']; ?></span>
        <?php endif; ?>
    </div>

    <div class="mb-2">
        <label for="categorie_id" class="form-label">Назва Категорії</label>
        <select class="form-control" id="categorie_id" name="categorie_id">
            <option value="">-- Оберіть категорію --</option>
            <?php foreach ($categoris as $category) : ?>
                <option value="<?= $category['id'] ?>" <?= ($category['id'] == $categori_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['categorie_id'])) : ?>
            <span class="error text-danger"><?= $errors['categorie_id']; ?></span>
        <?php endif; ?>
    </div>

    <div class="mb-2">
        <label for="price" class="form-label">Ціна</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= htmlspecialchars($model['price'] ?? '') ?>">
        <?php if (!empty($errors['price'])) : ?>
            <span class="error text-danger"><?= $errors['price']; ?></span>
        <?php endif; ?>
    </div>

    <div class="mb-2">
        <label for="short_text" class="form-label">Опис</label>
        <textarea class="form-control" id="short_text" name="short_text"><?= htmlspecialchars($model['short_text'] ?? '') ?></textarea>
        <?php if (!empty($errors['short_text'])) : ?>
            <span class="error text-danger"><?= $errors['short_text']; ?></span>
        <?php endif; ?>
    </div>

    <div class="mb-2">
        <label for="visible" class="form-label">Чи потрібно відображати гру</label>
        <select class="form-control" id="visible" name="visible">
            <option value="1" <?= (!isset($model['visible']) || $model['visible'] == 1) ? 'selected' : '' ?>>так</option>
            <option value="0" <?= (isset($model['visible']) && $model['visible'] == 0) ? 'selected' : '' ?>>ні</option>
        </select>
        <?php if (!empty($errors['visible'])) : ?>
            <span class="error text-danger"><?= $errors['visible']; ?></span>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="file" class="form-label">Виберіть картинку</label>
        <input class="form-control" type="file" id="file" name="file" accept="image/*">
    </div>

    <div>
        <button class="btn btn-primary">Додати гру</button>
    </div>
</form>
