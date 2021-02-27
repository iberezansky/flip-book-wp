<?php
  namespace iberezansky\fb3d;

  $fb3d['templates'] = [
    'short-white-book-view'=> [
      'styles'=> [
        ASSETS_CSS.'font-awesome.min.css',
        ASSETS_CSS.'short-white-book-view.css'
      ],
      'links'=> [],
      'html'=> ASSETS_TEMPLATES.'default-book-view.html',
      'script'=> ASSETS_JS.'default-book-view.js',
      'sounds'=> [
        'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
        'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
      ]
    ]
  ];
  $fb3d['lightboxes'] = [
    'light' => [
      'caption'=> 'Light Glass Box'
    ],
    'dark' => [
      'caption'=> 'Dark Glass Box'
    ],
    'dark-shadow' => [
      'caption'=> 'Dark Glass Shadow'
    ],
  ];

?>
