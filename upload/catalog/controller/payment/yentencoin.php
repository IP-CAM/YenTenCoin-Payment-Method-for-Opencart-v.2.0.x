<?php

/**
 * LICENSE
 *
 * This source file is subject to the GNU General Public License, Version 3
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @category   OpenCart
 * @package    Bitcoin Payment for OpenCart
 * @copyright  Copyright (c) 2015 Eugene Lifescale (a.k.a. Shaman) by OpenCart Ukrainian Community (http://opencart-ukraine.tumblr.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License, Version 3
 */

class ControllerPaymentYenTenCoin extends Controller {

	private $_yentencoin;
	private $address;

    public function __construct($registry) {

        parent::__construct($registry);

        // Load dependencies
        $this->load->language('payment/yentencoin');
        $this->load->library('yentencoin');
        $this->load->model('checkout/order');

        // Connect to the server
        $this->_yentencoin = new YenTenCoin(
            $this->config->get('yentencoin_user'),
            $this->config->get('yentencoin_password'),
            $this->config->get('yentencoin_host'),
            $this->config->get('yentencoin_port'),
            $this->config->get('yentencoin_path')
        );

        // Check for errors
        if ($this->_yentencoin->error) {

            // Save errors to the log
            $log = new Log('yentencoin.log');
            $log->write($this->_yentencoin->error);

            // Force exit
            exit;
        }
    }

    public function index() {

        // Create invoice
        $data['text_instruction'] = $this->language->get('text_instruction');
        $data['text_loading']     = $this->language->get('text_loading');
        $data['text_description'] = sprintf($this->language->get('text_description'),
                                            $this->currency->format($this->cart->getTotal(),
                                            $this->config->get('yentencoin_currency')));

        $data['button_confirm']   = $this->language->get('button_confirm');
        $data['continue']         = $this->url->link('checkout/success');
	$data['address']          = $this->_yentencoin->getnewaddress((string)$this->config->get('yentencoin_user'));
	$this->address = $data['address'];
	$this->session->data['address'] = $data['address'];

        // Load QR code if enabled
        if ($data['address']) {
            switch ($this->config->get('yentencoin_qr')) {

                // Google API
                case 'google':
                    $data['qr'] = 'https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl=' . $data['address'];
                    break;

                // QR is disabled
                default:
                    $data['qr'] = false;
            }
        }

        // Load the template
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/yentencoin.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/yentencoin.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/yentencoin.tpl', $data);
        }
    }

    public function confirm() {

        // Confirm an order if payment gateway is YenTenCoin
        if ($this->session->data['payment_method']['code'] == 'yentencoin') {
            $this->model_checkout_order->addOrderHistory(
                $this->session->data['order_id'],
                $this->config->get('yentencoin_order_status_id'),

		// Save YenTenCoin Address to the Order History
                sprintf($this->language->get('text_yentencoin_address'),$this->session->data['address']),true);
                        //$this->_yentencoin->getaccountaddress((string)$this->session->data['order_id'])),
                //true
            //);
        }
    }
}
