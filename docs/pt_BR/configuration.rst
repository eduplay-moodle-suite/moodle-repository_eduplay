Configuração
============

Plugins de repositório ficam desabilitados até que um administrador os habilite:

1. Acesse *Administração do site* > *Plugins* > *Repositórios* > *Gerenciar repositórios*.
2. Defina o **EduPlay** como *Habilitado e visível*.

O plugin não tem configurações próprias. A capacidade ``repository/eduplay:view`` é concedida a usuários autenticados por padrão; ajuste em *Permissões* se quiser restringir o repositório.

A busca depende da configuração **Consultar o serviço EduPlay** do ``local_eduplay`` (*Administração do site* > *Plugins* > *Plugins locais* > *EduPlay*), habilitada por padrão. Desabilitada, nenhuma requisição é feita ao EduPlay e só funcionam links de vídeo colados.

Privacidade
-----------

O plugin não armazena dados pessoais. Na busca, o **texto digitado na caixa de busca** é enviado pelo servidor ao serviço EduPlay (``eduplay.rnp.br``); nenhum identificador do usuário é enviado. Textos que sejam links diferentes de um link canônico de vídeo do EduPlay nunca são enviados. Isso está declarado na Privacy API do Moodle.
