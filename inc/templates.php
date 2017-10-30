<?php
  namespace iberezansky\fb3d;

  $fb3d['defaultTemplateUrl'] = admin_url('admin-ajax.php').'?action=fb3d_send_template_html&template='.urlencode(ASSETS_TEMPLATES.'default-book-view.php');
  $fb3d['templates'] = [
    'short-white-book-view'=> [
      'styles'=> [
        ASSETS_CSS.'short-white-book-view.css'
      ],
      'links'=> [
        [
          'rel'=> 'stylesheet',
          'href'=> ASSETS_CSS.'font-awesome.min.css'
        ]
      ],
      'html'=> $fb3d['defaultTemplateUrl'],
      'script'=> ASSETS_JS.'default-book-view.js',
      'sounds'=> [
        'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
        'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
      ]
    ],
    'white-book-view'=> [
      'styles'=> [
        ASSETS_CSS.'white-book-view.css'
      ],
      'links'=> [
        [
          'rel'=> 'stylesheet',
          'href'=> ASSETS_CSS.'font-awesome.min.css'
        ]
      ],
      'html'=> $fb3d['defaultTemplateUrl'],
      'script'=> ASSETS_JS.'default-book-view.js',
      'sounds'=> [
        'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
        'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
      ]
    ],
    'short-black-book-view'=> [
      'styles'=> [
        ASSETS_CSS.'short-black-book-view.css'
      ],
      'links'=> [
        [
          'rel'=> 'stylesheet',
          'href'=> ASSETS_CSS.'font-awesome.min.css'
        ]
      ],
      'html'=> $fb3d['defaultTemplateUrl'],
      'script'=> ASSETS_JS.'default-book-view.js',
      'sounds'=> [
        'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
        'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
      ]
    ],
    'black-book-view'=> [
      'styles'=> [
        ASSETS_CSS.'black-book-view.css'
      ],
      'links'=> [
        [
          'rel'=> 'stylesheet',
          'href'=> ASSETS_CSS.'font-awesome.min.css'
        ]
      ],
      'html'=> $fb3d['defaultTemplateUrl'],
      'script'=> ASSETS_JS.'default-book-view.js',
      'sounds'=> [
        'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
        'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
      ]
    ]
  ];

  function import_templates($skins) {
    global $fb3d;
    $fb3d['templates'] = $skins[POST_ID]['skins'];
  }

  add_action('iberezansky_skinedit_export_skins', '\iberezansky\fb3d\import_templates');

  function export_templates($skins) {
    global $fb3d;
    $skins[POST_ID] = [
      'skins'=> $fb3d['templates'],
      'version'=> SKINVERSION,
      'name'=> __('3D FlipBook'),
      'description'=> [
        'caption'=> [
          'type'=> 'text',
          'required'=> false
        ],
        'styles'=> [
          'type'=> 'file',
          'ext'=> 'css',
          'number'=> 'inf',
          'required'=> false
        ],
        'links'=> [
          'type'=> 'links',
          'number'=> 'inf',
          'required'=> false
        ],
        'html'=> [
          'type'=> 'file',
          'ext'=> 'php',
          'number'=> 'one',
          'required'=> true
        ],
        'script'=> [
          'type'=> 'file',
          'ext'=> 'js',
          'number'=> 'one',
          'required'=> false
        ],
        'sounds'=> [
          'type'=> 'file',
          'ext'=> 'mp3',
          'number'=> [
            'startFlip',
            'endFlip'
          ],
          'required'=> false
        ]
      ]
    ];
    return $skins;
  }

  add_filter('iberezansky_skinedit_import_skins', '\iberezansky\fb3d\export_templates');

  function template_url_to_path($url) {
    $url = str_replace ('\\', '/', $url);
    $dir = str_replace ('\\', '/', DIR);
    $pattern = '/wp-content/plugins/';
    return substr($dir, 0, strpos($dir, $pattern)).substr($url, strpos($url, $pattern));
  }

?>
