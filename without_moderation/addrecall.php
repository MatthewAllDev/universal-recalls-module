<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');

	function create_xml_document($data)
	{
		$root = $data->createElement('root');
		$data->appendChild($root);
		create_page($data, 1);
	}

	function add_recall($data, $date, $name, $text)
	{
		$page = search_page($data, 1);
		$target = get_recall($page, 'first');
		if (!$target)
			$recall_id = 1;
		else
			$recall_id = $target->getAttribute('id') + 1;

		$recall_element = $data->createElement('recall');
		$attr_1 = $data->createAttribute('id');
		$attr_1->value = $recall_id;
		$recall_element->appendChild($attr_1);
		$id_element = $data->createElement('id', $recall_id);
		$date_element = $data->createElement('date', $date);
		$name_element = $data->createElement('name', $name);
		$text_element = $data->createElement('text', $text);

		$recall_element->appendChild($id_element);
		$recall_element->appendChild($date_element);
		$recall_element->appendChild($name_element);
		$recall_element->appendChild($text_element);

		if (!$target)
			$page->appendChild($recall_element);
		else
			$page->insertBefore($recall_element, $target);

		if (get_recall_count($page) < 50)
			inc_recall_count($page);
		else
			shift_recall($data, $page->getAttribute('id'));
	}

	function shift_recall($data, $page_id)
	{
		$page = search_page($data, $page_id);
		$element = get_recall($page, 'last');
		$element->parentNode->removeChild($element);
		$next_page = search_page($data, $page_id + 1);
		$target = get_recall($next_page, 'first');
		if (!$target)
			$next_page->appendChild($element);
		else
			$next_page->insertBefore($element, $target);
		if (get_recall_count($next_page) < 50)
			inc_recall_count($next_page);
		else
			shift_recall($data, $next_page->getAttribute('id'));
	}

	function search_page($data, $page_id)
	{
		$pages = $data->getElementsByTagName('page');
		foreach ($pages as $page) 
		{
			if ($page->getAttribute('id') == $page_id)
				return $page;
		}
		return create_page($data, $page_id);
	}

	function create_page($data, $page_id)
	{
		$root = $data->documentElement;
		$page = $data->createElement('page');
		$attr_1 = $data->createAttribute('id');
		$attr_1->value = $page_id;
		$page->appendChild($attr_1);
		$count = $data->createElement('count', '0');
		$page->appendChild($count);
		$root->appendChild($page);
		return $page;
	}

	function get_recall_count($page)
	{
		$elements = $page->childNodes;
		foreach ($elements as $element) 
		{
			if ($element->nodeName == 'count')
				return $element->nodeValue;
		}
	}

	function inc_recall_count($page)
	{
		$elements = $page->childNodes;
		foreach ($elements as $element) 
		{
			if ($element->nodeName == 'count')
				$element->nodeValue = $element->nodeValue + 1;
		}
	}

	function get_recall($page, $position)
	{
		$elements = $page->childNodes;
		$return_value = NULL;
		foreach ($elements as $element) 
		{
			if ($element->nodeName == 'recall')
				if ($position == 'first')
					return $element;
				elseif ($position == 'last')
					$return_value = $element;
		}
		return $return_value;
	}

	if ($_POST)
	{
		$date = trim(strip_tags($_POST["date"]));
		$name = trim(strip_tags($_POST["name"]));
		$text = trim(strip_tags($_POST["text"]));
		$dom = new DOMDocument();
		$dom->encoding = "utf-8";
		if ($dom->load("recalls.xml"))
			add_recall($dom, $date, $name, $text);
		else
		{
			create_xml_document($dom);
			add_recall($dom, $date, $name, $text);
		}
		$dom->save('recalls.xml');
	}
?>
