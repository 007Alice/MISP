<?php

/**
 * http://en.wikipedia.org/wiki/CamelCase
 **/
class CamelCase {

/**
 * http://php.net/manual/en/function.lcfirst.php
 **/
	function lcfirst($str) {
		$str{0} = strtolower($str{0});
		return $str;
	}

/**
 * http://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
 **/
	function fromCamelCase($input) {
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
		$ret = $matches[0];
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : $this->lcfirst($match); // TODO string lcfirst
		}
		return implode('_', $ret);
	}

/**
 * http://www.paulferrett.com/2009/php-camel-case-functions/
 **/
	function fromCamelCase2($str) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}

/**
 * 
 **/
	function toCamelCase($underscored) {
		//App::uses('Inflector', 'lib');
		return Inflector::camelize($underscored);
	}		
}