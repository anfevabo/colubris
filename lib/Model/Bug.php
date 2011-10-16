<?php
/*
   When developer reports time, report is linked with all the timesheets which contribute to the time-spent on that report.
   Also reports are linked with acceptance records. Acceptance record signifies completion of requirement / task / model

   If task was completed properly, however several minor bugs were present, those bugs needs to be associated with this
   acceptance record.

   id
   acceptance_id  - optionally may be related to acceptance of certain feature

   */
class Model_Bug extends Model_Table {
	public $entity_code='acceptance';

	function defineFields(){
		parent::defineFields();


	}
}
