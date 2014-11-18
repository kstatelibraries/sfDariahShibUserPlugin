DARIAH Shibboleth authentication plugin for AtoM
===================

Install
-------------------
Clone the project to the plugin directory of you AtoM installation and activate it.

Protect the login page, i.e. `ATOM_ROOT/user/login` with Shibboleth.


Features
-------------------
- login via Shibboleth
- create AtoM account from Shibboleth data if it does not yet exist
- fall back to standard login

TODO
-------------------
- assign roles/groups based on Shibboleth data
- update user data on every login
- disable password handling

