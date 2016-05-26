<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Model_logs extends CI_Model {
    public $dbgroup = 'default';
    public $table   = 'logs';
    public $primary = 'id';
}