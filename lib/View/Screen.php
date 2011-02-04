<?php
class View_Screen extends View {
	function defaultTemplate(){
		return array('view/screen');
	}
	function setController($c){
		parent::setController($c);

		$file=$this->getController()->getRef('filestore_file_id');
		if($file->isInstanceLoaded()){
			$this->add('View_HtmlElement',null,'screenshot')
				->setElement('image')
				->setAttr('src',$file->getPath())->set(null)
				->setAttr('width','500');
		}
		$this->template->set($this->getController()->get());
	}
}
