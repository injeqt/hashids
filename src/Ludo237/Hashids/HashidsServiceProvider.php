<?php namespace Ludo237\Hashids;

use Hashids\Hashids;
use Illuminate\Support\ServiceProvider;

class HashidsServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('danphyxius/hashids');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */

	public function register()
	{
		$this->registerHashids();
	}

	protected function registerHashids()
	{
		$self = $this;

		$this->app->bind('Hashids\Hashids', function ($app) use ($self) {

			$params = $self->getParams(); 

			return new Hashids(			
				$params['salt'],
				$params['length'],
				$params['alphabet']
			);
		});
	}

	/**
	 * Get the salt, length and alphabet params, used for encrypting and decrypting hashes.
	 *
	 * @return string
	 */
	public function getParams()
	{
		return array(
			'salt' => $this->getSalt(), 
			'length' => $this->getLength(), 
			'alphabet' => $this->getAlphabet()
			);
	}

	/**
	 * Get the length used for encrypting and decrypting hashes.
	 *
	 * @return string
	 */
	public function getSalt()
	{
		$salt = $this->app['config']['hashids::salt'];

		if ( ! $salt) {
			$salt = $this->app['config']['app.key'];
		}

		return $salt;
	}

	/**
	 * Get the length used for the length of the hash.
	 *
	 * @return string
	 */
	public function getLength()
	{
		$length = $this->app['config']['hashids::length'];

		if (! $length) {
			$length = 7;
		}

		return $length;
	}

	/**
	 * Get the alphabet used as base for encrypting and decrypting hashes.
	 *
	 * @return string
	 */
	public function getAlphabet()
	{
		$alphabet = $this->app['config']['hashids::alphabet'];

		if (! $alphabet) {
			$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		}		
		return $alphabet;
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('Hashids\Hashids');
	}
}
