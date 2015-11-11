<?php

return [
    'uploads_path' => 'uploads/images',

    // Driver to use for creating the image object @example 'gd' or 'imagick' or 'gmagick'
    'driver' => 'gd',

    // Allowed filetypes necessary for validating the input image. Configuration per context
    'allowed_filetypes' => [
        'default' => [
            'image/jpeg' => [
                'jpeg',
                'jpg',
            ],
            'image/png' => [
                'png',
            ],
            'image/gif' => [
                'gif',
            ],
        ],
        'avatar' => [
            'image/jpeg' => [
                'jpeg',
                'jpg',
            ],
            'image/png' => [
                'png',
            ],
            'image/gif' => [
                'gif',
            ],
        ]
    ],

    'output_format' => [
        'default' => '.jpg',
        'avatar' => '.gif'
    ],

    'default' => [
        'small' => [
            'width' => 32,
            'height' => 32,
            'operation' => 'resize-crop',
        ],
        'medium' => [
            'width' => 100,
            'height' => 100,
            'operation' => 'resize-crop',
        ],
        'large' => [
            'width' => 200,
            'height' => 200,
            'operation' => 'resize-crop',
        ],
    ],

    'avatar' => [
        'small' => [
            'width' => 24,
            'height' => 24,
            'operation' => 'resize-crop',
        ],
        'medium' => [
            'width' => 200,
            'height' => 200,
            'operation' => 'resize-crop',
        ],
        'large' => [
            'width' => 400,
            'height' => 400,
            'operation' => 'resize-crop',
        ],
    ],
];
