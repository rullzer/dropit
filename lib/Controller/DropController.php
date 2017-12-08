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

namespace OCA\DropIt\Controller;

use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\Constants;
use OCP\Files\IRootFolder;
use OCP\Files\NotFoundException;
use OCP\IRequest;
use OCP\IURLGenerator;
use OCP\Share;
use OCP\Share\IManager as ShareManager;

class DropController extends Controller {

	/** @var IRootFolder */
	private $rootFolder;

	/** @var string */
	private $userId;

	/** @var ShareManager */
	private $shareManager;

	/** @var ITimeFactory */
	private $timeFactory;

	/** @var IURLGenerator */
	private $urlGenerator;

	/**
	 * DropController constructor.
	 *
	 * @param string $appName
	 * @param IRequest $request
	 * @param IRootFolder $rootFolder
	 * @param string $userId
	 * @param ShareManager $shareManager
	 * @param ITimeFactory $timeFactory
	 * @param IURLGenerator $urlGenerator
	 */
	public function __construct(string $appName,
								IRequest $request,
								IRootFolder $rootFolder,
								string $userId,
								ShareManager $shareManager,
								ITimeFactory $timeFactory,
								IURLGenerator $urlGenerator) {
		parent::__construct($appName, $request);

		$this->rootFolder = $rootFolder;
		$this->userId = $userId;
		$this->shareManager = $shareManager;
		$this->timeFactory = $timeFactory;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return JSONResponse
	 */
	public function upload() {
		$files = $this->request->files;

		if (count($files) !== 1) {
			return new JSONResponse([], Http::STATUS_BAD_REQUEST);
		}

		$ts = $this->timeFactory->getTime();
		$dt = new \DateTime();
		$dt->setTimestamp($ts);

		$folder = $this->getFolder();

		$drop = array_pop($files);
		$fileName = $dt->format('YmdHis') . ' - ' . $drop['name'];

		$file = $folder->newFile($fileName);
		$file->putContent(file_get_contents($drop['tmp_name']));

		$share = $this->shareManager->newShare();
		$share->setNode($file);
		$share->setShareType(Share::SHARE_TYPE_LINK);
		$share->setPermissions(Constants::PERMISSION_READ);
		$share->setSharedBy($this->userId);

		$share = $this->shareManager->createShare($share);

		return new JSONResponse([
			'link' => $this->urlGenerator->linkToRouteAbsolute('files_sharing.sharecontroller.showShare', ['token' => $share->getToken()]),
		]);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string text
	 * @return JSONResponse
	 */
	public function text(string $text) {
		$ts = $this->timeFactory->getTime();
		$dt = new \DateTime();
		$dt->setTimestamp($ts);

		$folder = $this->getFolder();

		$fileName = $dt->format('YmdHis') . '.txt';

		$file = $folder->newFile($fileName);
		$file->putContent($text);

		$share = $this->shareManager->newShare();
		$share->setNode($file);
		$share->setShareType(Share::SHARE_TYPE_LINK);
		$share->setPermissions(Constants::PERMISSION_READ);
		$share->setSharedBy($this->userId);

		$share = $this->shareManager->createShare($share);

		return new JSONResponse([
			'link' => $this->urlGenerator->linkToRouteAbsolute('files_sharing.sharecontroller.showShare', ['token' => $share->getToken()]),
		]);
	}

	/**
	 * @return \OCP\Files\Folder
	 */
	private function getFolder() {
		$userFolder = $this->rootFolder->getUserFolder($this->userId);

		//Check for DropIt
		try {
			$dropItFolder = $userFolder->get('DropIt');
		} catch (NotFoundException $e) {
			$dropItFolder = $userFolder->newFolder('DropIt');
		}

		return $dropItFolder;
	}
}
