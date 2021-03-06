<?php
/**
 * Nextcloud - Tasks
 *
 * @author Raimund Schlüßler
 * @copyright 2019 Raimund Schlüßler <raimund.schluessler@mailbox.org>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Tasks\Controller;

use \OCP\AppFramework\Controller;
use \OCP\AppFramework\Http\TemplateResponse;
use \OCP\IRequest;
use \OCP\IUserSession;
use \OCP\IConfig;

/**
 * Controller class for main page.
 */
class PageController extends Controller {

	/**
	 * @var IUserSession
	 */
	private $userSession;

	/**
	 * @var IConfig
	 */
	private $config;

	/**
	 * @param string $appName
	 * @param IRequest $request an instance of the request
	 * @param IUserSession $userSession
	 * @param IConfig $config
	 */
	public function __construct(string $appName, IRequest $request, IUserSession $userSession, IConfig $config) {
		parent::__construct($appName, $request);
		$this->userSession = $userSession;
		$this->config = $config;
	}


	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 */
	public function index():TemplateResponse {
		\OCP\Util::connectHook('\OCP\Config', 'js', $this, 'addJavaScriptVariablesForIndex');
		return new TemplateResponse('tasks', 'main');
	}

	/**
	 * Add parameters to javascript for user sites
	 *
	 * @param array $array
	 */
	public function addJavaScriptVariablesForIndex(array $array) {
		$user = $this->userSession->getUser();
		if ($user === null) {
			return;
		}
		$appversion = $this->config->getAppValue($this->appName, 'installed_version');
		$array['array']['oca_tasks'] = \json_encode([
			'versionstring' => $appversion,
		]);
	}
}
