<?php
class Model_building_menu extends Model
{
	public function get_data($bid) {		
		$data = array(
				array ('Name' => 'Объявления', 'Url' => '/building/news'.$bid,'Icon' => 'news'),
				array ('Name' => 'Паспорт', 'Url' => '/building/passport'.$bid,'Icon' => 'passport'),
				array ('Name' => 'Заявки', 'Url' => '/building/laments'.$bid,'Icon' => 'lament'),		
				array ('Name' => 'Услуги', 'Url' => '/building/services'.$bid,'Icon' => 'service'),
				array ('Name' => 'Приборы учёта', 'Url' => '/building/emelements'.$bid,'Icon' => 'graph'));
		return $data;		
	}
}
?>