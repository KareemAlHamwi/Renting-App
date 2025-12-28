<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('reservations:mark-completed')->dailyAt('00:05');
