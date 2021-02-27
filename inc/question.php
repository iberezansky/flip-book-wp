<?php
  namespace iberezansky\fb3d;
  use \DateTime;
  require_once(INC.'questions.php');

  function render_question() {
    global $fb3d;
    ?>
    <div class="fb3d-question notice notice-info is-dismissible">
      <div class="text">
        <?php echo($fb3d['question']['html']);?>
      </div>
      <hr />
      <p>
        <a href="#" class="button fb3d-q-remind-later">Remind later</a>&nbsp;
        <a href="#" class="button fb3d-q-ok">Ok</a>
      </p>
    </div>
    <script type="text/javascript">
      (function(jContainer) {
        var ajaxurl = '<?php echo(admin_url('admin-ajax.php'));?>', id = '<?php echo($fb3d['question']['id']);?>';
        var send = function(state) {
          jQuery.ajax({
            type: 'post',
            dataType: 'json',
            url: ajaxurl,
            data: {
             action: 'fb3d_receive_question_answer',
             question: {
               id: id,
               state: state
             }
           }
         });
        };
        var later = jContainer.find('.fb3d-q-remind-later'), ok = jContainer.find('.fb3d-q-ok'),
        handle = function(e, s) {
          e.preventDefault();
          send(s);
          jContainer.remove();
        };
        later.on('click', function(e) {
          handle(e, 1);
        });
        ok.on('click', function(e) {
          handle(e, 0);
        });
        <?php echo($fb3d['question']['js']);?>
      })(jQuery('.fb3d-question'));
    </script>
    <?php
  }

  function add_question() {
    global $typenow, $wpdb, $fb3d;
    if($typenow===POST_ID) {
      $aq = NULL;
      $qs = $fb3d['options']['questions'];
      foreach(get_questions() as $q) {
        if(!isset($qs[$q['id']]) ||
          $qs[$q['id']]['state']==1 &&
          (time()-DateTime::createFromFormat(DTM_FORMAT, $qs[$q['id']]['date'])->getTimestamp())/(24*60*60)>1
        ) {
          $aq = $q;
          break;
        }
      }
      if($aq) {
        $r = $wpdb->get_row("SELECT DATEDIFF(now(),post_date) as days FROM {$wpdb->posts} WHERE post_type = '".POST_ID."' ORDER BY post_date ASC LIMIT 0, 1");
        $r = $r? intval($r->days): 0;
        if($r>9) {
          $fb3d['question'] = $aq;
          add_action('admin_notices', '\iberezansky\fb3d\render_question');
        }
      }
    }
  }

  add_action('current_screen', '\iberezansky\fb3d\add_question');

?>
