<?php

/**
 * Custom session storage class for DARIAH AtoM Shibboleth login.
 */
class sfDariahShibSessionStorage extends QubitSessionStorage
{
    /**
     * The sole purpose of this overridden `regenerate` method
     * is to fix an issue that was introduced in AtoM 2.3. To whit:
     * - Shiboleth login happens via GET, after Apache successfully
     *   authenticates a user and provides their credentials in the
     *   request environment
     * - AtoM 2.3 added an override to sfSessionStorage in the following
     *   change: https://goo.gl/s08atR
     *   which destroyed the session on GET requests after the Shibboleth
     *   user had been authenticated, thus effectively logging them out
     * - this over-overridden method undoes that change, calling the
     *   original 'regenerate' method
     *
     * FIXME: This is fragile and should ultimately be done in a better manner
     *
     * @param bool|false $destroy
     */
    public function regenerate($destroy = false)
    {
        $gpMethod = new ReflectionMethod(
            get_parent_class(get_parent_class($this)), 'regenerate');
        $gpMethod->invoke($this, $destroy);
    }
}