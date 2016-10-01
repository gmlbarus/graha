<?php
class View
{
	function load($view_file, $args = array())
	{
		extract($args);

		if (file_exists("view/{$view_file}.html"))
			include("view/{$view_file}.html");
		else
			exit("505. Internal Error.");
	}

	function load_index($content, $args = array())
	{
		if (file_exists("view/{$content}.html"))
			include("view/index.html");
		else
			exit("505. Internal Error.");
	}
}
?>