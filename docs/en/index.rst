moodle-repository_eduplay
=========================

**moodle-repository_eduplay** is a Moodle file picker repository for EduPlay, in the style of the Wikimedia repository: the author **searches EduPlay videos by title**, sees them with their thumbnail and picks one. The video is inserted as an **external link** (never a copy of the media), which `moodle-media_eduplay <https://eduplay-moodle-suite.github.io/moodle-media_eduplay/>`_ then shows as the official player. Pasting a full video link in the search box also works. It is part of the unofficial **EduPlay Moodle Suite** and depends on `moodle-local_eduplay <https://eduplay-moodle-suite.github.io/moodle-local_eduplay/>`_, which queries the public EduPlay API.

Versão em português: `Português (Brasil) <../pt-br/index.html>`_.

.. warning::

   This project is unofficial. It has no affiliation with, or endorsement by, RNP, EduPlay or Moodle HQ. The EduPlay API it uses is public but **not documented**, so it may change without notice.

.. toctree::
   :maxdepth: 2
   :caption: Contents

   installation
   configuration
   usage

Main features
-------------

* **Search by title**, 10 results per page, with title and thumbnail.
* **Only public videos**: active videos that are public and do not require authentication.
* **Link only**: the repository returns the canonical link (``FILE_EXTERNAL``); no media is copied to Moodle.
* **Pasted links** are still accepted and now show the real title and thumbnail.
* **Can be switched off**: the *Query the EduPlay service* setting of ``local_eduplay`` turns every request to EduPlay off; then only pasted links work.
* **Moodle 4.5 LTS and 5.3 LTS**.
