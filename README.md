Установка
---------

* Открываем в админ панели раздел "Расширения" -> "Менеджер расширений"
* Выбираем архив и жмем "Загрузить и установить"

Настройка
---------

* Открываем в админ панели раздел "Расширения" -> "Менеджер модулей"
* Если в списке модулей нет модуля "mod_rees46", нажимаем кнопку "Создать", либо находим модуль "mod_rees46" и активируем его в колонке "Состояние"
* Заходим в сам модуль (нажимаем на ссылку в имени)
* В полях "Показывать заголовок" выбираем "Скрыть", "Позиция" выбираем "position-3", "Привязка модуля" -> "На всех страницах"
* В поле "SHOP ID" и "SHOP SECRET" вбиваем данные полученные на сайте rees46
* Нажимаем "Сохранить и закрыть"

Отслеживание событий
--------------------

* Находим файл `/components/com_virtuemart/assets/js/vmprices.js`, находим функцию `cartEffect` и в `callback` функцию добавления в корзину добавляем необходимый код:

		$.getJSON(vmSiteurl+'index.php?option=com_virtuemart&nosef=1&view=cart&task=addJS&format=json'+vmLang,datas,
		function(datas, textStatus) {
			if(datas.stat ==1){

				//Вот этот код нужно добавить -->
				REES46.pushData('cart', {
					item_id: form.find('input[name="Itemid"]').val(),
					price: parseFloat(jQuery('span.PricesalesPrice').text()),
					is_available: '1'
				});
				//---->

				var txt = datas.msg;
			} else if(datas.stat ==2){
			
* Находим файл `/components/com_virtuemart/helpers/cart.php`, находим функцию `removeProductCart` и перед стрчкой `$this->setCartIntoSession();` добавляем следующий код:

		//REES46
		setcookie("rees46_track_remove_from_cart", json_encode(array(
			"item_id" => $prod_id
		)), time() + 86400, "/");
		
* В этом же файле `cart.php` находим функцию `confirmedOrder` и после строчки `$returnValues = $dispatcher->trigger('plgVmConfirmedOrder', array($this, $orderDetails));` добавляем следующий код:

		//Rees46
		$rees46_items = array();
		foreach($orderDetails['items'] as $product) {
			$rees46_items[] = array(
				"item_id" => $product->virtuemart_product_id,
				"amount" => $product->product_quantity
			);
		}
		setcookie("rees46_track_purchase", json_encode(array(
			"items" => $rees46_items,
			"order_id" => $orderDetails['details']['BT']->order_number
		)), time() + 86400, "/");
