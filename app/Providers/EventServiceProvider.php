<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\OrderEventHandler;
use App\Events\BillEventHandler;
use App\Events\TableEventHandler;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);
		$subscriber = new OrderEventHandler;
		$events->subscribe($subscriber);
		$subscriber = new BillEventHandler;
		$events->subscribe($subscriber);
		$subscriber = new TableEventHandler;
		$events->subscribe($subscriber);
		//
	}

}
