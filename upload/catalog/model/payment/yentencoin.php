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

class ModelPaymentYenTenCoin extends Model {

    public function getMethod($address, $total) {

        // Load dependencies
        $this->load->library('yentencoin');
        $this->load->language('payment/yentencoin');

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

            // Block this payment gateway if connection failed
            return false;
        }

        // Get active Geo-Zones
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('yentencoin_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        // Check for order total
        if ($this->config->get('yentencoin_total') > 0 && $this->config->get('yentencoin_total') > $total) {
            $status = false;

        // Check for Geo-Zone
        } elseif (!$this->config->get('yentencoin_geo_zone_id')) {
            $status = true;
        } elseif ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }

        // Add YenTenCoin Payment Option to the Order Form
        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'yentencoin',
                'title'      => $this->language->get('text_title'),
                'terms'      => '',
                'sort_order' => $this->config->get('yentencoin_sort_order')
            );
        }

        return $method_data;
    }
}
