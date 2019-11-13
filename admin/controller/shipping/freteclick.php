<?php
class ControllerShippingFreteclick extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/freteclick');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('freteclick', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_freteclick_key'] = $this->language->get('entry_freteclick_key');
		$data['entry_msg_prazo'] = $this->language->get('entry_msg_prazo');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/freteclick', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/freteclick', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['free_total'])) {
			$data['freteclick_total'] = $this->request->post['freteclick_total'];
		} else {
			$data['freteclick_total'] = $this->config->get('freteclick_total');
		}

		if (isset($this->request->post['free_geo_zone_id'])) {
			$data['freteclick_geo_zone_id'] = $this->request->post['freteclick_geo_zone_id'];
		} else {
			$data['freteclick_geo_zone_id'] = $this->config->get('freteclick_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['freteclick_status'])) {
			$data['freteclick_status'] = $this->request->post['freteclick_status'];
		} else {
			$data['freteclick_status'] = $this->config->get('freteclick_status');
		}

		if (isset($this->request->post['freteclick_sort_order'])) {
			$data['freteclick_sort_order'] = $this->request->post['freteclick_sort_order'];
		} else {
			$data['freteclick_sort_order'] = $this->config->get('freteclick_sort_order');
		}

		if (isset($this->request->post['freteclick_postcode'])) {
            $data['freteclick_postcode'] = $this->request->post['freteclick_postcode'];
        } else {
            $data['freteclick_postcode'] = $this->config->get('freteclick_postcode');
        }
        
        if (isset($this->request->post['freteclick_key'])) {
            $data['freteclick_key'] = $this->request->post['freteclick_key'];
        } else {
            $data['freteclick_key'] = $this->config->get('freteclick_key');
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// var_dump($data); exit;

		$this->response->setOutput($this->load->view('shipping/freteclick.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/freteclick')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}