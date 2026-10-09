Configuration
=============

Repository plugins are disabled until an administrator enables them:

1. Go to *Site administration* > *Plugins* > *Repositories* > *Manage repositories*.
2. Set **EduPlay** to *Enabled and visible*.

The plugin has no settings of its own. The capability ``repository/eduplay:view`` is granted to authenticated users by default; adjust it in *Permissions* if you want to restrict the repository.

Searching depends on the **Query the EduPlay service** setting of ``local_eduplay`` (*Site administration* > *Plugins* > *Local plugins* > *EduPlay*), enabled by default. Disabled, no request is made to EduPlay and only pasted video links work.

Privacy
-------

The plugin stores no personal data. When searching, the **text typed in the search box** is sent by the server to the EduPlay service (``eduplay.rnp.br``); no user identifier is sent. Texts that are links other than a canonical EduPlay video link are never sent. This is declared in the Moodle Privacy API.
