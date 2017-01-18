<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('parseExcel'))
{
    function parseExcel($file)
	{
		$objPHPExcel = PHPExcel_IOFactory::load($file);
	    $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
            $headingsArray = $headingsArray[1];
            $r = -1;
            $namedDataArray = array();
            for ($row = 2; $row <= $highestRow; ++$row) {
                $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
                if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
                    ++$r;
                    foreach($headingsArray as $columnKey => $columnHeading) {
                        $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
                    }
                }
               
            
            }

        return (count($namedDataArray)<1) ? false : $namedDataArray;
	}
}