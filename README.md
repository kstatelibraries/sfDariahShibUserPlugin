DARIAH Shibboleth authentication plugin for AtoM
===================

This plugin enables Shibboleth authentication with AtoM.
Tested only with **AtoM 2.1**!

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
- fall back to standard login
- no password dialogs in user settings
- create AtoM account from Shibboleth data if it does not yet exist
- add and remove privileges based on Shibboleth groups

TODO
-------------------
- defer username generation to CENDARI API and update on each login


Acknoledgement
-------------------
The delopment of this plugin was made possbible by the help of
Jesús García Crespo, Artefactual Systems Inc.
on the AtoM mailing list
