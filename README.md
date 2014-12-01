DARIAH Shibboleth authentication plugin for AtoM
===================

Install
-------------------
Clone the project to the plugin directory of you AtoM installation and activate it.

Protect the login page, i.e. `ATOM_ROOT/user/login` with Shibboleth.

In `apps/qubit/config/app.yml` add correct Shibboleth group mappings, e.g.
```
all:
  shibboleth_administrator_groups: 'shib-admins;shib-atom-admins'
  shibboleth_editor_groups: 'shib-editors'
  shibboleth_contributor_groups: 'shib-contributors;shib-atom-contributors'
  shibboleth_translator_groups: 'shib-translators'

```

Features
-------------------
- login via Shibboleth
- create AtoM account from Shibboleth data if it does not yet exist
- fall back to standard login
- add privileges based on Shibboleth groups

TODO
-------------------
- remove roles if not in Shibboleth data
- update user data on every login
- disable password handling
- defer username generation to CENDARI API
