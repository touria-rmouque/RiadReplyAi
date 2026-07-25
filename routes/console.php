<?php
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
Artisan::command('inspire', fn() => app('log')->info(Inspiring::quote()))->purpose('Display an inspiring quote');
