<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');
	
	function search_page($data, $id)
	{
		foreach ($data as $page) {
			if ($page['id'] == $id)
				return $page;
		}
	}

	function get_page_count($data)
	{
		$i = 0;
		foreach ($data as $page) {
			$i++;
		}
		return $i;
	}

	function load_recalls()
	{
		$xml = simplexml_load_file('recalls.xml');
		$hr = '<hr />';
		$page_count = get_page_count($xml);
		if ($page_count > 1)
		{
			$page_selector = '<div align="center" class="otstup"><span class="swchItemA" page="1" onclick="load_page(this)">1</span>';
			for ($i=1; $i < $page_count; $i++)
			{
				$page_selector = $page_selector . '<span class="swchItem" page="' . ($i + 1) . '" onclick="load_page(this)">' . ($i + 1) . '</span>';
			} 
			$page_selector = $page_selector . '</div>';
		}
		else
			$page_selector = '';
		$page = '<div id="recall_page">' . load_page(1, $xml) . '</div>';
		$response = $hr . $page_selector . $page . $page_selector . $hr;
		return $response;
	}

	function load_page($page_id, $xml=null)
	{
		if(!$xml)
			$xml = simplexml_load_file('recalls.xml');
		$elements = search_page($xml, $page_id); 
		$response = '<hr />';
		foreach ($elements as $recall) {
			if ($recall->id)
				if ($recall->date)
					$response = $response . '<div class="recall" id="'. $recall->id .'"><div class="recall_date">' . $recall->date . '</div><div class="recall_name">' . $recall->name . ':</div><div class="recall_text">' . $recall->text . '</div></div><hr />';
				else
				$response = $response . '<div class="recall" id="'. $recall->id .'"><div class="recall_name">' . $recall->name . ':</div><div class="recall_text">' . $recall->text . '</div></div><hr />';
		}
		return $response;
	}

	if ($_GET["method"] == 'load_recalls')
	{
		echo load_recalls();
	}

	if ($_GET["method"] == 'load_page')
	{
		echo load_page($_GET['page_id']);
	}
	
?>
