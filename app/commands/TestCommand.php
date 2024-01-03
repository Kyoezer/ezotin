<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:Test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This is my first test on doing cron job with laravel with linux os';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
		ContractorModel::delete();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		/*return array(
			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);*/
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		/*return array(
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);*/
        return [];
	}

}
