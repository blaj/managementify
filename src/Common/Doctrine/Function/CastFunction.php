<?php

namespace App\Common\Doctrine\Function;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

class CastFunction extends FunctionNode {


  protected Node|string $fieldIdentifierExpression;
  protected ?string $castingTypeExpression;


  public function getSql(SqlWalker $sqlWalker): string {
    return sprintf(
        'CAST(%s AS %s)',
        $sqlWalker->walkSimpleArithmeticExpression($this->fieldIdentifierExpression),
        $this->castingTypeExpression
    );
  }


  /**
   * @throws QueryException
   */
  public function parse(Parser $parser): void {
    $parser->match(TokenType::T_IDENTIFIER);
    $parser->match(TokenType::T_OPEN_PARENTHESIS);

    $this->fieldIdentifierExpression = $parser->SimpleArithmeticExpression();

    $parser->match(TokenType::T_AS);
    $parser->match(TokenType::T_IDENTIFIER);

    $type = $parser->getLexer()->token?->value;

    if ($parser->getLexer()->isNextToken(TokenType::T_OPEN_PARENTHESIS)) {
      $parser->match(TokenType::T_OPEN_PARENTHESIS);
      $parameter = $parser->Literal();
      $parameters = [$parameter->value];

      if ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
        while ($parser->getLexer()->isNextToken(TokenType::T_COMMA)) {
          $parser->match(TokenType::T_COMMA);
          $parameter = $parser->Literal();
          $parameters[] = $parameter->value;
        }
      }

      $parser->match(TokenType::T_CLOSE_PARENTHESIS);
      $type .= '(' . implode(', ', $parameters) . ')';
    }

    $this->castingTypeExpression = $type;

    $parser->match(TokenType::T_CLOSE_PARENTHESIS);
  }
}
