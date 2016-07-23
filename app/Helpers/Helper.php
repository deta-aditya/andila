<?php

namespace App\Helpers;

use Sentinel;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateInterval;
use DatePeriod;

class Helper
{
	
	/**
     * Format date into Indonesian date
     * credit to Abe Tobing (@abetobing)
     *
     */
	public static function indonesianDate($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') 
	{
	    if (trim ($timestamp) == '')
	    {
	            $timestamp = time ();
	    }
	    elseif (!ctype_digit ($timestamp))
	    {
	        $timestamp = strtotime ($timestamp);
	    }

	    // remove S (st,nd,rd,th) there are no such things in indonesia :p
	    $date_format = preg_replace ("/S/", "", $date_format);
	    $pattern = array (
	        '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
	        '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
	        '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
	        '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
	        '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
	        '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
	        '/April/','/June/','/July/','/August/','/September/','/October/',
	        '/November/','/December/',
	    );
	    $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
	        'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
	        'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
	        'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
	        'Oktober','November','Desember',
	    );
	    $date = date ($date_format, $timestamp);
	    $date = preg_replace ($pattern, $replace, $date);
	    $date = "{$date} {$suffix}";
	    return $date;

	}

	/**
     * Convert 'email' to 'username'
     *
     */
	public static function userify($email)
	{
		$fraction = explode('@', $email);
		return $fraction[0];
	}

	/**
	 * Get this week range date
	 *
	 */
	public static function getThisWeekDateRange()
	{
		$thisMonday = date('Y-m-d', strtotime('monday this week'));
		$thisSunday = date('Y-m-d', strtotime('saturday this week'));

		return $thisMonday . '_' . $thisSunday;
	}

	/**
	 * Get this week date in array
	 *
	 */
	public static function getThisWeekDateArray()
	{
		$start = Carbon::now()->startOfWeek();
		$end = Carbon::now()->endOfWeek();
		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($start, $interval, $end);
		$dates = [];

		foreach ($period as $date) {
			$dates[] = $date->format('Y-m-d');
		}

		return $dates;
	}

	/**
	 * Get months in array
	 *
	 */
	public static function getMonthsArray($months)
	{
		$start = Carbon::now()->startOfMonth();
		$end = Carbon::now()->addMonths($months);
		$interval = CarbonInterval::months(1);
		$period = new DatePeriod($start, $interval, $end);
		$dates = [];

		foreach ($period as $date) {
			$dates[] = $date->format('Y-m-d');
		}

		return $dates;
	}

	/**
	 * Get array of date intervals 
	 * starting from specified date until its next month
	 *
	 */
	public static function getDatesUntilNextMonth($date)
	{
		$current = Carbon::parse($date);
		$nextMonth = $current->copy()->addMonth();

		return self::getDatesArray($date, $current->diffInDays($nextMonth));
	}

	/**
	 * Get dates in array
	 *
	 */
	public static function getDatesArray($date, $iterate)
	{
		$start = Carbon::parse($date);
		$end = Carbon::parse($date)->addDays($iterate);
		$interval = CarbonInterval::days(1);
		$period = new DatePeriod($start, $interval, $end);
		$dates = [];

		foreach ($period as $date) {
			$dates[] = $date->format('Y-m-d');
		}

		return $dates;
	}
}
