moodle-repository_eduplay
=========================

O **moodle-repository_eduplay** é um repositório do seletor de arquivos do Moodle para o EduPlay, no estilo do repositório Wikimedia: o autor **pesquisa vídeos do EduPlay pelo título**, vê os resultados com a miniatura e escolhe um. O vídeo é inserido como **link externo** (nunca uma cópia da mídia), que o `moodle-media_eduplay <https://eduplay-moodle-suite.github.io/moodle-media_eduplay/>`_ exibe como o player oficial. Colar o link completo do vídeo na busca também funciona. Faz parte da **EduPlay Moodle Suite** (não oficial) e depende do `moodle-local_eduplay <https://eduplay-moodle-suite.github.io/moodle-local_eduplay/>`_, que consulta a API pública do EduPlay.

English version: `English <../en/index.html>`_.

.. warning::

   Este projeto não é oficial. Não possui afiliação, endosso ou representação da RNP, do EduPlay ou do Moodle HQ. A API do EduPlay que ele usa é pública, mas **não é documentada**, então pode mudar sem aviso.

.. toctree::
   :maxdepth: 2
   :caption: Conteúdo

   installation
   configuration
   usage

Principais recursos
-------------------

* **Busca por título**, 10 resultados por página, com título e miniatura.
* **Somente vídeos públicos**: vídeos ativos, públicos e que não exigem autenticação.
* **Somente link**: o repositório devolve o link canônico (``FILE_EXTERNAL``); nenhuma mídia é copiada para o Moodle.
* **Links colados** continuam aceitos e agora mostram o título e a miniatura reais.
* **Pode ser desligado**: a configuração *Consultar o serviço EduPlay* do ``local_eduplay`` desliga toda requisição ao EduPlay; então só funcionam links colados.
* **Moodle 4.5 LTS e 5.3 LTS**.
