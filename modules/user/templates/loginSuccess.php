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

// Forked from upstream to hide login fields outside login url
//
?>

<?php decorate_with('layout_1col') ?>
<?php use_helper('Javascript') ?>

<?php slot('content') ?>

  <div class="row">

    <div class="offset4 span4">

      <div id="content">

        <?php if ('user' != $sf_request->module || 'login' != $sf_request->action): ?>
          <h1><?php echo __('Please log back in') ?></h1>
        <?php else: ?>
          <h1><?php echo __('Log in') ?></h1>
        <?php endif; ?>

        <?php if ($form->hasErrors()): ?>
          <?php echo $form->renderGlobalErrors() ?>
        <?php endif; ?>

        <?php echo $form->renderFormTag(url_for(array('module' => 'user', 'action' => 'login'))) ?>

          <?php // show login fields only on login page ?>
          <?php if (!('user' != $sf_request->module || 'login' != $sf_request->action)): ?>
            <?php echo $form->renderHiddenFields() ?>
            <?php echo $form->email->renderRow(array('autofocus' => 'autofocus', 'class' => 'input-block-level')) ?>
            <?php echo $form->password->renderRow(array('class' => 'input-block-level', 'autocomplete' => 'off')) ?>
          <?php endif; ?>


          <section class="actions">
            <button type="submit" class="btn btn-primary btn-block btn-large"><?php echo _('Log back in') ?></button>
          </section>

        </form>

      </div>

    </div>

  </div>

<?php end_slot() ?>
