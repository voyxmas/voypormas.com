<?php

/**
 * CodeIgniter Helper extensions
 * @package	CodeIgniter
 * @author	Fracisco Javier Machado
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
function cstm_get_edad($date)
{
  $CI = &get_instance();

  $tz = new DateTimeZone($CI->config->item('time_reference'));
  $age = DateTime::createFromFormat(SYS_DATE_FORMAT, $date, $tz)
    ->diff(new DateTime('now', $tz))
    ->y;
  return $age;
}

function cstm_get_time($datetime = null, $format = APP_TIME_FORMAT)
{
  if ($datetime) {
    return date($format, strtotime($datetime));
  } else {
    return date($format);
  }
}

function cstm_get_time_from_military($int)
{
  $return = substr_replace($int, ':', -2, 0);
  $return = strlen($return) === 4 ? '0' . $return : $return;

  return $return;
}

function cstm_get_date($datetime = null, $format = APP_DATE_FORMAT)
{
  if ($datetime) {
    return date($format, strtotime($datetime));
  } else {
    return date($format);
  }
}

function cstm_get_datetime($datetime = null, $format = APP_DATETIME_FORMAT)
{
    if ($datetime!="" AND $datetime!="0000-00-00 00:00:00" AND $datetime!="0000-00-00T00:00:00") {
      return date($format, strtotime($datetime));
    } else {
      return FALSE;
    }
}

function cstm_today($format = APP_DATE_FORMAT)
{
  return date($format);
}

function cstm_now($format = APP_DATETIMEFULL_FORMAT)
{
  return date($format);
}

function cstm_tomorrow($format = APP_DATE_FORMAT, $date_ref = false)
{
  $date_ref || cstm_today();
  $datetime = date($format, strtotime($date_ref . ' +1 day'));
  return $datetime;
}

function cstm_nextweek($format = APP_DATE_FORMAT, $date_ref = false)
{
  $date_ref || cstm_today();
  $datetime = date($format, strtotime($date_ref . ' +1 week'));
  return $datetime;
}

function cstm_nextmonth($format = APP_DATE_FORMAT, $date_ref = false)
{
  $date_ref || cstm_today();
  $datetime = date($format, strtotime($date_ref . ' +1 month'));
  return $datetime;
}

function cstm_nextyear($format = APP_DATE_FORMAT, $date_ref = false)
{
  $date_ref || cstm_today();
  $datetime = date($format, strtotime($date_ref . ' +1 year'));
  return $datetime;
}

function cstm_timedif($date1 = 1, $date2 = 2, $format = 'H:i')
{
  $date1 = strtotime($date1);
  $date2 = strtotime($date2);
  $intervalo = $date1 - $date2;

  return date($format, $intervalo);
}

function cstm_timedif_minutes($date1 = 1, $date2 = 2)
{
  $date1 = strtotime($date1);
  $date2 = strtotime($date2);
  $intervalo = $date1 - $date2;

  return round($intervalo / 60);
}

function time2key($time)
{
  return date("Hi", strtotime($time));
}

?>