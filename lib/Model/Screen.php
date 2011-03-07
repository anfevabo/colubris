<?
class Model_Screen extends Model_Requirement {

	function defineFields(){
		parent::defineFields();

		$this->addField('filestore_file_id')
			->refModel('Model_Filestore_File')
            ->caption('Wireframe')
			->datatype('image');

        $this->setMasterField('type','screen');
	}
	function scopeDefault(){
		if($sc=$this->api->recall('scope')){
			if($sc['client'])$dsql->where('client_id',$sc['client']);
		}
	}
}
