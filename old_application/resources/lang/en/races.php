<?php

return [

	'title' => 'Races',

	'index' => [
		'name' => 'Name',
		'date' => 'Date',
		'hour' => 'Hour',
		'laps' => 'Laps',
		'hours' => 'Hours',
		'group' => 'participants',
	],
	'show' => [
		'title' => 'Race',
		'points' => 'Points',
		'bestLap' => 'Best lap',
	],
	'link' => [
		'create' => 'Create new Race',
		'delete' => 'Delete',
		'edit' => 'Edit',
		'selectRacers' => 'Start a race',
		'selectGroups' => 'Select groups',
		'startRaceGroups' => 'Start a race',
	],
    'form' => [
    	'name' => 'Name',
        'championship_id' => 'Championship',
    	'date' => 'Date',
    	'hour' => 'Hour',
    	'type' => 'Hours type',
    	'laps' => 'Laps',
    	'hours' => 'Number of hours',
    	'group' => 'Number of participants of a group',
    	'submit' => 'Create',
    	'save' => 'Save'
    ],

    'selectRacers' => [
    	'header' => 'Select racers',
    	'submit' => 'Go to race!',
        'number' => 'Number',
        'filter' => 'Filter selecteds',
        'remove_filter' => 'Show all',
        'racer_filter' => 'Search',
    ],

    'selectGroups' => [
    	'header' => 'Select groups',
    	'group' => 'Group',
    	'number' => 'Number',
    	'submit' => 'Save groups'
    ],

    'startRace' => [
    	'header' => 'Race control',
        'number_lap' => 'Lap with number'
    ]
];
