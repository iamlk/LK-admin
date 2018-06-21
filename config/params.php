<?php 
 return array (
    'appName' => '重晶石粉称重管理系统',
    'logo' => '@web/logo.png',
    'keywords' => '',
    'description' => '',
    'cacheDuration' => '-1',
    'pageSize' => '100',
    'mdm.admin.configs' => [
        'menuTable' => 'admin_menu',
    ],
    'nav' => '{
    "options": {
        "class": "nav navbar-nav navbar-right"
    },
    "items": [
        {
            "label": "首页",
            "url": [
                "/site/index"
            ]
        },
        {
            "label": "产品",
            "url": [
                "/products/list"
            ],
            "activeUrls": [
                "/products/index"
            ]
        },
        {
            "label": "新闻",
            "url": [
                "/news/list"
            ],
            "activeUrls": [
                "/news/index"
            ]
        },
        {
            "label": "下载",
            "url": [
                "/downloads/list"
            ],
            "activeUrls": [
                "/downloads/index"
            ]
        },
        {
            "label": "关于我们",
            "url": [
                "/site/about"
            ],
            "items": [
                {
                    "label": "企业荣誉",
                    "url": {
                        "0":"/site/page/honor", "id":"honor"
                    }
                }
            ]
        },
        {
            "label": "联系我们",
            "url": [
                "/site/contact"
            ]
        }
    ]
}',
);
