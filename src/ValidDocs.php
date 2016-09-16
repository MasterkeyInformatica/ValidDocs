<?php namespace Masterkey\ValidDocs;

    use InvalidArgumentException;

    /**
     * ValidDocs
     *
     * Realiza a validação de documentos brasileiros, como CPF e CNPJ
     *
     * @author  Matheus Lopes Santos <fale_com_lopez@hotmail.com>
     * @version 1.2.0
     * @since   11/07/2016
     */
    class ValidDocs
    {
        /**
         * Realiza a validação de um cpf
         *
         * @param   string $cpf  CPF a ser validado
         * @return  bool
         */
        public static function validaCPF($cpf = '')
        {
            // Garante que o cpf terá somente números
            $cpf = (string) preg_replace( '/[^0-9]/', '', $cpf);

            if(!$cpf or strlen($cpf) != 11) {
                return false;
            }

            // Inicia a classe de validação
            $validador = new ValidationAlgorithms($cpf);

            // Captura os 9 primeiros dígitos do CPF
    		$digitos = substr($cpf, 0, 9);

    		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    		$novo_cpf = $validador->calcDigitPositions($digitos);

    		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
    		$novo_cpf = $validador->calcDigitPositions($novo_cpf, 11);

            // Verifica se todos os números são iguais
            if ($validador->verifyEquality()) {
                return false;
            }

    		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
    		if ($novo_cpf === $cpf) {
    			return true;
    		}

            // CPF inválido
            return false;
        }

        /**
         * Realiza a validação de um CNPJ
         *
         * @param   string  $cnpj   O CNPJ a ser validado
         * @return  bool
         */
        public static function validaCNPJ($cnpj = '')
        {
            // Garante que o cnpj possuirá somente números
    		$cnpj_original = $cnpj = (string) preg_replace( '/[^0-9]/', '', $cnpj);

            if(!$cnpj || strlen($cnpj) != 14) {
                return false;
            }

            // Inicializa o validador
            $validador = new ValidationAlgorithms($cnpj_original);

    		// Captura os primeiros 12 números do CNPJ
    		$primeiros_numeros_cnpj = substr($cnpj_original, 0, 12);

    		// Faz o primeiro cálculo
    		$primeiro_calculo = $validador->calcDigitPositions($primeiros_numeros_cnpj, 5);

    		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
    		$segundo_calculo = $validador->calcDigitPositions($primeiro_calculo, 6);

    		// Concatena o segundo dígito ao CNPJ
    		$cnpj = $segundo_calculo;

            // Verifica se todos os números são iguais
            if ($validador->verifyEquality()) {
                return false;
            }

    		// Verifica se o CNPJ gerado é idêntico ao enviado
    		if ( $cnpj === $cnpj_original ) {
    			return true;
    		}

            // CNPJ inválido
            return false;
        }

        /**
         * Realiza a Validação do número do PIS iunformado
         *
         * @param   string $pis
         * @return  bool
         */
        public static function validaPIS($pis = '')
        {
            $pis = (string) preg_replace( '/[^0-9]/', '', $pis);

            if(!$pis || strlen($pis) != 11) {
                return false;
            }

            $validador = new ValidationAlgorithms($pis);
            return $validador->checkPIS($pis);
        }
    }
