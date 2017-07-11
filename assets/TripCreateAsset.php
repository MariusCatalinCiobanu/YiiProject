<?php

namespace app\assets;

use yii\web\AssetBundle;

class TripCreateAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/trip/getCoordinates.js',
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyDiBDng41jiC6mOQ9oC5ySOQKwxQWQ2z_Y&callback=initMap'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}
