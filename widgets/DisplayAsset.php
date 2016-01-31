<?php

namespace lo\modules\vote\widgets;

use yii\web\AssetBundle;

/**
 * Class BlockAsset
 * Ассет виджета скрываемой области
 * @package lo\modules\vote\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DisplayAsset extends AssetBundle
{

    public $js = [
        'vote.js',
    ];
    public $css = [
        'vote.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }

}