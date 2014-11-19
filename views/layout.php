<!doctype html PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
<html lang='en'>
<!--open head-->
  <head>
  <?= $this->m_head->Deliver() ?>
  </head>
<!--close head-->
<!--open body-->
<body>

<div id='wrap'>
  <?php if(count($this->m_alerts) > 0): ?>
    <div class="global-alerts">
      <?php foreach ($this->m_alerts as $key => $value): ?>
        <div class="global-alert-priority-<?= $value->m_priority ?>">
          <?= $value->m_message ?>
        </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
  <div class='container'>
    <?= $this->m_nav->Deliver() ?>
    <div class='tab-content'>
        <div class='tab-pane active' id='problems'>
        <?= $this->m_content->Deliver() ?>
        </div>
    </div>
  </div>
  <div id='push'>
  </div>
</div>

<div id='footer'>
    <div class='container'>
    <p class='muted credit'>
      Development of this site was sponsored by the <a href='http://www.provost.umich.edu' target='_blank'>UM Office of the Provost</a> through the Gilbert Whitaker Fund for the Improvement of Teaching.
    </p>
    <p class='muted credit'>
      Please send any feedback to <a href='mailto:physics.sso@umich.edu'>physics.sso@umich.edu</a><br/>
      For issues with the content of the problems, see your instructor first.
    </p>
    </div>
</div>

</body>
<!--close body-->
</html>