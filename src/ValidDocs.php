<?php 

namespace Masterkey\ValidDocs;

/**
 * ValidDocs
 *
 * Realiza a validação de documentos brasileiros, como CPF, CNPJ e PIS
 *
 * @author  Matheus Lopes Santos <fale_com_lopez@hotmail.com>
 * @author  Anderson Souza <sakn45@bol.om.br>
 * @version 1.2.0
 * @since   11/07/2016
 * @package ValidDocs
 */
class ValidDocs
{
    /**
     * Realiza a validação de um cpf
     *
     * @author Anderson Souza <sakn45@bol.com.br>
     * Acrescentando as verificações se o parametro passado é de fato uma string e
     * se a mesma não está vazia.
     * Acrescentado lançamento de duas excessões.
     * Removendo a opcionalidade do parametro, já que a passagem do mesmo é obrigatório para
     * validação.
     * Removido o cast já que o retorno é misto e como foi passado uma string, em caso
     * de não ocorrer nenhum erro, uma string será retornada.
     * Removida a condicional que verificava se o cpf continha o tamanho exigido.
     * Acrescentada a verificação do tamanho total do cpf, na condicional que verifica
     * se o mesmo é uma string.
     * Mudança no nome de algumas variáveis, para melhor compreensão.
     * Removido o trecho condicional que verificava se todos os números eram iguais, pois,
     * o mesmo é desnecessário, já que a validação dos dois digitos verificadores, implica não
     * em os nove primeiros número sem os "corretos", já que um digito verificador sendo incorreto,
     * invalida todo o documento. Sendo assim, eu só preciso verificar se o CPF informado inicialmente,
     * é igual ao cpf gerado depois da verificação.
     * Removido a condicional if final, sendo substituida por uma operação ternária.
     * @access public
     * @static
     * @param   string $cpf  CPF a ser validado
     * @return  boolean
     * @throws \UnderflowException            Lançada caso nenhum valor seja informado.
     * @throws \InvalidArgumentException      Lançada caso um tipo string nao seja informado.
     * 
     */
    public static function validaCPF($cpf)
    {
        /*
         * Trecho inicial verifica se cpf passado é uma string e possui o total de 14
         * caracteres
         */
        if (\is_string($cpf) && \strlen($cpf) === 14) {
            if (!empty($cpf)) {
                /* 
                 * Garante que o cpf terá somente números.
                 * @var $cpf string 
                 */
                $cpf = preg_replace( '/[^0-9]+/', '', $cpf);
                
                /* @var $validator ValidationAlgorithms */
                $validator= new ValidationAlgorithms($cpf);
                
                /* 
                 * Futuramente, transferir a responsabilidade para a classe ValidationAlgorithms
                 * Remove os dois dígitos verificadores.
                 * @var $digitos string
                 */
                $digitos = substr($cpf, 0, 9);
                
                /* 
                 * Obtém o primeiro digito verificador do documento
                 * Não tenha medo de variáveis com nomes longos
                 * @var $cpfComPrimeiroDigito string
                 */
                $cpfComPrimeiroDigito= $validator->calcDigitPositions($digitos);
                
                /*
                 * Obtém o segundo digito do cpf, retornando assim o mesmo completo.
                 * @var $cpfCompleto string
                 */
                $cpfCompleto= $validator->calcDigitPositions($cpfComPrimeiroDigito, 11);
                
                return $validator->verifyEquality($cpfCompleto) ? true : false;                
                                
            } else {
                throw new \UnderflowException('Obrigatório informar um valor');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }          
    }

    /**
     * Realiza a validação de um CNPJ
     *
     * @author Anderson Souza <sakn45@bol.com.br>
     * Removido a opcionalidade do parametro, já que é obrigatório informar um, para posteriores
     * verificações.
     * Acrescantada as verificações para de fato saber se uma string está sendo passada,
     * e se a mesma não está vazia.
     * Acrescentada duas excessões.
     * Mudança no nome da variável $cnj_original, para simplesmente $cnpj.
     * Retirado o cast para string, já que o método preg_replace, retorna um valor misto, ou seja,
     * dependendo do tipo passado, o mesmo valor retornado, no nosso caso uma string.
     * Removida a condicional que verificava o tamanho máximo do cnpj, movendo para o trecho
     * da verificação condicional no início do método.
     * Alterado o nome da variável $primeiros_numeros_cnpj, para $digitos, assim mantém a
     * consistência.
     * Alterado o nome da variável de $primeiro_calculo, para $cnpjComPrimeiroDigito.
     * Alterado o nome da variável de $segundo_calculo, para o $cnpjCompleto.
     * @param   string  $cnpj   O CNPJ a ser validado.
     * Removida a concatenação, já que no segundo momento, onde há o cálculo do segundo
     * digito verificador, o cnpj já possui 11 números, e no final do processo, o método responsável
     * pelo cálculo já concatena internamente, devolvendo o cnpj com seus 12 números originais.
     * Removido o método antigo de verificação da igual.
     * Removida o trecho condicional que verificava a igual, já que o método já o faz.
     * Acrescentada uma operação ternária.
     * @access public
     * @return  boolean
     * @throws \UnderflowException             Lançada caso nenhum valor seja informado.
     * @throws \InvalidArgumentException       Lançada caso um tipo string não seja informado.
     */
    public static function validaCNPJ($cnpj)
    {
        /*
         * Trecho que além de verificar se o valor passado é uma string, verificar também
         * se o tamanho total do cpnj é 18.
         */
        if (\is_string($cnpj) && \strlen($cnpj) === 18) {
            if (!empty($cnpj)) {
                /* 
                 * Obtém somente os números
                 * @var $cnpj string
                 */
                $cnpj = preg_replace( '/[^0-9]+/', '', $cnpj);
                
                /* @var $validator ValidationAlgorithms */
                $validator= new ValidationAlgorithms($cnpj);
                
                /*
                 * Remove os dois digitos verificadores do cnpj
                 * @var $digitos string
                 */
                $digitos= substr($cnpj, 0, 12);
                
                /*
                 * Retorna o cnpj com o primeiro digito verificador.
                 * @var $cnpjComPrimeiroDigito string
                 */               
                $cnpjComPrimeiroDigito= $validator->calcDigitPositions($digitos, 5);
                
                /*
                 * Retorna o cnpj com o segundo digito, ou seja, o cnpj completo.
                 * @var $cnpjCompleto string
                 */
                $cnpjCompleto= $validator->calcDigitPositions($cnpjComPrimeiroDigito, 6);
                
                return $validator->verifyEquality($cnpjCompleto) ? true : false;
                
            } else {
                throw new \UnderflowException('Obrigatório informar um valor');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }        
    }

    /**
     * Realiza a Validação do número do PIS
     *
     * @author Anderson Souza <sakn45@bol.com.br>
     * Removido a opcionalidade do parametro, já que é obrigatório informar um, para posteriores
     * verificações.
     * Acrescantada as verificações para de fato saber se uma string está sendo passada,
     * e se a mesma não está vazia.
     * Acrescentada duas excessões.
     * Retirado o cast para string, já que o método preg_replace, retorna uma string.
     * Removida a condicional que verificava o tamanho máximo do PIS, movendo para o trecho
     * da verificação condicional no início do método.
     * Alterado o nome da variável de $validador, para $validator.
     * @access public
     * @param   string $pis
     * @return  boolean
     * @throws \UnderflowException           Lançada caso nenhum valor seja informado.
     * @throws \InvalidArgumentException     Lançada caso um tipo string não seja informado.
     */
    public static function validaPIS($pis)
    {
        /*
         * Trecho que além de verificar se o valor passado é uma string, verificar também
         * se o tamanho total do PIS é 14.
         */
        if (\is_string($pis) && \strlen($pis) === 14) {
            if (!empty($pis)) {
                /* @var $pis string */
                $pis = preg_replace( '/[^0-9]+/', '', $pis);
                
                /* @var $validator ValidationAlgorithms */
                $validator= new ValidationAlgorithms($pis);
                
                return $validator->checkPIS($pis);
                
            } else {
                throw new \UnderflowException('Obrigatório informar um valor');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }    
    }
}
