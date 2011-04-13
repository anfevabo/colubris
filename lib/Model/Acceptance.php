<?php
/*
   When developer reports time, report is linked with all the timesheets which contribute to the time-spent on that report.
   Also reports are linked with acceptance records. Acceptance record signifies completion of requirement / task / model

   Acceptance needs to be verified through Q/A process

    report_id
    task_id = which task is completed

    status = "pending", "buggy" or "incomplete"
    tester = user who tested this task's completion

   If task was completed properly, however several minor bugs were present, those bugs needs to be associated with this
   acceptance record.

   */
class Model_Acceptance extends Model_Table {
	public $entity_code='acceptance';
	public $table_alias='acc';

	function defineFields(){
		parent::defineFields();


	}
}
