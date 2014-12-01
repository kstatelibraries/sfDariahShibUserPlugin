i<?php

/*
 * This file is part of the Dariah Shibboleth auth plugin for AtoM.
 * It is adopted from upstream code and under AGPL accordingly.
 *
 * The original file is part of the Access to Memory (AtoM) software.
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

class sfDariahShibUserPluginConfiguration extends sfPluginConfiguration
{
  public static
    $summary = 'DARIAH Shibboleth Authentication',
    $version = '0.0.1';

  public function contextLoadFactories(sfEvent $event)
  {
    $context = $event->getSubject();
    $context->response->addStylesheet('/plugins/sfDariahShibUserPlugin/css/header.css', 'last', array('media' => 'screen'));
  }


  public function initialize()
  {
    $this->dispatcher->connect('context.load_factories', array($this, 'contextLoadFactories'));

    $enabledModules = sfConfig::get('sf_enabled_modules');
    $enabledModules[] = 'sfDariahShibUserPlugin';
    sfConfig::set('sf_enabled_modules', $enabledModules);

    $moduleDirs = sfConfig::get('sf_module_dirs');
    $moduleDirs[$this->rootDir.'/modules'] = false;
    sfConfig::set('sf_module_dirs', $moduleDirs);

    // use our login class
    sfConfig::set('sf_factory_user', 'sfDariahShibUser');
  }
}

