Installation
============

Requirements
------------

* Moodle 4.5 LTS or 5.3 LTS.
* The `local_eduplay <https://github.com/eduplay-moodle-suite/moodle-local_eduplay>`_ plugin, installed first. To display the links as a player, also install `media_eduplay <https://github.com/eduplay-moodle-suite/moodle-media_eduplay>`_.

Steps
-----

1. Install ``local_eduplay`` into ``local/eduplay``.
2. Install this plugin into ``repository/eduplay`` (ZIP upload with top-level folder ``eduplay``, or Git):

   .. code-block:: bash

      git clone https://github.com/eduplay-moodle-suite/moodle-repository_eduplay.git repository/eduplay

3. Open *Site administration* > *Notifications* to finish the installation. On Moodle 5.1 and later, use ``public/`` as the base directory.
