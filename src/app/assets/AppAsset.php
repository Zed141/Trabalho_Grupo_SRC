<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main app application asset bundle.
 */
class AppAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/static/theme/css/tabler.min.css',
        '/static/theme/css/tabler-flags.min.css',
        '/static/theme/css/tabler-payments.min.css',
        '/static/theme/css/tabler-vendors.min.css',
        '/static/theme/css/inter-font.css',
        //'/static/theme/css/demo.min.css'
    ];

    public $js = [
        '/static/theme/js/tabler.min.js',
        //'/static/theme/js/demo.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
