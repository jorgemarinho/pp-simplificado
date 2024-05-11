# Pp Simplificado



## Instruções

Baixa o repositorio

```
git clone https://github.com/jorgemarinho/pp-simplificado.git
```

Subir o serviço docker, caso não esteja iniciado

```
service docker start
```


Primeira vez, fazer um build

```
docker-compose up --build 
```

Outras vezes
```
docker-compose up -d
```

Serviço disponivel na porta

```
http://localhost:8000
```

Executando teste
```
composer test
```

Test coverage %
```
composer test:coverage

```

## Arquitetura Clean Architecture ##

![Clean Architecture](CleanArchitecture.jpg)


**Core da aplicação está na pasta src**

![Src/Core](src_core.jpg)


**Modelagem de dados**

![mer](mer.png)


## Requisitos ##

A seguir estão algumas regras de negócio que são importantes para o funcionamento do PP Simplificado:

   * Para ambos tipos de usuário, precisamos do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema. Sendo assim, seu sistema deve permitir apenas um cadastro com o mesmo CPF ou endereço de e-mail;

   * Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários;

   * Lojistas só recebem transferências, não enviam dinheiro para ninguém;

   * Validar se o usuário tem saldo antes da transferência;

   * Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo, use este mock para simular (https://run.mocky.io/v3/f3737791-a6d0-4cbd-acfd-c0934024d5c0);

   * A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia;

   * No recebimento de pagamento, o usuário ou lojista precisa receber notificação (envio de email, sms) enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável. Use este mock para simular o envio (https://run.mocky.io/v3/5376d9af-6c62-4396-be9a-9c539dd0539d);

   * Este serviço deve ser RESTFul.

