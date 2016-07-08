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
    }
