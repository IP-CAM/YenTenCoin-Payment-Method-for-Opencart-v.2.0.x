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

    private $error = array();

    public function index() {

        // Load dependencies
        $this->load->model('setting/setting');
        $data = $this->load->language('payment/yentencoin');

        // Validate & save changes
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $this->model_setting_setting->editSetting('yentencoin', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));

        }

        // Display warnings if exists
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        // Build breadcrumbs
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/yentencoin', 'token=' . $this->session->data['token'], 'SSL')
        );

        // Form processing
        $data['action'] = $this->url->link('payment/yentencoin', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $this->load->model('localisation/currency');
        $data['currencies'] = $this->model_localisation_currency->getCurrencies();

        if (isset($this->request->post['yentencoin_user'])) {
            $data['yentencoin_user'] = $this->request->post['yentencoin_user'];
        } else {
            $data['yentencoin_user'] = $this->config->get('yentencoin_user');
        }

        if (isset($this->request->post['yentencoin_password'])) {
            $data['yentencoin_password'] = $this->request->post['yentencoin_password'];
        } else {
            $data['yentencoin_password'] = $this->config->get('yentencoin_password');
        }

        if (isset($this->request->post['yentencoin_host'])) {
            $data['yentencoin_host'] = $this->request->post['yentencoin_host'];
        } else if ($this->config->get('yentencoin_host')) {
            $data['yentencoin_host'] = $this->config->get('yentencoin_host');
        } else {
            $data['yentencoin_host'] = 'localhost';
        }

        if (isset($this->request->post['yentencoin_port'])) {
            $data['yentencoin_port'] = $this->request->post['yentencoin_port'];
        } else if ($this->config->get('yentencoin_port')) {
            $data['yentencoin_port'] = $this->config->get('yentencoin_port');
        } else {
            $data['yentencoin_port'] = 9982;
        }

        if (isset($this->request->post['yentencoin_path'])) {
            $data['yentencoin_path'] = $this->request->post['yentencoin_path'];
        } else {
            $data['yentencoin_path'] = $this->config->get('yentencoin_path');
        }

        if (isset($this->request->post['yentencoin_total'])) {
            $data['yentencoin_total'] = $this->request->post['yentencoin_total'];
        } else {
            $data['yentencoin_total'] = $this->config->get('yentencoin_total');
        }

        if (isset($this->request->post['yentencoin_qr'])) {
            $data['yentencoin_qr'] = $this->request->post['yentencoin_qr'];
        } else {
            $data['yentencoin_qr'] = $this->config->get('yentencoin_qr');
        }

        if (isset($this->request->post['yentencoin_currency'])) {
            $data['yentencoin_currency'] = $this->request->post['yentencoin_currency'];
        } else {
            $data['yentencoin_currency'] = $this->config->get('yentencoin_currency');
        }

        if (isset($this->request->post['yentencoin_order_status_id'])) {
            $data['yentencoin_order_status_id'] = $this->request->post['yentencoin_order_status_id'];
        } else {
            $data['yentencoin_order_status_id'] = $this->config->get('yentencoin_order_status_id');
        }

        if (isset($this->request->post['yentencoin_geo_zone_id'])) {
            $data['yentencoin_geo_zone_id'] = $this->request->post['yentencoin_geo_zone_id'];
        } else {
            $data['yentencoin_geo_zone_id'] = $this->config->get('yentencoin_geo_zone_id');
        }

        if (isset($this->request->post['yentencoin_status'])) {
            $data['yentencoin_status'] = $this->request->post['yentencoin_status'];
        } else {
            $data['yentencoin_status'] = $this->config->get('yentencoin_status');
        }

        if (isset($this->request->post['yentencoin_sort_order'])) {
            $data['yentencoin_sort_order'] = $this->request->post['yentencoin_sort_order'];
        } else {
            $data['yentencoin_sort_order'] = $this->config->get('yentencoin_sort_order');
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        // Load the template
        $this->response->setOutput($this->load->view('payment/yentencoin.tpl', $data));
    }

    protected function validate() {

        // Load dependencies
        $this->load->library('yentencoin');

        // Check permissions
        if (!$this->user->hasPermission('modify', 'payment/yentencoin')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        // Check connection
        $yentencoin = new YenTenCoin(
            $this->request->post['yentencoin_user'],
            $this->request->post['yentencoin_password'],
            $this->request->post['yentencoin_host'],
            $this->request->post['yentencoin_port'],
            $this->request->post['yentencoin_path']
        );

        if ($yentencoin->error) {
            $this->error['warning'] = sprintf($this->language->get('error_response'), $yentencoin->error);
        }

        return !$this->error;
    }
}
