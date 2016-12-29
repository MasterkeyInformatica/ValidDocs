<?php 

namespace Masterkey\ValidDocs;

/**
 * ValidationAlgorithms
 *
 * Realiza a validação de diversos tipos de documentos
 *
 * @author  Matheus Lopes Santos <fale_com_lopez@hotmail.com>
 * @author  Anderson Souza <sakn45@bol.com.br>
 * Feitas diversas mudanças nos métodos da classe e acrescentado método destruct
 * @version 1.0.3
 * @since   08/07/2016
 * @package ValidDocs
 */
class ValidationAlgorithms
{
    /**
     * Recebe a string contendo o número do documento
     * @access protected
     * @var string
     */
    protected $valor;
        
    const MULTIPLICADOR= [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Construtor da classe
     * @author Anderson Souza <sakn45@bol.com.br>
     * Acrescentando a verificação se o documento informado é de fato do tipo string
     * e o mesmo não está vazio.
     * Também acrescentado o lançamento de excessões caso uma string e/ou um documento
     * não seja informado.
     * E por fim, acrescentado a sanitização do valor recebido.
     * @access public
     * @param string $documento           Representa o tipo de documento que deseja-se validar
     * @throws \UnderflowException        Lançada, caso nenhum valor seja informado.
     * @throws \InvalidArgumentException  Lançada, caso um tipo string não seja informado.
     */
    public function __construct($documento)
    {
        if (\is_string($documento)) {
            if (!empty($documento)) {
                $this->valor = \filter_var($documento, \FILTER_SANITIZE_STRING);
                
            } else {
                throw new \UnderflowException('Obrigatório a informar um valor');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }        
    }

    /**
     * Realiza o cálculo das posições dos valores de cada dígito do documento
     * @author Anderson Souza <sakn45@bol.com.br>
     * Foi retirada o parametro $soma_digitos que era opcional.
     * Informando a mesma internamente no método.
     * Alterado o nome das variáveis para o padrão camelCase.
     * Alterado o trecho da soma dos dígitos, onde havia repetição no uso da variável
     * a mesma foi removida, ao invés de fazer uma decrementação, foi prefirível
     * o uso do indice já previamente informado, como forma de ajudar na diminuição
     * das posições e posterior multiplicação, e por fim alterado o comentário também.
     * Onde havia uma simples condicional if, foi alterado para uma operação ternária.
     * Acrescentado a verificação se o parametro $digito fornecido, de fato é uma string
     * e/ou não está vazio.
     * Acrescentado o lançamento de duas excessões.
     * Acrescentada a variável $restoSoma, para armezar o resto da soma efetuada anteriormente.
     * Inserindo um cast para string, no trecho que necessita da concatenação entre as variáveis.
     * @access public
     * @param   string  $digitos         Os dígitos desejados
     * @param   integer $posicoes        A posição de onde o algo inicia a regreção
     * @param   integer $somaDigitos     A soma dos multiplicadores entre posições e dígitos
     * @return  string
     * @throws \UnderflowException       Lançada, caso nenhum valor seja informado
     * @throws \InvalidArgumentException Lançada, caso um tipo string não seja informado
     */
    public function calcDigitPositions($digitos, $posicoes = 10)
    {
        if (\is_string($digitos)) {
            if (!empty($digitos)) {
                /**
                * Faz a soma dos dígitos com a posição
                * Ex. para 10 posições:
                *  0      2    5    4    6    2    8    8   4
                *  x10   x9   x8   x7   x6   x5   x4   x3  x2
                *  0 +   18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
                */
                for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
                    /*
                    * Efetua a soma dos digitos do documentos, multiplicado pela posição
                    * @var int $somaDigitos
                    */
                    $somaDigitos +=  $digitos[$i] * ($posicoes - $i);

                    /*
                    * Parte específica para CNPJ
                    * Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
                    */
                    $posicoes < 2 ? $posicoes = 9 : $posicoes;
                }
                
                /*
                * Captura o resto da divisão entre $restoSoma dividido por 11
                * Ex.: 196 % 11 = 9
                * @var $restoSoma int *
                */
                $restoSoma = $this->calculateRest($somaDigitos);
                
                /*
                * Concatena mais um dígito aos primeiro nove dígitos
                * Ex.: 025462884 + 2 = 0254628842
                * @var string $documento
                */
                $documento = $digitos . ((string)$restoSoma);
                
            } else {
                throw new \UnderflowException('Um valor deve ser informado');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }       
        return $documento;
    }

    /**
     * Verifica a igualdade dos caracteres
     * @author Anderson Souza <sakn45@bol.com.br>
     * O método foi totalmente reescrito, deixando-o mais eficiente. 
     * @access public
     * @param string $documento            Representa o tipo de documento que deseja-se verificar
     *                                     a igualdade.
     * @return boolean
     * 
     */
    public function verifyEquality($documento) {
        if (strcasecmp($this->valor, $documento) === 0) {
            return true;
        }
        return false;
    }

    /**
     * Realiza a checkagem de um número de PIS
     * @access public
     * @author Anderson Souza <sakn45@bol.com.br>
     * Retirada a variável local $multicador, e transformou a mesma em uma constante,
     * já que o valor nunca mudará e nem deve mudar.
     * Acrescentada verificação de o parametro informado é de fato string e não o mesmo não
     * não foi informado.
     * Também acrescentado o lançamento de duas excessões.
     * Removido a variável $atual, que foi criada no intuito de armezenar cada número do PIS
     * para posterior soma, procedimento desnecessário.
     * Forçando primeiro efetuar o cast e somente depois proceder com os cálculos.
     * Removida a declaração condicional if, e no lugar foi colocada uma operação ternária
     * @param   string  $pis
     * @return  boolean
     */
    public function checkPIS($pis)
    {
        if (\is_string($pis)) {
            if (!empty($pis)) {
                /* @var $soma int */
                $soma= 0;
                
                /* @var $pis array */
                $pis= str_split($pis);
                
                for($i = 0; $i < 10; $i++) {
                    $soma += ((int)$pis[$i]) * self::MULTIPLICADOR[$i];
                }
                /*
                 * Captura o resto da divisão entre $soma dividido por 11
                 * Ex.: 196 % 11 = 9
                 * @var int $resto
                 */                
                $resto = $this->calculateRest($soma);
                
                /* @var $digito int */
                $digito = (int) $pis[10];
                
                return $resto === $digito ? true : false;
                
            } else {
                throw new \UnderflowException('Obrigatório informar um valor');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }
    }

    /**
     * Realiza a soma do mod 11, utilizado no CPF, PIS e CPNJ
     * 
     * @author Anderson Souza <sakn45@bol.com.br>
     * Pequena alteração na descrição do método
     * Alteração no nome do parametro, deixando mais clara a finalidade
     * Alterado o nome do método, deixando a finalidade mais clara
     * Acrescentado a verificação se de fato o parametro recebido é um inteiro e/ou
     * não está vazio.
     * Alterado um pouco o trecho condicional do if, retirando o else.
     * Acrescentando o lançamento de duas excessões.
     * @access private
     * @param   int  $soma
     * @return  int
     * @throws \UnderflowException       Lançada, caso nenhum valor seja informado.
     * @throws \InvalidArgumentException Lançada, caso um tipo string não seja informado.
     */
    private function calculateRest($soma) {
        if (\is_int($soma)) {
            if (!empty($soma)) {
                /* @var $resto int */
                $resto = $soma % 11;
                
                if ($resto < 2) {
                    $resto = 0;
                }
                $resto= 11 - $resto;
                
            } else {
                throw new \UnderflowException('Obrigatório informar um valor');
            }
            
        } else {
            throw new \InvalidArgumentException('Tipo string é obrigatório');
        }
        return $resto;
    }
    
    public function __destruct() {
        unset($this->valor);        
    }
}
