<?php
function check_date($str)
{
	$scrapedate = date('M d, Y',strtotime(date('Y-m-d') . "-1 days"));	
	if(strpos($str, $scrapedate, 0) == FALSE)
	{
	return 0;
	}
	else
	{
	return 1;
	}
}
function scrape_beef_trim($file_name,$commodity_name,$commodity_search,$next_commodity)
{
	$url = "http://www.ams.usda.gov/mnreports/lm_xb401.txt";
	$ch=curl_init($url);
	$fields = null;
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // set the fields to post
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$str = curl_exec($ch);		
	$handle = fopen($file_name, "a");
	if ( 0 == filesize( $file_name ) ){
		$line = array();
		$line[0] = "Date";
		$line[1] = $commodity_name;
		fputcsv($handle, $line);
	}	
	//$checkdtae = date('M d, Y',strtotime(date('Y-m-d') . "-1 days"));	
	$start = strpos($str,'FOB Plant - National',0);
	$limit = strpos($str,$next_commodity,$start);
	$start = strpos($str,$commodity_search,$start);
	$start = strpos($str,'$',$start);
	$start = strpos($str,'$',$start+1);
	$start = strpos($str,'$',$start+1);		
	$end = strpos($str,' ',$start);
	$str_beef = substr($str,$start,$end - $start);
	$str_beef = str_replace("$","",$str_beef);
	$str_beef = (float)$str_beef;	
	$line = array();
	//echo "result : ".check_date($str)."<br>";
	if(($limit<$end)||(check_date($str)==0)){
		$line[0] = date('n/d/Y',strtotime(date('Y-m-d') . "-1 days"));
		$str_beef = "last record";//getLastrecord($file_beef_trim_50);
		$line[1] = $str_beef;
	}
	else{
		$line[0] = date('n/d/Y',strtotime(date('Y-m-d') . "-1 days"));
		$line[1] = $str_beef;
	}
	echo $str_beef."<br>";
	fputcsv($handle, $line);
	fclose($handle);
}
scrape_beef_trim("beef_trim_90_percent.csv","Beef Trim 90 Percent DOM Fresh USDA","Fresh  90%","Frozen 90%");
scrape_beef_trim("beef_trim_75_percent.csv","Beef Trim 75 Percent DOM Fresh USDA","Fresh  75%","Frozen 75%");
scrape_beef_trim("beef_trim_50_percent.csv","Beef Trim 50 Percent DOM Fresh USDA","Fresh  50%","Frozen 50%");
?>
