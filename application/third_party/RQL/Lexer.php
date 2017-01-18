<?php

namespace RQL;

class Lexer extends \Doctrine\Common\Lexer
{
	
	// All tokens that are not valid identifiers must be < 100
	const T_NONE                = 1;
	const T_INTEGER             = 2;
	const T_STRING              = 3;
	const T_INPUT_PARAMETER     = 4;
	const T_FLOAT               = 5;
	const T_CLOSE_PARENTHESIS   = 6;
	const T_OPEN_PARENTHESIS    = 7;
	const T_COMMA               = 8;
	const T_DIVIDE              = 9;
	const T_DOT                 = 10;
	const T_EQUALS              = 11;
	const T_GREATER_THAN        = 12;
	const T_LOWER_THAN          = 13;
	const T_MINUS               = 14;
	const T_MULTIPLY            = 15;
	const T_NEGATE              = 16;
	const T_PLUS                = 17;
	const T_OPEN_CURLY_BRACE    = 18;
	const T_CLOSE_CURLY_BRACE   = 19;
	const T_COLON				= 20;
	const T_IDENTIFIER			= 100;
	const T_TABLIZE				= 101;
	const T_BY					= 102;
	const T_FILTERABLE			= 103;
	const T_TYPE				= 104;
	const T_DATE				= 105;
	const T_COMBO				= 106;
	const T_SUM					= 108;
	const T_AVERAGE				= 109;
	const T_FILTER				= 110;
	const T_FREETEXT			= 112;
	const T_ACCOUNT				= 113;
	const T_CARD				= 114;
	const T_REMARKS				= 115;
	const T_BRANCH				= 116;
	const T_BRANCHCODE			= 117;
	const T_CARDSTATUS			= 118;
	const T_REPINSTATUS			= 119;
	
	public function __construct($input){
		$this->setInput($input);
	}
	
	function getCatchablePatterns(){
		return array(
				'[a-z_\\\][a-z0-9_\\\]*[a-z0-9_]{1}',
				"'(?:[^']|'')*'",
				);
	}
	
	function getNonCatchablePatterns(){
		return array('\s+', '(.)',':');
	}
	
	protected function getType(&$value)
    {
        $type = self::T_NONE;

        switch (true) {
            // Recognize numeric values
            case (is_numeric($value)):
                if (strpos($value, '.') !== false || stripos($value, 'e') !== false) {
                    return self::T_FLOAT;
                }

                return self::T_INTEGER;

            // Recognize quoted strings
            case ($value[0] === "'"):
                $value = str_replace("''", "'", substr($value, 1, strlen($value) - 2));

                return self::T_STRING;

            // Recognize identifiers
            case (ctype_alpha($value[0]) || $value[0] === '_'):
                $name = '\RQL\Lexer::T_' . strtoupper($value);
				if (defined($name)) {
                    $type = constant($name);

                    if ($type > 100) {
                        return $type;
                    }
                }

                return self::T_IDENTIFIER;

            // Recognize input parameters
            case ($value[0] === '?'):
                return self::T_INPUT_PARAMETER;

            case ($value[0] === ':'):
            	return self::T_COLON;
            // Recognize symbols
            case ($value === '.'): return self::T_DOT;
            case ($value === ','): return self::T_COMMA;
            case ($value === '('): return self::T_OPEN_PARENTHESIS;
            case ($value === ')'): return self::T_CLOSE_PARENTHESIS;
            case ($value === '='): return self::T_EQUALS;
            case ($value === '>'): return self::T_GREATER_THAN;
            case ($value === '<'): return self::T_LOWER_THAN;
            case ($value === '+'): return self::T_PLUS;
            case ($value === '-'): return self::T_MINUS;
            case ($value === '*'): return self::T_MULTIPLY;
            case ($value === '/'): return self::T_DIVIDE;
            case ($value === '!'): return self::T_NEGATE;
            case ($value === '{'): return self::T_OPEN_CURLY_BRACE;
            case ($value === '}'): return self::T_CLOSE_CURLY_BRACE;

            // Default
            default:
                // Do nothing
        }

        return $type;
    }
}