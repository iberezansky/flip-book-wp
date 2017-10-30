<?php namespace iberezansky\fb3d;?>

<div id="fb3d-ctx" class="flip-book">
  <div class="view">
    <div class="fnav">
      <div class="prev">
        <a class="cmdBackward" href="#"><span class="icon"><i class="fa fa-angle-left" title="<?php _e('Previous page', POST_ID)?>"></i></span></a>
      </div>
      <div class="next">
        <a class="cmdForward" href="#"><span class="icon"><i class="fa fa-angle-right" title="<?php _e('Next page', POST_ID)?>"></i></span></a>
      </div>
    </div>
    <div class="widLoadingProgress loading-progress hidden">
      <div class="progress">
      </div>
      <div class="txtLoadingProgress caption">
      </div>
    </div>
  </div>

  <div class="widFloatWnd float-wnd hidden">
    <div class="header">
      <?php _e('Table of contents', POST_ID)?>
      <a href="#" title="<?php _e('Close', POST_ID)?>" class="close cmdCloseToc">
        <span class="icon"><i class="fa fa-times"></i></span>
      </a>
    </div>
    <div class="body">
      <div class="ctrl">
        <div class="toc">
          <div class="toc-menu widTocMenu">
            <ul>
              <li class="cmdBookmarks"><a href="#"><?php _e('Bookmarks', POST_ID)?></a></li>
              <li class="cmdThumbnails"><a href="#"><?php _e('Thumbnails', POST_ID)?></a></li>
              <li class="cmdSearch"><a href="#"><?php _e('Search', POST_ID)?></a></li>
            </ul>
          </div>
          <div class="widBookmarks toc-view">

          </div>
          <div class="widThumbnails toc-view">

          </div>
          <div class="widSearch toc-view">

          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="controls">

    <div class="ctrl">
      <nav class="fnavbar">
        <ul class="fnav">

          <li class="fnav-item cmdZoomIn"><a href="#"><span class="icon"><i class="fa fa-search-plus" title="<?php _e('Zoom in', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdZoomOut"><a href="#"><span class="icon"><i class="fa fa-search-minus" title="<?php _e('Zoom out', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdDefaultZoom"><a href="#"><span class="icon"><i class="fa fa-compress" title="<?php _e('Fit view', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdToc"><a href="#"><span class="icon"><i class="fa fa-bookmark" title="<?php _e('Table of contents', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdFastBackward"><a href="#"><span class="icon"><i class="fa fa-fast-backward" title="<?php _e('10 pages backward', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdBackward"><a href="#"><span class="icon"><i class="fa fa-backward" title="<?php _e('Previous page', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item">
            <div class="pages">
              <input type="text" class="number inpPage" maxlength="4" placeholder="1">
              <input type="text" class="amount inpPages" readOnly maxlength="4" placeholder="1">
            </div>
          </li>
          <li class="fnav-item cmdForward"><a href="#"><span class="icon"><i class="fa fa-forward" title="<?php _e('Next page', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdFastForward"><a href="#"><span class="icon"><i class="fa fa-fast-forward"  title="<?php _e('10 pages forward', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdSave"><a href="#"><span class="icon"><i class="fa fa-download"  title="<?php _e('Download', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdPrint"><a href="#"><span class="icon"><i class="fa fa-print"  title="<?php _e('Print', POST_ID)?>"></i></span></a></li>
          <li class="fnav-item cmdFullScreen"><a href="#"><span class="icon"><i class="fa fa-arrows-alt" title="<?php _e('Full screen', POST_ID)?>"></i></span></a></li>
          <li class="dropup fnav-item toggle widSettings">
            <a href="#"><div class="icon-caret"><span class="icon"><i class="fa fa-cog" title="<?php _e('Settings', POST_ID)?>"></i> <i class="caret"></i></span></div></a>
            <ul class="menu hidden">
              <li  class="cmdSmartPan"><a href="#"><span class="icon"><i class="fa fa-eye"></i></span> <?php _e('Smart pan', POST_ID)?></a></li>
              <li  class="cmdSinglePage"><a href="#"><span class="icon"><i class="fa fa-file-o"></i></span> <?php _e('Single page', POST_ID)?></a></li>
              <li  class="cmdSounds"><a href="#"><span class="icon"><i class="fa fa-volume-up"></i></span> <?php _e('Sounds', POST_ID)?></a></li>
              <li  class="cmdStats"><a href="#"><span class="icon"><i class="fa fa-line-chart"></i></span> <?php _e('Stats', POST_ID)?></a></li>
              <li class="divider"></li>
              <li  class="cmdLightingUp"><a href="#"><span class="icon"><i class="fa fa-chevron-up"></i></span> <?php _e('Increase lighting', POST_ID)?></a></li>
              <li  class="cmdLightingDown"><a href="#"><span class="icon"><i class="fa fa-chevron-down"></i></span> <?php _e('Reduce lighting', POST_ID)?></a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>

  </div>
</div>
