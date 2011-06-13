<?

/*
  Commonly you would want to re-define ApiFrontend for your own application.
 */

class Colubris extends ApiFrontend {

    function init() {
        parent::init();

        // Keep this if you are going to use database
        $this->dbConnect();

        // Keep this if you are going to use plug-ins
        $this->addLocation('atk4-addons', array(
                    'php' => array('mvc',
                        'misc/lib',
                    )
                ))
                ->setParent($this->pathfinder->base_location);

        // Keep this if you will use jQuery UI in your project
        $this->add('jUI');

        // Initialize any system-wide javascript libraries here
        $this->js()
                ->_load('atk4_univ')
                ->_load('ui.atk4_notify')

        ;

        // Before going further you will need to verify access
        $auth = $this->add('SQLAuth');
        $auth->setSource('user', 'email', 'password')->field('id,name,is_admin');
        $auth->usePasswordEncryption('md5');
        $auth->allowPage('minco');
        if (!$auth->isPageAllowed($this->api->page)

            )$auth->check();

        // Alternatively
        // $this->add('MVCAuth')->setController('Controller_User')->check();


        $this->initLayout();
    }

    function initLayout() {

        $this->template->tryDel('fullscreen');
        // If you are using a complex menu, you can re-define
        // it and place in a separate class


        if ($this->page == 'minco'

            )return parent::initLayout();

        $m = $this->add('Menu', 'Menu', 'Menu');
        $u = $this->getUser();

        // Determine page first

        $p = explode('_', $this->page);
        switch ($p[0]) {
            case 'client':
                if (!$u->get('is_client') && !$u->get('is_admin')) {
                    $this->api->redirect('/');
                }

                $m->addMenuItem('Welcome', 'client/welcome');
                $m->addMenuItem('Budgets', 'client/budgets');
                $m->addMenuItem('Project Status', 'client/status');
                if($u->get('is_timereport')){
                $m->addMenuItem('Time Reports', 'client/timesheets');
                }
                break;

            case 'team':
                if (!$u->get('is_developer') && !$u->get('is_admin')) {
                    $this->api->redirect('/');
                }

                $m->addMenuItem('Welcome', 'team');
                $m->addMenuItem('Time Entry', 'team/entry');
                //$m->addMenuItem('Development Priorities','team/timesheets');
                // TODO:
                 $m->addMenuItem('Timesheets', 'team/timesheets');
                $m->addMenuItem('Budgets', 'team/budgets');
                break;

            case 'manager':
                if (!$u->get('is_manager') && !$u->get('is_admin')) {
                    $this->api->redirect('/');
                }
                $m->addMenuItem('Home', 'manager');
                $m->addMenuItem('Statistics', 'manager/statistics');
                $m->addMenuItem('Reports', 'manager/reports'); // review all reports in system - temporary
                $m->addMenuItem('Timesheets', 'manager/timesheets'); // review all reports in system - temporary

                $m->addMenuItem('Tasks', 'manager/tasks'); // review all tasks in system - temporary
                $m->addMenuItem('Requirements', 'manager/req'); // PM can define project requirements here and view tasks
                $m->addMenuItem('Budgets', 'manager/budgets'); // Admin can setup projects and users here
                $m->addMenuItem('Projects', 'manager/projects'); // Admin can setup projects and users here
                $m->addMenuItem('Clients', 'manager/clients');
                break;
            case 'admin':
                if (!$u->get('is_admin')) {
                    $this->api->redirect('/');
                }
                $m->addMenuItem('Users', 'admin/users');
                $m->addMenuItem('Developers', 'admin/developers');
                $m->addMenuItem('Files', 'admin/filestore');
                break;

            default:
                $m->addMenuItem('Introduction', 'intro');

                if ($u->get('is_manager') || $u->get('is_admin')) {
                    $m->addMenuItem('Manager', 'manager');
                }
                if ($u->get('is_developer') || $u->get('is_admin')) {
                    $m->addMenuItem('Developer', 'team');
                }
                if ($u->get('is_client') || $u->get('is_admin')) {
                    $m->addMenuItem('Client', 'client');
                }
                if ($u->get('is_admin')) {
                    $m->addMenuItem('Admin', 'admin');
                }

                if (!$u->get('is_client')) {
                    $m->addMenuItem('About Colubris', 'about');
                }
                $m->addMenuItem('account');

                break;
        }
        if (!$u->get('is_client')) {
            $m->addMenuItem('Main Menu', '/');
        }
        $m->addMenuItem('logout');

        // HTML element contains a button and a text
        $sc = $this->add('HtmlElement', null, 'name')
                        ->setElement('div')
                        ->setStyle('width', '700px')
                        ->setStyle('text-align', 'right');

        // Button have 2 events. Click event opens the frame, reload event is a custom event
        // which would reload parent
        $b = $sc->add('Button')
                        ->setStyle('float', 'right')
                        ->setStyle('margin-left', '1em')
                        ->set('Change Scope');
        $b->js('click')->univ()->dialogURL('Change Scope', $this->api->getDestinationURL('/scope'));
        $b->js('my_reload', $sc->js()->univ()->page($this->api->getDestinationURL()));


        $sc->add('Text')
                ->set(
                        $this->api->auth->get('name') . ' @ ' .
                        'Colubris Team Manager v' . $this->getVersion() . '<br/>' . $this->getScope());



        parent::initLayout();
    }

    function upgradeChecker() {

    }

    function page_index($p) {
        
        $u = $this->getUser();

        if($u->get('is_client')){
            $this->api->redirect('client/budgets');
        }
        else{
            $this->api->redirect('intro');
        }
    }

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
        $u = $this->getUser();
        if ($u->get('is_client')) {
            $sc['client'] = $u->get('id');
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

    function getUser() {
        return $this->add('Model_User')->loadData($this->getUserID());
    }

    function getUserID() {
        return $this->api->auth->get('id');
    }

    function isManager() {
        return $this->getUser()->get('is_manager');
    }

    function isDeveloper() {
        return $this->getUser()->get('is_developer');
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

    function page_pref($p) {

        // This is example of how you can use form with MVC support
        $p->frame('Preferences')->add('MVCForm')
                ->setController('Controller_User');
    }

}
