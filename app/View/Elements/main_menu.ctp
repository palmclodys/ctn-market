<?php
	$menu_nav = '';
	foreach($menu_items as $k => $v):
		$menu_nav .= '<li class="dropdown">';
		if (is_array($v)) {
			$nav_item_array = array_keys($v);
			$nav_item_name = array_shift($nav_item_array);
			if(!empty($v[$nav_item_name])) {
				$menu_nav .= '<a href="#" id="drop2" role="button" class="dropdown-toggle" data-toggle="dropdown">' . $nav_item_name . ' <b class="caret"></b></a>';

				$menu_nav .=  '<ul class="dropdown-menu" role="menu" aria-labelledby="drop2">';
				foreach($v[$nav_item_name] as $sub_k => $sub_v):

					$menu_nav .= '<li role="presentation">' . $this->Html->link(__d('categories', $sub_v['name']), $sub_v['link']) . '<li>';

				endforeach;
				$menu_nav .=  '</ul>';
			}
		}

		$menu_nav .= '</li>';
	endforeach;
	
	echo ($menu_nav);
?>

