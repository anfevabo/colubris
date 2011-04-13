<?php
/*
   Given the budget, and perhaps time value, this model will return list of all developers and some curious columns:

    id
    name = name of developer
    developer_id = id of developer

    time_reported = hours reported
    time_reported_pct = percentage from total budget
    ( this data is based on Reports )

    progress_reported = hours (based on estimate) completed
    progress_reported_pct = percentage from budget estimate
    ( this data is based on Acceptance )

    new_bugs = amount of new bugs introduced
    bugs_closed = amount of bugs close




     
   */
class Model_ParticipantProgress extends Model_Participant {

	function defineFields(){
		parent::defineFields();

		// Each field can have a varietty of properties. Please
		// referr to FieldDefinition.php file for more information

		$this->newField('email')
			->mandatory(true)
			;

		$this->newField('name')
			;

	}
}
