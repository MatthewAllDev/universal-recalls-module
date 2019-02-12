<?php
	$___notjson=1;
	header('Content-Type: text/html; charset=utf-8');
	setlocale(LC_ALL, 'ru_RU.65001', 'rus_RUS.65001', 'Russian_Russia. 65001', 'russian');

	$PASSWORD_MD5 = 'YOUR PASSWORD MD5 HASH';

	/* SERVICE FUNCTIONS */

	function data_encrypt($data, $password)
	{
		$key = hash('sha256', $password);
		$key = pack('H*', $key);
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $data_en = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	    $data_en = $iv . $data_en;
	    return base64_encode($data_en);
	}

	function data_decrypt($data, $password)
	{
		$key = hash('sha256', $password);
		$key = pack('H*', $key);
	    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	    $data_dec = base64_decode($data);
	    $iv_dec = substr($data_dec, 0, $iv_size);
	    $data_dec = substr($data_dec, $iv_size);
	    return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data_dec, MCRYPT_MODE_CBC, $iv_dec);;
	}

	function create_xml_document($data, $public_document=FALSE)
	{
		$root = $data->createElement('root');
		$data->appendChild($root);
		return $data;
	}

	/*PUBLICATION FUNCTIONS*/

	function create_public_xml_document($data)
	{
		$root = $data->createElement('root');
		$data->appendChild($root);
		create_page($data, 1);
	}

	function add_public_recall($data, $date, $name, $text)
	{
		$page = search_page($data, 1);
		$target = get_public_recall($page, 'first');
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

		if (get_public_recall_count($page) < 50)
			inc_public_recall_count($page);
		else
			shift_public_recall($data, $page->getAttribute('id'));
	}

	function shift_public_recall($data, $page_id)
	{
		$page = search_page($data, $page_id);
		$element = get_public_recall($page, 'last');
		$element->parentNode->removeChild($element);
		$next_page = search_page($data, $page_id + 1);
		$target = get_public_recall($next_page, 'first');
		if (!$target)
			$next_page->appendChild($element);
		else
			$next_page->insertBefore($element, $target);
		if (get_public_recall_count($next_page) < 50)
			inc_public_recall_count($next_page);
		else
			shift_public_recall($data, $next_page->getAttribute('id'));
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

	function get_public_recall_count($page)
	{
		$elements = $page->childNodes;
		foreach ($elements as $element) 
		{
			if ($element->nodeName == 'count')
				return $element->nodeValue;
		}
	}

	function inc_public_recall_count($page)
	{
		$elements = $page->childNodes;
		foreach ($elements as $element) 
		{
			if ($element->nodeName == 'count')
				$element->nodeValue = $element->nodeValue + 1;
		}
	}

	function get_public_recall($page, $position)
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

	function publish_recall_to_file($date, $name, $text)
	{
		$dom = new DOMDocument();
		$dom->encoding = "utf-8";
		if ($dom->load("recalls.xml"))
			add_public_recall($dom, $date, $name, $text);
		else
		{
			create_public_xml_document($dom);
			add_public_recall($dom, $date, $name, $text);
		}
		return $dom->save('recalls.xml');
	}

	/*USER ACTION FUNCTIONS*/

	function add_recall($data, $password, $date, $name, $email, $text)
	{
		global $PASSWORD_MD5;
		if (!$password)
			$password = $PASSWORD_MD5;
		else
			$password = md5($password);
		$root = $data->lastChild;
		$target = $root->lastChild;
		if (!$target)
			$recall_id = 1;
		else
			$recall_id = $target->getAttribute('id') + 1;
		$date_en = data_encrypt($date, $password);
		$name_en = data_encrypt($name, $password);
		$email_en = data_encrypt($email, $password);
		$text_en = data_encrypt($text, $password);
		$recall_element = $data->createElement('recall');
		$attr_1 = $data->createAttribute('id');
		$attr_1->value = $recall_id;
		$recall_element->appendChild($attr_1);
		$id_element = $data->createElement('id', $recall_id);
		$date_element = $data->createElement('date', $date_en);
		$name_element = $data->createElement('name', $name_en);
		$email_element = $data->createElement('email', $email_en);
		$text_element = $data->createElement('text', $text_en);
		$recall_element->appendChild($id_element);
		$recall_element->appendChild($date_element);
		$recall_element->appendChild($name_element);
		$recall_element->appendChild($email_element);
		$recall_element->appendChild($text_element);
		$root->appendChild($recall_element);
		return 'success';
	}

	function publish_recall($data, $id, $password)
	{
		$password = md5($password);
		$root = $data->lastChild;
		$target = $root->firstChild;
		if ((!$target) or ($target->getAttribute('id') != $id))
			return 'error';
		else
		{
			$date = data_decrypt($target->getElementsByTagName('date')->item(0)->nodeValue, $password);
			$name = data_decrypt($target->getElementsByTagName('name')->item(0)->nodeValue, $password);
			$text = data_decrypt($target->getElementsByTagName('text')->item(0)->nodeValue, $password);
			$result1 = publish_recall_to_file($date, $name, $text);
			$result2 = remove_recall($data,'target', $target, $password);
		}
		if ($result1 and ($result2 != 'error'))
			return $result2;
		else
			return 'error';
	}

	function edit_recall($data, $id, $password, $name, $text)
	{
		$password = md5($password);
		$root = $data->lastChild;
		$target = $root->firstChild;
		if ((!$target) or ($target->getAttribute('id') != $id))
			return 'error';
		else
		{
			$date_en = data_encrypt($date, $password);
			$name_en = data_encrypt($name, $password);
			$text_en = data_encrypt($text, $password);
			$target->getElementsByTagName('name')->item(0)->nodeValue = $name_en;
			$target->getElementsByTagName('text')->item(0)->nodeValue = $text_en;
			return 'success';
		}
	}

	function remove_recall($data, $mode, $target, $password)
	{
		$root = $data->lastChild;
		if($mode == 'id')
		{
			$target_new = $root->firstChild;
			if ($target_new->getAttribute('id') != $target)
				return 'error';
			else
				$target = $target_new;
		}
		$root->removeChild($target);
		return 'success';
	}

	function load_recall ($data, $password)
	{
		$password = md5($password);
		$element = $data->firstChild;
		$id = $element->getElementsByTagName('id')->item(0)->nodeValue;
		if ($id == NULL) 
		{
			$response = 'Recalls not found.';
			return $response;
		}
		$date = data_decrypt($element->getElementsByTagName('date')->item(0)->nodeValue, $password);
		$name = data_decrypt($element->getElementsByTagName('name')->item(0)->nodeValue, $password);
		$email = data_decrypt($element->getElementsByTagName('email')->item(0)->nodeValue, $password);
		$text = data_decrypt($element->getElementsByTagName('text')->item(0)->nodeValue, $password);
		$panel = '<div class="panel"><div class="left_panel"><span class="button" id="add" onclick="add_recall_action()"><i class="fas fa-plus"></i></span></div><div class="right_panel"><span class="button" id="publish" target="' . $id . '" onclick="publish_recall(this)"><i class="fas fa-check"></i></span><span class="button" id="edit" target="' . $id . '" onclick="edit_recall_action(this)"><i class="fas fa-edit"></i></span><span class="button" id="remove" target="' . $id . '"  onclick="remove_recall(this)"><i class="fas fa-times"></i></span></div></div>';
		$data = '<div class="recall-data date"><span class="data-label">Date: </span><span class="data">'. $date . '</span></div><div class="recall-data name"><span class="data-label">Name: </span><span class="data">' . $name . '</span></div><div class="recall-data email"><span class="data-label">E-mail: </span><span class="data">' . $email . '</span></div><div class="recall-data text"><span class="data-label">Text: </span><span class="data">' . $text . '</span></div>';
		$response = '<div class="recall" id="' . $id . '">' . $panel . $data . '</div>';
		return $response;
	}

	if ((md5($_GET['password']) == $PASSWORD_MD5) or (md5($_POST['password']) == $PASSWORD_MD5))
	{
		$dom = new DOMDocument();
		$dom->encoding = "utf-8";
		if ($dom->load("recalls_admin_data.xml"))
		{
			if ($_GET['method'] == 'load') $response = load_recall($dom, $_GET['password']);
			if ($_POST['method'] == 'publish') $response = publish_recall($dom, $_POST['id'], $_POST['password']);
			if ($_POST['method'] == 'add') $response = add_recall($dom, $_POST['password'], $_POST['date'], $_POST['name'], $_POST['email'], $_POST['text']);
			if ($_POST['method'] == 'edit') $response = edit_recall($dom, $_POST['id'], $_POST['password'], $_POST['name'], $_POST['text']);
			if ($_POST['method'] == 'remove') $response = remove_recall($dom, 'id', $_POST['id'], $_POST['password']);
		}
		else
		{
			create_xml_document($dom);
			if ($_GET['method'] == 'load') $response = 'Recalls not found.';
		}
		$dom->save('recalls_admin_data.xml');
		echo $response;
	}
	else
		if ($_POST['method'] == 'add_client')
		{
			$dom = new DOMDocument();
			$dom->encoding = "utf-8";
			if ($dom->load("recalls_admin_data.xml"))
			{
				$response = add_recall($dom, FALSE, $_POST['date'], $_POST['name'], $_POST['email'], $_POST['text']);
			}
			else
			{
				$dom = create_xml_document($dom);
				add_recall($dom, FALSE, $_POST['date'], $_POST['name'], $_POST['email'], $_POST['text']);
			}
			$dom->save('recalls_admin_data.xml');
			echo $response;
		}
		else
			echo 'password error';
?>
	