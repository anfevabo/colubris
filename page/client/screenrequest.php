<?
class page_client_screenrequest extends Page {
	function init(){
		parent::init();
		$info=$this->add('HtmlElement');
		$info->add('HtmlElement')->setElement('h1')->set('Step 1 - Information');

		$info->add('HtmlElement')->setElement('p')->set('It is possible that current implementation is missing an important screen. Through this
				form you can request this screen to be added to your project. You would need to select appropriate budget');

		$info->add('HtmlElement')->setElement('p')->set('Once we receive your request, we will break down your description into more "developer
				friendly" descriptions and will add it to list of your screens');

		$continue_button=$info->add('Button');





		$f=$this->add('Form');

		$f->js(true)->hide();
		$continue_button->js('click',array(
					$info->js()->hide(),
					$f->js()->show()
					));

		$f->add('View_Hint',null,'hint')->set('Fill as many fields as necessary')->js(true)->css('float','right');

		$f->addField('line','name','Give name to new screen')->validateNotNULL();
		$f->addField('reference','budget_id','Budget')->setController('Controller_Budget')->validateNotNULL();
		$f->addField('text','descr','Explain what new screen is supposed to do');
	}
}
?>
