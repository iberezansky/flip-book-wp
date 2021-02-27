<?php
  namespace iberezansky\fb3d;

  function get_questions() {
    $qs = [];
    $push = function($id, $html='', $js='') use(&$qs) {
      array_push($qs, ['id'=> $id, 'html'=> $html, 'js'=> $js]);
    };

    ob_start();
    ?>
    <p style="text-align:center;">
      <b>Thank You for using 3D FlipBook!</b>
    </p>
    <p>
      <img src="<?php echo(ASSETS_IMAGES.'please.png'); ?>" alt="Please" style="width: 100px; float: left; padding-right: 10px;">
      Everyday we work hard on improving our project quality. We answer your questions, fix bugs, develop new features. We want to do even more, but we need your help.
      If You want You can buy <a href="http://3dflipbook.net/download-wp" target="_blank">pro version</a>, if You don't - no problem, we respect any your decision.
      We just ask You to write <a href="https://bit.ly/3fOzyoQ" target="_blank">a review</a>.
      This helps us better understand your experience and what we should do next.
      Also it helps other users to use our plugin with the same assurance that You have.
      <b>All your feedbacks are very important for us - only together we can get the best product!</b>
      <div style="clear: left;"></div>
    </p>
    <p style="text-align:right;">
      3D FlipBook Team
    </p>
    <?php
    $html = ob_get_clean();
    ob_start();
    ?>
    ok.on('click', function() {
      window.open('https://bit.ly/3fOzyoQ');
    });
    <?php
    $push('review-reminder2', $html, ob_get_clean());

    return $qs;
  }

?>
