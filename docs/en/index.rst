moodle-repository_eduplay
=========================

**moodle-repository_eduplay** is a proof of concept of a Moodle file picker repository for EduPlay. The author pastes an EduPlay video link in the repository search box and the video is returned as an **external link** (never a copy of the media), which `moodle-media_eduplay <https://eduplay-moodle-suite.github.io/moodle-media_eduplay/>`_ then shows as the official player. It is part of the unofficial **EduPlay Moodle Suite** and depends on `moodle-local_eduplay <https://eduplay-moodle-suite.github.io/moodle-local_eduplay/>`_.

Versão em português: `Português (Brasil) <../pt-br/index.html>`_.

.. warning::

   This project is unofficial and experimental. It has no affiliation with, or endorsement by, RNP, EduPlay or Moodle HQ.

.. toctree::
   :maxdepth: 2
   :caption: Contents

   installation
   configuration
   usage

Scope of the proof of concept
-----------------------------

* **No catalogue browsing**: EduPlay has no officially confirmed listing or search API, so the repository cannot list videos. Browsing and keyword search depend on an official API and authorization from RNP.
* **Link only**: the repository returns the canonical link (``FILE_EXTERNAL``); no media is copied to Moodle.
* **Validated**: only links accepted by ``local_eduplay`` are returned.
* **Moodle 4.5 LTS and 5.3 LTS**.
