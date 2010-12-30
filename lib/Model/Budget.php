<?
class Model_Budget extends Model_Table {
	public $entity_code='budget';
	public $table_alias='B';


	function defineFields(){
		parent::defineFields();

		$this->debug();

		$this->newField('name');
		$this->newField('amount');
		$this->newField('amount_currency')
			->datatype('list')
			->listData(array(
						'usd'=>'USD',
						'eur'=>'EUR',
						'hours'=>'Man-hours',
						'days'=>'Man-days'
						));

		$this->newField('client_id')
			->refModel('Model_Client');

	}
}
