<?php
use yii\helpers\Html;
/**
 * @var \yii\base\View $this
 * @var array $menuItems
 */
/** @var \yz\admin\widgets\MainMenu $context  */
$context = $this->context;
?>
<ul class="b-mainMenu nav nav-bar">
    <?php foreach ($menuItems as $group): ?>
        <li class="group"><?= (isset($group['icon'])?$group['icon']->ac('fa-fw fa-lg').' ':'') . Html::encode($group['label']); ?></li>
        <?php foreach ($group['items'] as $item): ?>
            <li class="item"><?= Html::a((isset($item['icon'])?$item['icon']->ac('fa-fw').' ':'') . $item['label'], $item['route']) ?></li>
        <?php endforeach ?>
    <?php endforeach; ?>
</ul>