<?php
/**
 * Container class file.
 */

namespace WcParcelas\App;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * PSR11 compliant dependency injection container.
 */
final class Container {
	/**
	 * The underlying container.
	 *
	 * @var \League\Container\Container
	 */
	private \League\Container\Container $container;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->container = new \League\Container\Container();

		$this->container->addShared( 'utils', \WcParcelas\App\Common\Utils::class );
		$this->container->addShared( 'settings', \WcParcelas\App\Common\Settings::class );
		$this->container->addShared( 'metaBox', \WcParcelas\App\Common\MetaBox::class );
		$this->container->addShared( 'frontEnd', \WcParcelas\App\Common\FrontEnd::class );
	}

	/**
	 * Finds an entry of the container by its identifier and returns it.
	 *
	 * @param  string $id Identifier of the entry to look for.
	 * @return mixed Entry.
	 * @throws ContainerExceptionInterface
	 * @throws NotFoundExceptionInterface
	 */
	public function get( string $id ) {
		return $this->container->get( $id );
	}
}