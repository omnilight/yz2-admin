<?php
use yii\helpers\Html;
use yz\icons\Icons;

/**
 * @var \yii\base\View $this
 * @var array $menuItems
 */
/** @var \yz\admin\widgets\MainMenu $context */
$context = $this->context;
?>
<ul class="sidebar-menu">
    <?php foreach ($menuItems as $group): ?>
        <li class="treeview <?= $group['active']?'active':'' ?>">
            <a href="#">
                <?= (isset($group['icon']) ? $group['icon']->ac('fa-fw') . ' ' : '') ?>
                <span><?= Html::encode($group['label']); ?></span>
                <?= Icons::i('angle-left fa-fw pull-right') ?>
            </a>
            <?php if (is_array($group['items']) && count($group['items']) > 0): ?>
                <ul class="treeview-menu">
                    <?php foreach ($group['items'] as $item): ?>
                        <li class="item <?= $item['active']?'active':'' ?>">
                            <?= Html::a((isset($item['icon']) ? $item['icon']->ac('fa-fw'): Icons::i('angle-double-right fa-fw')) . ' ' . $item['label'], $item['route']) ?></li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
        </li>
    <?php endforeach; ?>
</ul>