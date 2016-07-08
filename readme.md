Masterkey ValidDocs
-------------------
[![Build Status](https://travis-ci.org/MasterkeyInformatica/ValidDocs.svg?branch=master)](https://travis-ci.org/MasterkeyInformatica/ValidDocs)

A biblioteca provê uma forma simples para validação de documentos brasileiros, como CPF e CNPJ, tornando o seu sistema mais robusto na validação destes dados.

Utilizando a Ferramenta
-----------------------

para utilização, baixe o pacote via zip, ou adicione-a em suas dependências do composer:

```sh
composer require masterkey/valid-docs
```

Agora, basta adicionar o autoloader e incluir a classe. A utilização também é muito simples:

```php
<?php
    require_once "vendor/autoload.php";

    use Masterkey\ValidDocs\ValidDocs;

    // Validando um CPF
    if(ValidDocs::validaCPF('111.111.111-11')) {
        echo "O CPF informádo é inválido";
    }

    // Validando um CNPJ
    if(ValidDocs::validaCNPJ('11.111.111/1111-11')) {
        echo "O CNPJ informado é inválido";
    }
```

De acordo a demanda, outros documentos também serão acrescentados, como o PIS.

Contribuições serão muito bem vindas.
