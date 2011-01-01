<?
class page_client_status extends Page_Client {
	function init(){
		parent::init();


		$this->add('Button')->setLabel('Request Screen')
			->js('click')->univ()->dialogURL('New Screen Request',
					$this->api->getDestinationURL('client/screenrequest'));

		$l=$this->add('View_Screens');
		$this->limitTourrentProject($l);


	}

}
