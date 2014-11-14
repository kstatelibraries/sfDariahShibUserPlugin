DARIAH Shibboleth authentication plugin for AtoM
===================

Install
-------------------
Clone the project to the plugin directory of you AtoM installation and activate it.

Modify `apps/qubit/config/factories.yml` to include
````
all:
  user:
    class: sfDariahShibUser
````

Features
-------------------
- login via Shibboleth
- create AtoM account from Shibboleth data if it does not yet exist
- fall back to standard login

TODO
-------------------
- assign roles/groups based on Shibboleth data
- simplify install: factory dependency
- update user data on every login
