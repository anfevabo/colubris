<?php
class Model_Interafce extends Model_Table {
    public $entity_code = "estimate_interface";
    function init(){
        parent::init();
        $this->addField("name")
            ->datatype("string");
        $this->addField("estimate_id")
            ->datatype("int")->refModel("Model_estimate");
        $this->addField("estimate_hours")
            ->datatype("int");
        $this->addField("description")
            ->datatype("text");

    }

}
