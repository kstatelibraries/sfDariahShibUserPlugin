<?php

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

/**
 * Overwrites upstream class to inject Shibboleth handling 
 * and pass request down to authentication class.
 *
 */
class UserLoginAction extends sfAction
{
  /**
   * Renders the login dialog, 
   * calls the login action if Shibboleth data is present
   * or POST data is sent as a fall back,
   * redirects the user after successful authentication
   *
   * @param sfWebRequest $request The current web request.
   *
   * @return void
   */
  public function execute($request)
  {
    $this->form = new sfForm;
    $this->form->getValidatorSchema()->setOption('allow_extra_fields', true);

    // Redirect to @homepage if the user is already authenticated
    if ($this->context->user->isAuthenticated())
    {
      $this->redirect('@homepage');
    }

    // Redirect to the current URI in case we're forwarded to the login page
    $this->form->setDefault('next', $request->getUri());
    if ('user' == $request->module && 'login' == $request->action)
    {
      // Redirect to our referer otherwise
      $this->form->setDefault('next', $request->getReferer());
    }

    $apache_params = $request->getPathInfoArray();

    $this->form->setValidator('next', new sfValidatorString);
    $this->form->setWidget('next', new sfWidgetFormInputHidden);

    $this->form->setValidator('email', new sfValidatorEmail(array('required' => true), array(
      'required' => $this->context->i18n->__('You must enter your email address'),
      'invalid' => $this->context->i18n->__('This isn\'t a valid email address'))));
    $this->form->setWidget('email', new sfWidgetFormInput);

    $this->form->setValidator('password', new sfValidatorString(array('required' => true), array(
      'required' => $this->context->i18n->__('You must enter your password'))));
    $this->form->setWidget('password', new sfWidgetFormInputPassword);

    if (strlen($apache_params['Shib-Session-Index'])>=8) 
    {
      if ($this->context->user->authenticate($apache_params['mail'],'',$request))
      {
        if (null !== $next = $this->form->getValue('next'))
        {
          $this->redirect($next);
        }
        $this->redirect('@homepage');
      }
    }

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getPostParameters());

      if ($this->form->isValid())
      {
        if ($this->context->user->authenticate($this->form->getValue('email'), $this->form->getValue('password')))
        {
          if (null !== $next = $this->form->getValue('next'))
          {
            $this->redirect($next);
          }

          $this->redirect('@homepage');
        }

        $this->form->getErrorSchema()->addError(new sfValidatorError(new sfValidatorPass, 'Sorry, unrecognized email or password'));
      }
    }



  }

}
