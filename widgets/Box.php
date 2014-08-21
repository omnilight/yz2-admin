<?php

namespace yz\admin\widgets;
use yii\base\Widget;
use yii\helpers\Html;


/**
 * Class Box
 * @package \yz\admin\widgets
 */
class Box extends Widget
{
    /**
     * @var string Box CSS classes. This is used to simplify access to the class property
     */
    public $cssClass;
    /**
     * @var array
     */
    public $options;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string template of the box
     */
    public $template = "<div {options}>\n{title}\n{content}</div>";
    /**
     * @var string template for the box title
     */
    public $titleTemplate = "<div class=\"box-header\"><h3 class=\"box-title\">{title}</h3></div>";
    public $bodyOptions = ['class' => 'box-body'];
    public $footerOptions = ['class' => 'box-footer'];

    public function init()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    public function run()
    {
        $boxContent = ob_get_clean();
        $boxTitle = $this->title ? strtr($this->titleTemplate, ['{title}' => $this->title]) : '';
        Html::addCssClass($this->options, 'box');
        if ($this->cssClass)
            Html::addCssClass($this->options, $this->cssClass);

        echo strtr($this->template, [
            '{options}' => Html::renderTagAttributes($this->options),
            '{title}' => $boxTitle,
            '{content}' => $boxContent
        ]);
    }

    /**
     * Sets title property for the box
     * @param string $title for the box
     */
    public function title($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @param array $options can override class bodyOptions
     */
    public function beginBody($options = [])
    {
        echo Html::beginTag('div', array_merge($this->bodyOptions, $options));
    }

    public function endBody()
    {
        echo Html::endTag('div');
    }

    public function beginFooter($options = [])
    {
        echo Html::beginTag('div', array_merge($this->footerOptions, $options));
    }

    public function endFooter()
    {
        echo Html::endTag('div');
    }
} 