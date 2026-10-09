moodle-repository_eduplay
=========================

O **moodle-repository_eduplay** é uma prova de conceito de repositório do seletor de arquivos do Moodle para o EduPlay. O autor cola o link de um vídeo do EduPlay na busca do repositório e o vídeo é devolvido como **link externo** (nunca uma cópia da mídia), que o `moodle-media_eduplay <https://eduplay-moodle-suite.github.io/moodle-media_eduplay/>`_ exibe como o player oficial. Faz parte da **EduPlay Moodle Suite** (não oficial) e depende do `moodle-local_eduplay <https://eduplay-moodle-suite.github.io/moodle-local_eduplay/>`_.

English version: `English <../en/index.html>`_.

.. warning::

   Este projeto é não oficial e experimental. Não possui afiliação, endosso ou representação da RNP, do EduPlay ou do Moodle HQ.

.. toctree::
   :maxdepth: 2
   :caption: Conteúdo

   installation
   configuration
   usage

Escopo da prova de conceito
---------------------------

* **Sem navegação no catálogo**: o EduPlay não tem API oficial de listagem ou busca confirmada, então o repositório não consegue listar vídeos. Navegação e busca por palavra-chave dependem de API oficial e de autorização da RNP.
* **Somente link**: o repositório devolve o link canônico (``FILE_EXTERNAL``); nenhuma mídia é copiada para o Moodle.
* **Validado**: só links aceitos pelo ``local_eduplay`` são devolvidos.
* **Moodle 4.5 LTS e 5.3 LTS**.
