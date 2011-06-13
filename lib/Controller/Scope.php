<?php
class Controller_Scope extends AbstractController {
    /*
       Scope controller allows developer to define criterias which
       are then applied to certain models. Redefine this class to
       create dependences between setting scopes. For example, when
       user have access only to certain projects and the user is set
       implicitly, then set of projects should be narrowed down also.
       */

    public $cr=array();


    function init(){
        parent::init();
        $this->api->scope=$this;
    }
    function set($key,$val=null){
        if(is_array($key))throw $this->exception('Use setAll()');

        if(!is_array($val))$val=array($val);
        $this->cr[$key]=$val;
        $this->setDependencies($key);
    }

    function setDependencies($key){
        // redefine this function for your own dependencies
    }

    function get($key){
        return $this->cr[$key];
    }

    function getValues($key){
    }
}
