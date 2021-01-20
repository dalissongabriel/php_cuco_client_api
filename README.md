<p align="center">
   <img src="./docs/clients.png" width="200"/>
</p>

# CUCO Health | Client API





[![Author](https://img.shields.io/badge/Author-Alisson%20Gabriel-black)](https://github.com/LauraBeatris)


> Cadastre seus clientes e os gerencie-os através de uma interace simples e amigável.

<br />


# Funcionalidades

* Crie seus clientes, registrando seu E-mail, CPF e Telefone
* Explore os dados registrados por meio de filtros e ordenações
* Obtenha seus clientes no formato mais famoso para integrações: JSON


# Requirimentos
 - Instale o [PHP 8](https://www.php.net/downloads)
 - Instale o [Postgres 13](https://www.postgresql.org/download/)
 - Instale o [Composer 2](https://getcomposer.org/download/)
 - Instale o [Git](https://git-scm.com/downloads/)

# Instalação

Clone o repositório

``` git clone git@github.com:dalissongabriel/test_php_backend_cuco.git ```

Baixe as dependências com o composer:

``` composer install ```

Crie suas variáveis de ambiente baseado no exemplo: ```.env.example```

```cp .env.example .env```

``` DATABASE_URL="postgresql://SEU_USUARIO:SUA_SENHA@HOST_DO_BANCO:PORTA_DO_BANCO/NOME_DO_BANCO?serverVersion=13&charset=utf8" ```

Crie o banco de dados

```php bin/console doctrine:database:create```

```php bin/console doctrine:migrations:migrate```

Popule o banco com as seeds

``` php bin/console doctrine:fixtures:load ```

Suba o servidor para rodar a aplicação: 

```php -S localhost:8081 -t public/```


# Testes

Execute o comando para criar a base de dados teste em SQLite 3:

``` php bin/console -e test doctrine:schema:create ```


Em seguida, rode testes feitos em PHPUnit

``` php bin/phpunit ```


# Histórias de usuário

- [X] Usuário deve poder criar um cliente informando: nome, e-mail, cpf. Opcionalmente, pode-se informar também o telefone.

- [X] Usuário deve poder deletar um cliente

- [X] Usuário deve poder editar um cliente

- [X] Usuário deve poder buscar um cliente pelo nome e/ou CPF

- [X] Todos os retornos da API devem ser em JSON, incluindo os erros tratados pela aplicação

*BONUS*

- [X] Cliente deverá se autenticar via token JWT para ter acesso as rotas

- [X] Cliente poderá filtrar os registros por email

- [X] Cliente deverá receber respostas com informações extras sobre os recursos consultados

- [X] Cliente poderá escolher a ordenação de sua busca

- [X] Cliente deverá receber respostas páginadas, e poderá escolher qual página gostaria de receber, assim como a quantidade de registros por página

- [X] Os campos CPF,Teleone e E-mail devem ser válidados para garantir a integridade do domínio


## Documentação

Está é um API RESTful com um contexto bem enxuto, todavia, para a construção da mesma, utilizei boas práticas de programação e teste unitários para entregar um ferramenta de fácil manutenção e evolução. Para facilitar ainda mais a utilização da mesma, disponibilizo abaixo uma relação completa de uso das rotas, e também uma [coleção do postmam](./docs/Cuco%20Clientes%20Collection.postman_collection.json). Faça bom uso :)

---

Documentação completa para consumo da API:


1. Adicionar clientes

    Esta rota insere um novo registro na tabela client.
    - Request
        ```bash
        POST http://localhost:8081/clientes 
        ```
    - Body
        ```json
        {
            "name": "Otavio Mota",
            "email": "otaviomota2020@gmail.com",
            "cpf": "123.456.789.9"
        }
        ```
         ```json
        {
            "name": "Otavio Mota",
            "email": "otaviomota2020@gmail.com",
            "cpf": "123.456.789.9",
            "phone": "(40) 404044040"
        }
        ```
    - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `201 (Criado)` | `Cliente cadastrado ` 
        `400 (Requisição inválida)` | `A requisição realizada contém problemas de má formação`
        `401 (Não autorizado)` | `Na requisição, não foi informado o cabeçalho de autorização`
        `412 (Pré-condição falhou)` | `Os valores informados não são    válidos.`

---
Documentação completa do banco de dados



| ##client</a>|

| id |
| name |
| cpf |
| email |


phone
---
Projeto concluído em **2021**

Feito com carinho por  [Alisson Gabriel](https://github.com/dalissongabriel)
