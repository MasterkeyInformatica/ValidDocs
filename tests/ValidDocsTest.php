<?php

    use Masterkey\ValidDocs\ValidDocs;

    class ValidDocsTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @expectedException InvalidArgumentException
         * @expectedExceptionMessage Para validação é necessário um CPF válido
         */
        public function testExceptionValidaCPF()
        {
            ValidDocs::ValidaCPF();
            ValidDocs::ValidaCPF('123.45');
        }

        public function testReturnValidaCpf()
        {
            $this->assertTrue(ValidDocs::validaCPF('123.456.789-09'));
            $this->assertFalse(ValidDocs::validaCPF('111.111.111-11'));
        }

        /**
         * @expectedException InvalidArgumentException
         * @expectedExceptionMessage Para validação é necessário um CNPJ válido
         */
        public function testExceptionValidaCNPJ()
        {
            ValidDocs::ValidaCNPJ();
            ValidDocs::ValidaCNPJ('12.235');
        }

        public function testReturnValidaCnpj()
        {
            $this->assertTrue(ValidDocs::validaCNPJ('22.686.661/0001-55'));
            $this->assertFalse(ValidDocs::validaCNPJ('11.111.111/1111-11'));
        }
    }
