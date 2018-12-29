<?php

/*$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            //'cookieValidationKey' => 'JHRvSzJrhZMAIdP0tl78jqaBTjUEIosJ',
            'cookieValidationKey' => 'nxg2hNWo3PBAAittucbsGEP8ULi06HcV',
        ],
    ],
];*/
$config = [
    'layout'=>'main',
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
