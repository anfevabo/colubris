<?php
class Model_Table extends Model_MVCTable {
    function selectQuery(){
        return $this->dsql();
    }
}
