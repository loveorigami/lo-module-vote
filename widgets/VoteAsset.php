<?php

namespace lo\modules\vote\widgets;

use yii\web\AssetBundle;

/**
 * Class VoteAsset
 * Ассет виджета голосования
 * @package lo\modules\vote\widgets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class VoteAsset extends AssetBundle
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