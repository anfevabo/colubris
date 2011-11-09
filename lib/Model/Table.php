<?php
class Model_Table extends Model_MVCTable {
    function init(){
        parent::init();
        $this->setOrder(null,'id',true);
    }
    function selectQuery(){
        return $this->dsql();
    }
}
