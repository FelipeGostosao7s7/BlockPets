<?php

declare(strict_types = 1);

namespace BlockHorizons\BlockPets\pets\datastorage;

use BlockHorizons\BlockPets\Loader;
use BlockHorizons\BlockPets\pets\datastorage\types\PetData;

abstract class BaseDataStorer {

	/** @var Loader */
	protected $loader;

	public function __construct(Loader $loader) {
		$this->loader = $loader;
		$this->prepare();
		if($loader->getBlockPetsConfig()->doHardReset()) {
			$this->reset();
			$this->getLoader()->getConfig()->set("Hard-Reset", false);
		}
	}

	/**
	 * Called during class construction to let
	 * databases create and initialize their
	 * instances.
	 */
	protected abstract function prepare(): void;

	/**
	 * Called when the plugin updates so database
	 * can perform patches (if any).
	 *
	 * @param string $version
	 */
	protected abstract function patch(string $version): void;

	/**
	 * @return Loader
	 */
	protected function getLoader(): Loader {
		return $this->loader;
	}

	/**
	 * Resets all values in the database.
	 */
	protected abstract function reset(): void;

	/**
	 * Registers pet to the database.
	 * If the pet's entry already exists in the
	 * database, the database will perform an
	 * UPDATE-ALL-VALUES instead.
	 *
	 * @param PetData $data
	 */
	public abstract function registerPet(PetData $data): void;

	/**
	 * Deletes the pet's entry from the database
	 * if exists.
	 *
	 * @param string $ownerName
	 * @param string $petName
	 */
	public abstract function unregisterPet(string $ownerName, string $petName): void;

	/**
	 * Retrieves all of the owner's pets from the
	 * database and then calls the callable to
	 * initialize the fetched entries.
	 *
	 * @param string $ownerName
	 * @param callable $callable
	 */
	public abstract function load(string $ownerName, callable $callable): void;

	/**
	 * Fetches all pets' names of the specified player
	 * from the database and calls the callable to get
	 * the list of pet names.
	 * If $entityName is not null, only entities with the
	 * specified entity name will be fetched.
	 *
	 * @param string $ownerName
	 * @param string|null $entityName
	 * @param callable $callable
	 */
	public abstract function getPlayerPets(string $ownerName, ?string $entityName = null, callable $callable): void;

	/**
	 * Fetches all pets sorted by their level and points
	 * and calls the callable to get the list of sorted
	 * pets.
	 * If $entityName is not null, only entities with the
	 * specified entity name will be fetched.
	 *
	 * @param int $offset
	 * @param int $length
	 * @param string|null $entityName
	 * @param callable $callable
	 */
	public abstract function getPetsLeaderboard(int $offset = 0, int $length = 1, ?string $entityName = null, callable $callable): void;

	/**
	 * Toggles pets on or off from the database.
	 *
	 * @param string $ownerName
	 * @param string|null $petName
	 */
	public abstract function togglePets(string $owner, ?string $petName, callable $callable): void;

	/**
	 * Renames pet in the database.
	 *
	 * @param string $ownerName
	 * @param string $oldName
	 * @param string $newName
	 */
	public abstract function updatePetName(string $ownerName, string $oldName, string $newName): void;

	/**
	 * Called during plugin disable to let databases
	 * close their instances.
	 */
	protected abstract function close(): void;
}