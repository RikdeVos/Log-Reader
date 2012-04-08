<?php

/**
 * LogReader Class
 * 
 * @version 1.0.0
 * @author Rik de vos <rik12345@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */
class LogReader {

	/**
	 * The path to the error log
	 * @var string
	 */
	private $path = '';

	/**
	 * The parsed error log
	 * @var array
	 */
	private $log = array();

	/**
	 * The raw error log
	 * @var string
	 */
	private $log_raw = '';


	/**
	 * Constructor functions
	 * @param string $path The path to the error log
	 */
	function __construct($path) {

		$this->path = $path;

		$this->_download_log();
		$this->_parse_log();

	}

	/**
	 * Returns the log as a string
	 * @param  array   $format An array of options
	 * @param  boolean $limit  The limit of rows
	 * @return string          The log
	 * @access public
	 */
	public function get_log_string($format = array(), $limit = false) {

		$defaults = array(
			'date_format' => '[d-M-Y H:i:s]',
			'before_error_type' => ' ',
			'after_error_type' => ': ',
			'after_error' => '<br />'
		);

		$options = array_merge($defaults, $format);

		$text = '';
		foreach($this->log as $i=>$line) {
			$text .= date($options['date_format'], $line['time']).$options['before_error_type'].$line['error_type'].$options['after_error_type'].$line['error'].$options['after_error'];
		}

		return $text;

	}

	/**
	 * Returns the log as array
	 * @param  mixed $limit The limit of rows
	 * @return array        The log
	 * @access public
	 */
	public function get_log($limit = false) {

		if($limit === false) {
			$limit = $this->log_length;
		}

		$start = $this->log_length-$limit;
		$end = $this->log_length;

		$log = array();
		foreach($this->log as $i=>$line) {
		
			if($i <= $start-2) { continue; }
			if($i === $end-1) { break; }

			$log[] = $line;

		}

		return $log;

	}

	/**
	 * Parses the log
	 * @return null
	 * @access private
	 */
	private function _parse_log() {

		foreach($this->log_raw as $i=>$line) {

			preg_match_all('/^\[([a-zA-Z0-9- :]+?)\]\s*([a-zA-Z0-9- ]+?):\s*(.*)$/', $line, $matches);

			if(empty($matches[0])) { continue; }

			if(isset($matches[0][0])) { $matches[0] = $matches[0][0]; }
			if(isset($matches[1][0])) { $matches[1] = $matches[1][0]; }
			if(isset($matches[2][0])) { $matches[2] = $matches[2][0]; }
			if(isset($matches[3][0])) { $matches[3] = $matches[3][0]; }

			$this->log[] = array(
				'total' => $line,
				'time' => strtotime($matches[1]),
				'error_type' => $matches[2],
				'error' => $matches[3]
			);

		}

	}

	/**
	 * Parses the log
	 * @return null
	 * @access private
	 */
	private function _download_log() {

		$contents = @file($this->path, FILE_SKIP_EMPTY_LINES);
		if(!is_array($contents)) {
			return;
		}

		$this->log_raw =$contents;
		$this->log_length = count($this->log_raw);

	}


}

?>