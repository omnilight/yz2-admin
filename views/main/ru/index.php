<?php
/**
 * @var yii\web\View $this
 */

$this->title = 'Главная страница';
$this->params['breadcrumbs'][] = $this->title;
$this->params['header'] = $this->title;
?>
<?php \yz\admin\widgets\Box::begin() ?>

    <h1>Добро пожаловать в панель администрирования!</h1>

    <p>Выберите пункт меню слева для начала работы</p>

<?php \yz\admin\widgets\Box::end() ?>