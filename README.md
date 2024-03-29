Log Reader Class
================

With this class you can easily read and output your PHP error class.

Installation
------------

Include the class in your PHP using the following code:

    require_once('log-reader.class.php');

Usage
-----

Create a new instance of the class:

    $reader = new LogReader('path/to/error_log');

You can output the log as an array using the function $reader->get_log([, int $limit]). This function accepts one parameter $limit which is the limit to the number of errors.

You can output the log as a string using the function $reader->get_log_string([array $format, [, int $limit]]). The parameter $format is an array of strings:

    array(
        'date_format' => '[d-M-Y H:i:s]',
        'before_error_type' => ' ',
        'after_error_type' => ': ',
        'after_error' => '<br />'
    );

