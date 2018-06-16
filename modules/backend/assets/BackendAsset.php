<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class BackendAsset extends AssetBundle
{
    public $sourcePath ='@app/modules/backend/assets/';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'skin.js',
        'backend.js',
        'jquery.jqprint-0.3.js',
        'http://www.jq22.com/jquery/jquery-migrate-1.2.1.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'dmstr\web\AdminLteAsset',
//        'mdm\admin\AutocompleteAsset',
//        'app\modules\backend\assets\AdminLtePluginsAsset',
    ];
}
