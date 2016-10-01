<?php
/**
* 
*/
class Utility
{
	static function eoq($biaya_pesan, $kebutuhan, $harga)
	{
		$eoq = sqrt((2 * $biaya_pesan * $kebutuhan) / (30 * $harga / 100));
		
		return round($eoq);
	}

	function rop($kebutuhan, $lead_time)
	{
		$rop = round($kebutuhan * $lead_time / 360);

		if ($rop > 0)
			return $rop;
		else
			return 1;
	}

	function ss($lead_time, $kebutuhan)
	{
		$ss  = round( 2.05 * ($lead_time) * ($kebutuhan / 365) );

		if ($ss > 0)
			return $ss;
		else
			return 1;
	}
}
?>