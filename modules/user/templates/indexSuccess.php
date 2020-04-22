<?php
/*
 * This file is part of the Dariah Shibboleth auth plugin for AtoM.
 * It is adopted from upstream code and under AGPL accordingly.
 *
 * The original file is part of the Access to Memory (AtoM) software
 * and lives in apps/qubit/modules/user/templates/
 *
 * Access to Memory (AtoM) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Access to Memory (AtoM) is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Access to Memory (AtoM).  If not, see <http://www.gnu.org/licenses/>.
 */

// Forked from upstream to remove password handling
//
?>
<h1><?php echo __('User %1%', array('%1%' => render_title($resource))) ?></h1>

<?php echo get_component('user', 'aclMenu') ?>

<?php if (0 < $notesCount || !$resource->active): ?>
  <div class="messages error">
    <ul>
      <?php if (0 < $notesCount): ?>
        <li><?php echo __('This user has %1% notes in the system and therefore it cannot be removed', array('%1%' => $notesCount)) ?></li>
      <?php endif; ?>
      <?php if (!$resource->active): ?>
        <li><?php echo __('This user is inactive') ?></li>
      <?php endif; ?>
    </ul>
  </div>
<?php endif; ?>

<section id="content">

  <section id="userDetails">

    <?php echo link_to_if(QubitAcl::check($resource, 'update'), '<h2>'.__('User details').'</h2>', array($resource, 'module' => 'user', 'action' => 'edit')) ?>

    <?php echo render_show(__('User name'), $resource->username.($sf_user->user === $resource ? ' ('.__('you').')' : '')) ?>

    <?php echo render_show(__('Email'), $resource->email) ?>

    <?php if (0 < count($groups = $resource->getAclGroups())): ?>
      <div class="field">
        <h3><?php echo __('User groups') ?></h3>
        <div>
          <ul>
            <?php foreach ($groups as $item): ?>
              <?php if (100 <= $item->id): ?>
                <li><?php echo $item->__toString() ?></li>
              <?php else: ?>
                <li><span class="note2"><?php echo $item->__toString() ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <?php if (sfConfig::get('app_multi_repository')): ?>
      <?php if (0 < count($repositories = $resource->getRepositories())): ?>
        <div class="field">
          <h3><?php echo __('Repository affiliation') ?></h3>
          <div>
            <ul>
              <?php foreach ($repositories as $item): ?>
                <li><?php echo render_title($item) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($sf_context->getConfiguration()->isPluginEnabled('arRestApiPlugin')): ?>
      <div class="field">
        <h3><?php echo __('REST API key') ?></h3>
        <div>
          <?php if (isset($restApiKey)): ?>
            <code><?php echo $restApiKey ?></code>
          <?php else: ?>
            <?php echo __('Not generated yet.') ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($sf_context->getConfiguration()->isPluginEnabled('arOaiPlugin')): ?>
      <div class="field">
        <h3><?php echo __('OAI-PMH API key') ?></h3>
        <div>
          <?php if (isset($oaiApiKey)): ?>
            <code><?php echo $oaiApiKey ?></code>
          <?php else: ?>
            <?php echo __('Not generated yet.') ?>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

  </section>

</section>

<?php echo get_partial('showActions', array('resource' => $resource)) ?>
