<?php declare(strict_types = 1);

namespace WebChemistry\ServiceAttribute\Group;

use WebChemistry\ServiceAttribute\Entity\ServiceEntityCollection;

interface GrouperInterface
{

	public function group(ServiceEntityCollection $collection): void;

}
