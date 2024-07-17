<?php

return [

    'pdf' => [
        'enabled' => true,
        //'binary'  => '/usr/local/bin/wkhtmltopdf',
        'binary' => 'D:\wkhtmltopdf\bin\wkhtmltopdf.exe',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        //'binary'  => '/usr/local/bin/wkhtmltoimage',
        'binary' => 'D:\wkhtmltopdf\bin\wkhtmltoimage.exe',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
