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
        <li><?= (isset($group['icon'])?$group['icon']->ac('fa-fw fa-lg').' ':'') . Html::encode($group['label']); ?></li>
        <?php foreach ($group['items'] as $item): ?>
            <li><?= Html::a((isset($item['icon'])?$item['icon']->ac('fa-fw').' ':'') . $item['label'], $item['route']) ?></li>
        <?php endforeach ?>
    <?php endforeach; ?>
</ul>