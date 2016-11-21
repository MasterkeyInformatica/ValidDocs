<?php

    use Masterkey\ValidDocs\ValidDocs;

    class ValidDocsTest extends PHPUnit_Framework_TestCase
    {
        public function testReturnValidaCpf()
        {
            $this->assertTrue(ValidDocs::validaCPF('123.456.789-09'));
            $this->assertFalse(ValidDocs::validaCPF('111.111.111-11'));
            $this->assertFalse(ValidDocs::validaCPF());
            $this->assertFalse(ValidDocs::validaCPF('111.11'));
        }

        public function testReturnValidaCnpj()
        {
            $this->assertTrue(ValidDocs::validaCNPJ('22.686.661/0001-55'));
            $this->assertFalse(ValidDocs::validaCNPJ('11.111.111/1111-11'));
            $this->assertFalse(ValidDocs::validaCNPJ());
            $this->assertFalse(ValidDocs::validaCNPJ('11.111.'));
        }

        public function testReturnValidaPis()
        {
            $this->assertFalse(ValidDocs::validaPIS('111.11111.11/1'));
            $this->assertFalse(ValidDocs::validaPIS());
            $this->assertFalse(ValidDocs::validaPIS('111.1115AS'));

            // NÚMEROS DE PIS GERADOS EM
            // WWW.4DEVS.COM.BR/gerador_de_pis_pasep
            $this->assertTrue(ValidDocs::validaPIS('400.66943.59/0'));
            $this->assertTrue(ValidDocs::validaPIS('519.16918.07/0'));
            $this->assertTrue(ValidDocs::validaPIS('356.44860.03/3'));
        }
    }
