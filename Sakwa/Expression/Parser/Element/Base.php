<?php

namespace Sakwa\Expression\Parser\Element;

class Base extends Transformations
{
    const TOKEN_ROOT                = 'Root',
          TOKEN_UNKNOWN             = 'Unknown',
          TOKEN_NUMBER              = 'Number',
          TOKEN_IDENTIFIER          = 'Identifier',
          TOKEN_LOGIC_OPERATOR      = 'LogicOperator',
          TOKEN_ASSIGNMENT_OPERATOR = 'AssignmentOperator',
          TOKEN_OPERATOR            = 'Operator',
          TOKEN_WHITESPACE          = 'Whitespace',
          TOKEN_LITERAL             = 'Literal',
          TOKEN_GROUP               = 'Group',
          TOKEN_INVERT              = 'Invert',
          TOKEN_VARIABLE_IDENTIFIER = 'VariableIdentifier',
          TOKEN_ESCAPE              = 'Escape',
          TOKEN_POINT               = 'Point',
          TOKEN_FUNCTION_CALL       = 'FunctionCall',
          TOKEN_PARAMETER_SEPARATOR = 'ParameterSeparator',
          TOKEN_PARAMETER           = 'Parameter';
}
