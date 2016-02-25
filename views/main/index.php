<?php
/**
 * @var yii\web\View $this
 */

$this->title = 'Main page';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php \yz\admin\widgets\Box::begin() ?>
    <h1>Wellcome to the Administration panel!</h1>

    <p>Please, select menu item to the left to perform some action</p>
<?php \yz\admin\widgets\Box::end() ?>