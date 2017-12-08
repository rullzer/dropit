<?php
/**
 * @copyright Copyright (c) 2017, Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\DropIt\BackgroundJob;

use OC\BackgroundJob\TimedJob;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\Files\File;
use OCP\Files\Folder;
use OCP\Files\IRootFolder;
use OCP\IUser;
use OCP\IUserManager;

class CleanUpJob extends TimedJob {
	/** @var IUserManager */
	private $userManager;

	/** @var IRootFolder */
	private $rootFolder;

	/** @var ITimeFactory */
	private $timeFactory;

	public function __construct(IUserManager $userManager,
								IRootFolder $rootFolder,
								ITimeFactory $timeFactory) {
		// Run once a day
		$this->setInterval(24 * 60 * 60);

		$this->userManager = $userManager;
		$this->rootFolder = $rootFolder;
		$this->timeFactory = $timeFactory;
	}

	protected function run($argument) {
		$now = new \DateTime();
		$now->setTimestamp($this->timeFactory->getTime());


		$this->userManager->callForSeenUsers(function(IUser $user) use ($now) {
			$userFolder = $this->rootFolder->getUserFolder($user->getUID());

			// No DropIt folder, just ignore
			if (!$userFolder->nodeExists('DropIt')) {
				return;
			}

			$dropIt = $userFolder->get('DropIt');

			// If it isn't a folder just return
			if (!($dropIt instanceof Folder)) {
				return;
			}

			$listing = $dropIt->getDirectoryListing();

			foreach ($listing as $node) {
				// Only process files
				if (!($node instanceof File)) {
					continue;
				}

				// Match on YYYYmmddHHiiss
				if (preg_match('/^[0-9]{14}/', $node->getName(), $matches)) {
					$filetime = \DateTime::createFromFormat('YmdHis', $matches[0]);

					$diff = $now->diff($filetime);

					// Delete all files older than 14 days
					if ($diff->days > 14) {
						$node->delete();
					}
				}
			}
		});
	}

}
