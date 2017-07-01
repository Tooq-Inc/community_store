<?php
defined('C5_EXECUTE') or die("Access Denied.");
use Concrete\Package\CommunityStore\Src\CommunityStore\Utilities\Price as StorePrice;
use Concrete\Package\CommunityStore\Src\Attribute\Key\StoreOrderKey as StoreOrderKey;
use Concrete\Package\CommunityStore\Src\CommunityStore\Customer\Customer as StoreCustomer;

$dh = Core::make('helper/date');

$subject = t("Order Status Notification for Order #%s", $order->getOrderID());
/**
 * HTML BODY START
 */
ob_start();

?>
    <!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
    <html>
    <head>
    </head>
    <body>
    <h2><?= t('Your order status has changed!') ?></h2>

    <p><strong><?= t("Order") ?>#:</strong> <?= $order->getOrderID() ?></p>
    <p><?= t('Order placed');?>: <?= $dh->formatDateTime($order->getOrderDate())?></p>
    <p><strong><?= t("Previous Status") ?>:</strong> <?= $orderHistory[1]->getOrderStatusName(); ?></p>
    <p><strong><?= t("New Status") ?>:</strong> <?= $orderHistory[0]->getOrderStatusName(); ?></p>

    <h3><?= t('Order Details') ?></h3>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th style="border-bottom: 1px solid #aaa; text-align: left; padding-right: 10px;"><?= t('Product Name') ?></th>
            <th style="border-bottom: 1px solid #aaa; text-align: left; padding-right: 10px;"><?= t('Options') ?></th>
            <th style="border-bottom: 1px solid #aaa; text-align: left; padding-right: 10px;"><?= t('Qty') ?></th>
            <th style="border-bottom: 1px solid #aaa; text-align: left; padding-right: 10px;"><?= t('Price') ?></th>
            <th style="border-bottom: 1px solid #aaa; text-align: left;"><?= t('Subtotal') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $items = $order->getOrderItems();
        if ($items) {
            foreach ($items as $item) {
                ?>
                <tr>
                    <td style="vertical-align: top; padding: 5px 10px 5px 0"><?= $item->getProductName() ?>
                        <?php if ($sku = $item->getSKU()) {
                            echo '(' . $sku . ')';
                        } ?>
                    </td>
                    <td style="vertical-align: top; padding: 5px 10px 5px 0;">
                        <?php
                        $options = $item->getProductOptions();
                        if ($options) {
                            $optionOutput = array();
                            foreach ($options as $option) {
                                $optionOutput[] =  "<strong>" . $option['oioKey'] . ": </strong>" . ($option['oioValue'] ? $option['oioValue'] : '<em>' . t('None') . '</em>');
                            }
                            echo implode('<br>', $optionOutput);
                        }
                        ?>
                    </td>
                    <td style="vertical-align: top; padding: 5px 10px 5px 0;"><?= $item->getQty() ?></td>
                    <td style="vertical-align: top; padding: 5px 10px 5px 0;"><?= StorePrice::format($item->getPricePaid()) ?></td>
                    <td style="vertical-align: top; padding: 5px 0 5px 0;"><?= StorePrice::format($item->getSubTotal()) ?></td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>

    <p><a href="<?= \URL::to('/dashboard/store/orders/order/'. $order->getOrderID());?>"><?=t('View this order within the Dashboard');?></a></p>

    </body>
    </html>

<?php
$bodyHTML = ob_get_clean();
/**
 * HTML BODY END
 *
 * ======================
 *
 * PLAIN TEXT BODY START
 */
ob_start();

?>

<?= t("Order Status Notification for Order #:") ?> <?= $order->getOrderID() ?>
<?= t("Your order status was updated.") ?>
<?php

$body = ob_get_clean(); ?>