<?php

class Options{
	private static $options;

	private static $instance;
	
	private function __construct() {}

	public static function __init(){
		if(!isset(self::$options)) {
			
			self::$instance = new Options;
			self::$instance->_loadOptions();
		}
		return;
	}
	
	public static function get($option_name,$default = FALSE)
	{
		if(!isset(self::$options))
			self::__init();
		
		$option_name = trim(str_replace(' ', '_', $option_name));
		
 		if(array_key_exists($option_name,self::$options))
 			return self::$options[$option_name]?:$default;
		
		//if not autoloaded get it from database
		$CI =& get_instance();

		$option = $CI->doctrine->em->createQueryBuilder()->select('o')
			->from('models\Common\Options', 'o')
			->where('o.name = :name')->setParameter('name', $option_name)
			->getQuery()->getOneOrNullResult();

		if(!$option)
			return $default;

		$value = $option->getValue();
		return self::$instance->_maybe_unserialize($value);		
	}
	
	public static function set($option, $value = '',$autoload = 1)
	{
		if(!isset(self::$options))
			self::__init();
			
		$option = trim($option);
		
		if ( empty($option) )
	    	return false;
		
		if(self::get($option) === FALSE)
		{
			$value = self::$instance->_maybe_serialize( $value );
			$autoload = ( 0 === $autoload ) ? 0 : 1;	
			
			$CI =& get_instance();			
			$em = $CI->doctrine->em;

			$newOption = $em->createQueryBuilder()->select('o')
			->from('models\Common\Options', 'o')
			->where('o.name = :name')->setParameter('name', $option)
			->getQuery()->getOneOrNullResult();

			if(!$newOption)
				$newOption = new \models\Common\Options();
			$newOption->setName($option);
			$newOption->setValue($value);

			$em->persist($newOption);

			if($em->flush())
				return true;
			else
				return false;

		}else
			show_error('The option <b>"'.$option.'"</b> already exists.');
	}
	
	public static function update($option, $newvalue)
	{
		if(!isset(self::$options))
			self::__init();
		
		$option = trim($option);
		
		if ( empty($option) )
	    	return false;
			
		$oldvalue = self::get( $option );
		
		if ( $newvalue === $oldvalue )
	        return false;
			
		if ( FALSE === $oldvalue )
	        return self::set( $option, $newvalue );
		
		$newvalue = self::$instance->_maybe_serialize( $newvalue );
		
		$CI =& get_instance();
		$em = $CI->doctrine->em;
		$oldOption = $em->createQueryBuilder()->select('o')
			->from('models\Common\Options', 'o')
			->where('o.name = :name')->setParameter('name', $option)
			->getQuery()->getOneOrNullResult();

		if($oldOption)
		{
			$oldOption->setValue($newvalue);
			$em->flush();
			self::$instance->_loadOptions();
			return true;
		}else{
			return false;
		}
	}
	
	private function _loadOptions()
	{
		$CI =& get_instance();

		$qb = $CI->doctrine->em->createQueryBuilder();		
		$qb->select('o')
			->from('models\Common\Options', 'o')
			->where('o.autoload = :true')->setParameter('true', true);
		$result = $qb->getQuery()->getResult();
		
		$options = array();
		foreach($result as $o)
		{
			$options[$o->getName()] = $this->_maybe_unserialize($o->getValue());
		}
		
		self::$options = $options;
		return;
	}
	private function _maybe_serialize( $data ) {
		if ( is_array( $data ) || is_object( $data ) )
	        return serialize( $data );
	
	    if ($this->_is_serialized( $data ) )
	        return serialize( $data );
	    return $data;
	}
	
	private function _maybe_unserialize( $original ) {
	    if ($this->_is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
	            return @unserialize( $original );
	    return $original;
	}
	
	private function _is_serialized( $data ) {
		// if it isn't a string, it isn't serialized
	    if ( ! is_string( $data ) )
	    	return false;
		$data = trim( $data );
	
	    if ( 'N;' == $data )
		    return true;
	    $length = strlen( $data );
	    if ( $length < 4 )
	            return false;
	    if ( ':' !== $data[1] )
	            return false;
	    $lastc = $data[$length-1];
	    if ( ';' !== $lastc && '}' !== $lastc )
	            return false;
	    $token = $data[0];
	    switch ( $token ) {
            case 's' :
                if ( '"' !== $data[$length-2] )
                            return false;
            case 'a' :
            case 'O' :
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b' :
            case 'i' :
            case 'd' :
                return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
	    }
	    return false;
	}	
	
	public function __clone() {
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
}
