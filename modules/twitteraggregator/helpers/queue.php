<?php
/*
 * Created on 6-feb-2010
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class queue {

	public static function get_oldest_file($directory)
	{
		$file_date = array();

		// add trailing slash if necessary
		if(substr(strrev($directory),0,1) != "/")
		{
			$directory .= "/";
		}

		if ($handle = opendir($directory)) {
		    while (false !== ($file = readdir($handle)))
		    {
		        $files[] = $file;
		    }

		    foreach ($files as $val)
		    {
		        if (is_file($directory.$val))
		        {
		            $file_date[$val] = filemtime($directory.$val);
		        }
		    }
		}

		closedir($handle);
		asort($file_date, SORT_NUMERIC);
		reset($file_date);
		$oldest = key($file_date);
		return $oldest;
	}

	public static function str_putcsv($array, $delimiter = ',', $enclosure = '"', $terminator = "\n")
	{
        # First convert associative array to numeric indexed array
        foreach ($array as $key => $value) $workArray[] = $value;

        $returnString = '';                 # Initialize return string
        $arraySize = count($workArray);     # Get size of array

        for ($i=0; $i<$arraySize; $i++)
        {
            # Nested array, process nest item
            if (is_array($workArray[$i]))
            {
                $returnString .= self::str_putcsv($workArray[$i], $delimiter, $enclosure, $terminator);
            }
            else
            {
                switch (gettype($workArray[$i]))
                {
                    # Manually set some strings
                    case "NULL":     $_spFormat = ''; break;
                    case "boolean":  $_spFormat = ($workArray[$i] == true) ? 'true': 'false'; break;
                    # Make sure sprintf has a good datatype to work with
                    case "integer":  $_spFormat = '%i'; break;
                    case "double":   $_spFormat = '%0.2f'; break;
                    case "string":   $_spFormat = '%s'; break;
                    # Unknown or invalid items for a csv - note: the datatype of array is already handled above, assuming the data is nested
                    case "object":
                    case "resource":
                    default:         $_spFormat = ''; break;
                }
                $returnString .= sprintf('%2$s'.$_spFormat.'%2$s', $workArray[$i], $enclosure);
				$returnString .= ($i < ($arraySize-1)) ? $delimiter : $terminator;
            }
        }
        # Done the workload, return the output information
        return $returnString;
    }
}
?>
