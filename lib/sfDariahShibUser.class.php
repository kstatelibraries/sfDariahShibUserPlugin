<?php

/*
 * This file is part of the Dariah Shibboleth auth plugin for AtoM.
 * It is adopted from upstream code and under AGPL accordingly.
 *
 * The development of the plugin was made possible by the help of
 * Jesús García Crespo, Artefactual Systems Inc.
 * on the AtoM mailing list.
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
 * User Class handling authentication and user creation from Shibboleth data.
 *
 */
class sfDariahShibUser extends myUser implements Zend_Acl_Role_Interface
{

  /**
   * Performs the actual authentication, calling parent if web request's data is missing
   *
   * @param string $usermail the mail address of the user to authenticate (entered or from Shibboleth)
   * @param string $password the password entered into the login form, empty in case of Shibboleth
   * @param sfWebRequest $request the current web request
   *
   */
  public function authenticate($usermail,$password,$request=NULL)
  {
    $authenticated = false;

    // if Shibboleth Data is missing, hand back to default auth
    if (NULL === $request)
    {
      $authenticated = parent::authenticate($usermail, $password);
      // Load user
      $criteria = new Criteria;
      $criteria->add(QubitUser::EMAIL, $usermail);
      $user = QubitUser::getOne($criteria);
    }
    else
    {
      $params = $request->getPathInfoArray();
      if (strlen($params['Shib-Session-Index'])>=8) 
      {
        $authenticated = true;
        // Load user using username or, if one doesn't exist, create it
        $criteria = new Criteria;
        $criteria->add(QubitUser::EMAIL, $usermail);
        if (null === $user = QubitUser::getOne($criteria))
        {     
          $user = $this->createUserFromShibInfo($request);
        }
        $this -> updateUserFromShibInfo($request,$user);
      }
      else
      {
        return false;
      }
    }

    // Sign in user if authentication was successful
    if ($authenticated)
    {
      $this->signIn($user);
    }

    return $authenticated;
  }  

  /**
   * Creates a new AtoM user from Shibboleth data
   * and assignes a random password
   *
   * @param sfWebRequest $request the current web request
   *
   * @return QubitUser $user The newly created user.
   * 
   */
  protected function createUserFromShibInfo($request)
  {

    $params = $request->getPathInfoArray();
    $username = $this->generateUserNameFromShibInfo($request);
    $password = $this->generateRandomPassword();

    $user = new QubitUser();
    $user->username = $username;
    $user->email = $params[sfConfig::get('app_shibboleth_attribute_mail')];
    $user->save();
    $user->setPassword($password);

    return $user;
  }

  /**
   * Updates user's access privileges from Shibboleth data
   *
   * @param QubitUser $user the current user
   * @param sfWebRequest $request the current web request
   *
   */
  protected function updateUserFromShibInfo($request,$user)
  {

    $params = $request->getPathInfoArray();

    $isMemberOf = explode(";", $params['isMemberOf']);

    // read group mapping from config file
    $mapings = array(
      'ADMINISTRATOR_ID' => explode(';', sfConfig::get('app_shibboleth_administrator_groups')),
      'EDITOR_ID'        => explode(';', sfConfig::get('app_shibboleth_editor_groups')),
      'CONTRIBUTOR_ID'   => explode(';', sfConfig::get('app_shibboleth_contributor_groups')),
      'TRANSLATOR_ID'    => explode(';', sfConfig::get('app_shibboleth_translator_groups'))
    );

    // for each privilege class, check whether the current user should have it and assign it if not yet assigned
    foreach ($mapings as $key => $array) {
      if (0 < count(array_intersect($array,$isMemberOf))) {
        if (!($user->hasGroup(constant("QubitAclGroup::$key")))) {
          $aclUserGroup = new QubitAclUserGroup;
          $aclUserGroup->userId = $user->id;
          $aclUserGroup->groupId = constant("QubitAclGroup::$key");
          $aclUserGroup->save();
        }
      } else {
        // remove the user from groups he should not be in
        if ($user->hasGroup(constant("QubitAclGroup::$key"))) {
          foreach ($user->aclUserGroups as $membership) {
            if ($membership->groupId == constant("QubitAclGroup::$key")) {
              $membership->delete();
            }
          }
        }
      }
    }

    return true;
  }

  /**
   * Generate a username from the Shibboleth ePPN
   *
   * @param sfWebRequest $request the current web request
   * @return string $username the local part of the ePPN as username
   *
   */
  protected function generateUserNameFromShibInfo($request)
  {

    $params = $request->getPathInfoArray();
    // Warning: does not support federation!
    $usernameparts = explode("@", $params[sfConfig::get('app_shibboleth_attribute_eppn')]);
    $username = $usernameparts[0];

    return $username;
  }

  /**
   * Generates a random 25 character password.
   * An additonal prepended string ensures compliance with tightend AtoM security policy.
   *
   * @return string Random String to be used as password.
   *
   */
  protected function generateRandomPassword()
  {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_.,+=!@&#';
    $randomPass = 'Yz0';
    for ($i = 0; $i < 25; $i++) {
      $randomPass .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomPass;
  }

}
