<?php
/**
 * User: nixx
 * Date: 12.11.14
 * Time: 18:29
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
header("Content-Type: text/html; charset=utf-8");

//Init Joomla
define('_JEXEC', 1);
define('JPATH_BASE', dirname(__FILE__) . '/../..' );
define('DS', DIRECTORY_SEPARATOR);
require_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
require_once(JPATH_BASE.DS.'includes'.DS.'framework.php');
JFactory::getApplication('site')->initialise();

//Init VirtueMart
if (!class_exists( 'VmConfig' )) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart'.DS.'helpers'.DS.'config.php');
if (!class_exists('VmImage')) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'image.php'); //dont remove that file it is actually in every view except the state view

jimport('joomla.application.module.helper'); //подключили класс
$module = JModuleHelper::getModule('rees46');
$params = new JRegistry();
$params->loadString($module->params);

//Ajax code

/**
 * @var VirtueMartModelProduct $productModel
 */
$productModel = VmModel::getModel('product');
if (!class_exists ('CurrencyDisplay')) {
	require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'currencydisplay.php');
}
$currency = CurrencyDisplay::getInstance ();

if( JRequest::getString("id") ) {
	?>
	<div class="rees46-recommend">
		<div class="recommender-block-title"></div>
		<div class="recommended-items">
		<?
			$ids = explode(",", JRequest::getString("id"));
			foreach( $ids as $id ) {
				$product = $productModel->getProduct($id,TRUE,TRUE,TRUE,1);
				$productModel->addImages($product);
				if( $product ) {
					?>
					<div class="recommended-item">
						<? if( $product->images ): ?>
						<div class="recommended-item-photo">
							<a href="<?= str_ireplace("/modules/mod_rees46", "", $product->link) ?>">
								<img class="item_img" src="/<?= $product->images[0]->getThumbUrl() ?>" alt="<?= $product->product_name ?>">
							</a>
						</div>
						<? endif ?>
						<div class="recommended-item-title">
							<a href="<?= str_ireplace("/modules/mod_rees46", "", $product->link) ?>"><?= $product->product_name ?></a>
						</div>
						<div class="recommended-item-price"><?= $currency->createPriceDiv('salesPrice', '', $product->prices, TRUE); ?></div>
						<div class="recommended-item-action">
							<a href="<?= str_ireplace("/modules/mod_rees46", "", $product->link) ?>">Подробнее</a>
						</div>
					</div>
					<?
				} elseif( $params->get('rees46_shop_secret', '') && $params->get('rees46_shop_id', '') ) {
					?>
					<script>jQuery.get('http://api.rees46.com/import/disable?shop_id=<?= $params->get('rees46_shop_id', '') ?>&shop_secret=<?= $params->get('rees46_shop_secret', '') ?>&item_ids=<?= $id ?>')</script>
				<?
				}
			}
		?>
		</div>
	</div>
<?
}
