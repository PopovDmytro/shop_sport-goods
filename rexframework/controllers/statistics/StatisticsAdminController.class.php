<?php

class StatisticsAdminController extends \RexFramework\ParentAdminController
{
    public static $assemble = 'shop.standart';
    public static $version = '1.0';
    public static $needed = array(
        'RexShop\OrderManager:shop.standart:1.0',
        'RexShop\OrderEntity:shop.standart:1.0',
        'RexFramework\ParentAdminController:standart:1.0',
    );

    protected $add_dialog_width = 800;
    protected $add_dialog_height = 424;
    protected $edit_dialog_width = 800;
    protected $edit_dialog_height = 424;

    public $dateFrom;
    public $dateTo;

    public function getDefault()
    {
        if (RexResponse::isRequest()) {
            RexResponse::init();

            $dateFrom   = Request::get('date_from', false);
            $this->dateFrom   = $dateFrom && strtotime($dateFrom) ? $dateFrom : false;
            $dateTo     = Request::get('date_to', false);
            $this->dateTo     = $dateTo && strtotime($dateTo) ? $dateTo : false;

            if (!$this->dateFrom || !$this->dateTo) {
                RexResponse::error('Не указан промежуток времени!');
            }

            if ($this->dateFrom > $this->dateTo) {
                RexResponse::error('Начальная дата меньше конечной!');
            }

            $orderManager = RexFactory::manager('order');
            $stats        = $orderManager->getStatsByDate($this->dateFrom.' 00:00:00', $this->dateTo.' 23:59:59');

            RexResponse::response(json_encode($stats));
        }

        $currDateRange = array(
            'date_from' => date('Y-m-01'),
            'date_to'   => date('Y-m-d')
        );

        RexDisplay::assign('currDateRange', $currDateRange);
        RexPage::setTitle('Статистика по заказам');
    }

    public function getPeriodOrders() {
        $dateFrom        = Request::get('date_from', false);
        $this->dateFrom  = $dateFrom && strtotime($dateFrom) ? $dateFrom : false;
        $dateTo          = Request::get('date_to', false);
        $this->dateTo    = $dateTo && strtotime($dateTo) ? $dateTo : false;

        $orderManager = RexFactory::manager('order');

        $this->entity   = new OrderEntity();
        $this->manager  = $orderManager;
        $this->fields = array (
            array('Информация по заказам за период', array($this, '_DGContent'))
        );

        parent::getDefault();

        $totals = $this->manager->getTotalsByPeriod( $this->dateFrom.' 00:00:00', $this->dateTo.' 23:59:59' );
        $dg = RexDisplay::getVar('dg');

        if ( isset($totals[0]['sale_price'], $totals[0]['net_profit']) ) {
            $totals_block = "<div class='totals-block' >
                                <table class='data'>
                                    <tr class='Odd'>
                                        <td><b>Итого: </b></td>
                                        <td><b class='red' >{$totals[0]['sale_price']}</b></td>
                                        <td><b class='red' >{$totals[0]['net_profit']}</b></td>
                                    </tr>
                                </table>
                            </div>";
        }
        RexResponse::response( array( 'dg' => $dg, 'totals' => isset($totals_block) ? $totals_block : '' ) );
    }

    protected function _getData($filters, $fields){
        return $this->manager->getOrdersInfo($filters, $fields, $this->dateFrom.' 00:00:00', $this->dateTo.' 23:59:59' );
    }

    protected function _getFields($fields){
        return $this->fields;
    }

    function _DGContent($param) {
        RexDisplay::assign('order', $param['record']);
        return RexDisplay::fetch('statistics/order_item.content.inc.tpl');
    }
}