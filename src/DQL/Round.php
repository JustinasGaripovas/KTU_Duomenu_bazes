<?php

namespace App\DQL;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\SqlWalker;

class Round extends FunctionNode
{
    private $arithmeticExpression;
    private $roundPrecision;

    public function getSql(SqlWalker $sqlWalker)
    {

        return 'ROUND(' .
            $sqlWalker->walkSimpleArithmeticExpression($this->arithmeticExpression) .','.
            $sqlWalker->walkStringPrimary($this->roundPrecision)
            . ')';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {

        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->arithmeticExpression = $parser->SimpleArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->roundPrecision = $parser->ArithmeticExpression();
        if($this->roundPrecision == null){
            $this->roundPrecision = 0;
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}