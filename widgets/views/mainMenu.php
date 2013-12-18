<?php
use yii\helpers\Html;
/**
 * @var \yii\base\View $this
 * @var array $menuItems
 */
/** @var \yz\admin\widgets\MainMenu $context  */
$context = $this->context;
?>
<ul class="b-mainMenu nav nav-pills nav-stacked">
    <?php foreach ($menuItems as $group): ?>
        <li><?= Html::encode($group['label']); ?></li>
        <?php foreach ($group['items'] as $item): ?>
            <li><?= Html::a($item['label'], $item['route']) ?></li>
        <?php endforeach ?>
    <?php endforeach; ?>
</ul>