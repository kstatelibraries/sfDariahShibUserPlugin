<?php if (!$sf_user->isAuthenticated()): ?>

  <div id="user-menu">

    <a class="top-item top-loginlink" href="<?php echo url_for(array('module' => 'user', 'action' => 'login')) ?>" ><?php echo __('Log in') ?></a>

  </div>

<?php else: ?>

  <div id="user-menu">

    <a class="top-item top-dropdown" data-toggle="dropdown" data-target="#" >
      <?php echo $sf_user->user->username ?>
    </a>

    <div class="top-dropdown-container">

      <div class="top-dropdown-arrow">
        <div class="arrow"></div>
      </div>

      <div class="top-dropdown-header">
        <?php echo image_tag($gravatar) ?>&nbsp;
        <?php echo __('Hi, %1%', array('%1%' => $sf_user->user->username)) ?>
      </div>

      <div class="top-dropdown-body">

        <ul>
          <li><?php echo link_to(__('Profile'), array($sf_user->user, 'module' => 'user')) ?></li>
          <li><?php echo link_to(__('Log out'), array('module' => 'user', 'action' => 'logout')) ?></li>
        </ul>

      </div>

      <div class="top-dropdown-bottom"></div>

    </div>

  </div>

<?php endif; ?>

