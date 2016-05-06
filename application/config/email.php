<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol'] = 'smtp';
//$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = 'ssl://smtp.googlemail.com';
$config['smtp_user'] = 'YOUREMAILHERE@gmail.com';
$config['smtp_pass'] = 'YOURPASSWORDHERE';
$config['smtp_port'] = 465; 
$config['smtp_timeout'] = 5;
$config['wordwrap'] = TRUE;
$config['wrapchars'] = 76;
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['validate'] = FALSE;
$config['priority'] = 3;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['bcc_batch_mode'] = FALSE;
$config['bcc_batch_size'] = 200;


/* End of file email.php */
/* Location: ./application/config/email.php */
