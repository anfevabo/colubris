<?

/*
  Commonly you would want to re-define ApiFrontend for your own application.
 */

class Colubris extends ApiFrontend {

    function init() {
        parent::init();

        $this->pathfinder->addLocation('.',array(
            'addons'=>array('atk4-addons','addons'),
            'php'=>array('atk4-addons/misc/lib'),
        ))->setParent($this->pathfinder->base_location);

        $this->dbConnect();

        $this->add('jUI');
        
        // Initialize any system-wide javascript libraries here
        
        $this->js()
                ->_load('atk4_univ')
                ->_load('ui.atk4_notify')
        ;


        $this->formatter=$this->add('Controller_Formatter');

        // Before going further you will need to verify access
        //$auth = $this->add('SQLAuth');
        $this->add('Auth')
            ->usePasswordEncryption('md5')
            ->setModel('Model_User', 'email', 'password')
        ;
        /*
        $auth->setSource('user', 'email', 'password')->field('id,name,is_admin');
        $auth->usePasswordEncryption('md5');*/
        $this->auth->allowPage('minco');


        if( (isset($_REQUEST['id'])) && (isset($_REQUEST['hash'])) ){
            $u=$this->add('Model_User')->load($_GET["id"]);
            if($u['hash']!=$_GET["hash"]){
                echo json_encode("Wrong user hash");
                $this->logVar('wrong user hash: '.$v['hash']);
                exit;
            }

            unset($u['password']);
            $this->api->auth->addInfo($u);
            $this->api->auth->login($u['email']);
        }



        $this->auth->allowPage('index');
        if (!$this->auth->isPageAllowed($this->api->page))$this->auth->check();

        // Alternatively
        // $this->add('MVCAuth')->setController('Controller_User')->check();
    }

    function initLayout() {

        $this->template->tryDel('fullscreen');
        // If you are using a complex menu, you can re-define
        // it and place in a separate class


//        $this->template->append('logo','<h2 style="float: left">Beta</h2>');

        if ($this->page == 'minco'

            )return parent::initLayout();

        $m = $this->add('Menu', 'Menu', 'Menu');

        // Determine page first

        $p = explode('_', $this->page);
        switch ($p[0]) {
            case 'client':
                if (!$this->api->auth->model['is_client'] && !$this->api->auth->model['is_admin']) {
                    $this->api->redirect('/');
                }

                $m->addMenuItem('client/welcome','Welcome');
                $m->addMenuItem('client/budgets','Budgets');
                //$m->addMenuItem('client/status','Project Status');
                if($this->api->auth->model['is_timereport']){
                $m->addMenuItem('client/timesheets','Time Reports');
                }
                break;

            case 'team':
                if (!$this->api->auth->model['is_developer'] && !$this->api->auth->model['is_admin']) {
                    $this->api->redirect('/');
                }

                $m->addMenuItem('team','Welcome');
                $m->addMenuItem('team/entry','Time Entry');
                //$m->addMenuItem('team/timesheets','Development Priorities');
                // TODO:
                 $m->addMenuItem('team/timesheets','Timesheets');
                $m->addMenuItem('team/budgets','Budgets');
                break;

            case 'manager':
                if (!$this->api->auth->model['is_manager'] && !$this->api->auth->model['is_admin']) {
                    $this->api->redirect('/');
                }
                $m->addMenuItem('manager','Home');
                $m->addMenuItem('manager/rfq','Request For Quotation');
                $m->addMenuItem('manager/statistics','Statistics');
                $m->addMenuItem('manager/reports','Reports'); // review all reports in system - temporary
                $m->addMenuItem('manager/timesheets','Timesheets'); // review all reports in system - temporary

                $m->addMenuItem('manager/tasks','Tasks'); // review all tasks in system - temporary
                $m->addMenuItem('manager/req','Requirements'); // PM can define project requirements here and view tasks
                $m->addMenuItem('manager/budgets','Budgets'); // Admin can setup projects and users here
                $m->addMenuItem('manager/projects','Projects'); // Admin can setup projects and users here
                $m->addMenuItem('manager/clients','Clients');
                break;
            case 'admin':
                if (!$this->api->auth->model['is_admin']) {
                    $this->api->redirect('/');
                }
                $m->addMenuItem('admin/users','Users');
                $m->addMenuItem('admin/developers','Developers');
                $m->addMenuItem('admin/filestore','Files');
                break;

            default:
                $m->addMenuItem('intro','Introduction');

                if(!$this->auth->isLoggedIn()){
                    break;
                }

                if ($this->api->auth->model['is_manager'] || $this->api->auth->model['is_admin']) {
                    $m->addMenuItem('manager','Manager');
                }
                if ($this->api->auth->model['is_developer'] || $this->api->auth->model['is_admin']) {
                    $m->addMenuItem('team','Developer');
                }
                if ($this->api->auth->model['is_client'] || $this->api->auth->model['is_admin']) {
                    $m->addMenuItem('client','Client');
                }
                if ($this->api->auth->model['is_admin']) {
                    $m->addMenuItem('admin/users','Admin');
                }

                if (!$this->api->auth->model['is_client']) {
                    $m->addMenuItem('about','About Colubris');
                }
                $m->addMenuItem('account');

                break;
        }
        
        if ($this->auth->isLoggedIn() && !$this->api->auth->model['is_client']) {
            $m->addMenuItem('/','Main Menu');
        }
        $m->addMenuItem('logout');

        // HTML element contains a button and a text
        $sc = $this->add('HtmlElement', null, 'name')
                        ->setElement('div')
                        ->setStyle('width', '700px')
                        ->setStyle('text-align', 'right');



        $sc->add('Text')
                ->set(
                        $this->api->auth->model['name'] . ' @ ' .
                        'Colubris Team Manager v' . $this->getVersion());// . $this->getScope());



        parent::initLayout();
    }

    function upgradeChecker() {

    }

    /*
    function page_index($p) {
        
        $u = $this->getUser();

        if($this->api->auth->model['is_client')){
            $this->api->redirect('client/budgets');
        }
        else{
            $this->api->redirect('intro');
        }
    }
     */

    function page_scope($p) {
        $f = $p->add('Form');


        $f->addField('reference', 'project')
                ->emptyValue('All Projects')
                ->setModel('Project');
        //->setValueList($f->add('Model_Project'));

        $f->addField('reference', 'budget')
                ->emptyValue('All Budgets')
                ->setModel('Budget');
        //->setValueList($f->add('Model_Budget'));

        $f->addField('reference', 'client')
                ->emptyValue('All Clients')
                ->setModel('Client');
        //->setValueList($f->add('Model_Client'));

        $f->addField('reference', 'user')
                ->emptyValue('All Users')
                ->setModel('User');
        //->setValueList($f->add('Model_User'));

        $f->set($this->recall('scope', array()));

        if ($f->isSubmitted()) {


            $this->memorize('scope', $f->getAllData());

            /*
            if($f->get('client')){
                // calculate budgets and projects
                $pr=$this->add('Model_Project')->addCondition('client_id',$f->get('client'))
                    ->dsql()->field('id')->do_getColumn();

                $this->setScope('project',$pr);

                $bu=$this->add('Model_Budget')->addCondition('project_id',$pr)
                    ->dsql()->field('id')->do_getColumn();
                $this->setScope('budget',$bu);


            }

            */


            $f->js()->univ()->closeDialog()->getjQuery()->trigger('my_reload')->execute();
        }
    }

    function setScope($key, $val=null) {
        $sc = $this->recall('scope', array());
        if ($val

            )$sc[$key] = $val;else
            unset($sc[$key]);
        $this->memorize('scope', $sc);
        return $this;
    }

    function getScope() {
        $sc = $this->recall('scope', array());
        if ($this->api->auth->model['is_client']) {
            $sc['client'] = $this->api->auth->model['id'];
        }
        $t = array();
        foreach ($sc as $key => $val) {
            if (!$val

                )continue;
            try {
                switch ($key) {
                    case'project':
                        $t[] = 'Project: ' . $this->add('Model_Project')->loadData($val)->get('name');
                        break;
                    case'client':
                        $t[] = 'Client: ' . $this->add('Model_Client')->loadData($val)->get('name');
                        break;
                    case'budget':
                        $t[] = 'Budget: ' . $this->add('Model_Budget')->loadData($val)->get('name');
                        break;
                    case'user':
                        $t[] = 'User: ' . $this->add('Model_User')->loadData($val)->get('name');
                        break;
                }
            } catch (Exception_InstanceNotLoaded $e) {
                // Entry was deleted or no longer available
                $sc[$key] = null;
                $this->memorize('scope', $sc);
                $t[] = '<font color=red>' . $key . ' was reset</font>';
            }
        }
        return join(', ', $t);
    }

    function getVersion() {
        return '0.2';
    }

    function getCurrentTask() {
        $task_id = $this->api->recall('task', null);
        if (!$task_id

            )return null;

        $t = $this->add('Controller_Task')->loadData($task_id);
        if (!$t->isInstanceLoaded()) {
            $this->forget('task');
            return null;
        }
        return $t;
    }

    function getClient(){
        // returns model for currently logged in client. If user is manager, he can select which client he's willing to be

        $c=$this->add('Model_Client');
        return $c;


    }

    function page_pref($p) {

        // This is example of how you can use form with MVC support
        $p->frame('Preferences')->add('Form')
                ->setController('Controller_User');
    }

}
