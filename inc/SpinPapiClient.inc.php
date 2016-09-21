<?php
/**
 * Class file of SpinPapiClient.
 *
 * PHP version 5.3
 *
 * Copyright (c) 2011-2013 Spinitron, LLC.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * - Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * - Neither the name of Spinitron, LLC nor the names of its contributors may be
 *   used to endorse or promote products derived from this software without
 *   specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    SpinPapi
 * @author      Tom Worster <tom@spinitron.com>
 * @copyright   2011-2013 Spinitron, LLC
 * @license     BSD 3-Clause License. http://opensource.org/licenses/BSD-3-Clause
 * @version     2013-03-04
 * @link        https://bitbucket.org/Spinitron/spinpapi-php-client
 */

/**
 * Presents an API to query SpinPapi and cache results.
 *
 * The SpinPapiClient class provides methods to retrieve data from Spinitron
 * using its SpinPapi API.
 *
 * It implements and provides methods to access an optional data cache that
 * uses local file storage. Alternatively the user may provide Memcache
 * server(s) and configure an instance to use that cache instead. In either
 * case it implements a generic, application-neutral cacheing policy with
 * heuristics derived from Spinitron's data semantics and typical Spinitron
 * use.
 *
 * SpinPapi users MUST use a data cache. Users may implment their own data
 * cache and use this SpinPapiClient in non-caching mode or they may
 * implement their own client and cache. In any case, the client must cache
 * SpinPapi data.
 *
 * The user must set the default timezone to the station's local timezone.
 *
 * @see     SpinPapi API Specification.
 * @link    http://spinitron.com/user-guide/pdf/SpinPapi-v2.pdf
 * @package SpinPapiClient
 * @version 2011-09-08
 */
class SpinPapiClient {
	/**#@+
	 * File cache expiration time in seconds.
	 */
	const EXP_S = 60;
	const EXP_M = 3600;
	const EXP_L = 10800;
	/**#@-*/
	/**
	 * File cache garbage collector runs at random on average once per
	 * GC_RATIO calls to SpinPapiClient::query(). Turn this up on a busy
	 * server. Running the GC once a minute is more than enough.
	 */
	const GC_RATIO = 100;
	/**#@+
	 * SpinPapi service config parameter.
	 */
    public $spHost = 'spinitron.com';
	public $spUrl = '/public/spinpapi.php';
	public $spVer = '2';
	/**#@-*/

	/**
	 * The SpinPapi user's authentication secret. Invariant after construction.
	 *
	 * @var array
	 */
	private $secret;
	/**
	 * The SpinPapi user's static quary parameters. Invariant after construction.
	 *
	 * @var array
	 */
	private $qp;
	/**
	 * The Memcached object, instance the PECL class or null to indicate that
	 * memcached isn't being used.
	 *
	 * @var Memcached
	 */
	private $mc = null;
	/**
	 * The file cache directory path or null to indicate that the file cache
	 * isn't being used.
	 *
	 * @var string
	 */
	private $fcDir = null;
	/**
	 * Hash of recognized SpinPapi methods to their two-letter abbreviations.
	 *
	 * @var array
	 */
	private $spMethods;
	/**
	 * Hash of recognized SpinPapi parameter names to their 2-letter abbrev.
	 *
	 * @var array
	 */
	private $spParams;
	/**
	 * Flag, true if the client requests errors be logged to PHP error log.
	 *
	 * @var bool
	 */
	private $logErrors;

    /**
     * Create and return a new SpinPapiClinet object.
     *
     * @param string $userid    User's authentication user ID.
     * @param string $secret    User's authentication secret.
     * @param string $station   Spinitron station ID.
     * @param bool   $logErrors Optionally set true to log errors to PHP log.
     * @param string $version SpinPapi API version string.
     */
	public function __construct($userid, $secret, $station, $logErrors = false, $version = '1') {
		$this->secret = $secret;
		$this->qp = array(
			'papiuser' => $userid,
			'station' => $station,
			'papiversion' => $version,
		);
		$this->logErrors = $logErrors;
		$this->spMethods = array(
			'getSong' => 'sg',
			'getSongs' => 'ss',
			'getCurrentPlaylist' => 'cp',
			'getPlaylistInfo' => 'pi',
			'getPlaylistsInfo' => 'ps',
			'getShowInfo' => 'si',
			'getRegularShowsInfo' => 'rs',
		);
		$this->spParams = array(
			'SongID' => 's',
			'PlaylistID' => 'p',
			'ShowID' => 'h',
			'When' => 'w',
			'StartHour' => 'h',
			'UserID' => 'u',
			'Num' => 'n',
		);
	}

	/**
	 * Initialize the Memcached client.
	 *
	 * If you initialize the Memcached client sucessfully then the file cache
	 * is disabled and SpinPapiClient::query() will try to use Memcached.
	 *
	 * @param array $servers The memcached server(s) to use. Each entry in
	 *                       is supposed to be an array containing hostname, port,
	 *                       and, optionally, weight of the server.
	 *
	 * @see         http://www.php.net/manual/en/memcached.addservers.php
	 * @return bool True means a Memcached object was sucessfully instantiated
	 *              but doesn't imply the servers are working.
	 */
	public function mcInit($servers) {
		if (!class_exists('Memcached')) {
			return false;
		}
		$this->mc = new Memcached('my_connection');
		if (get_class($this->mc) !== 'Memcached'
			|| !$this->mc->addServers($servers)
		) {
			$this->mc = null;
			return false;
		}
		$this->fcDir = null;
		return true;
	}

	/**
	 * Initialize the local file system data cache.
	 *
	 * You must provide a directory for the exclusive use of this cache. It
	 * must be writable by whatever process uses SpinPapiClient. The file
	 * system must support PHP's filemtime() function.
	 *
	 * If you initialize the file cache sucessfully then the Memcached client
	 * is disabled and SpinPapiClient::query() will try to the file cache.
	 *
	 * @param  string $dir Path of the file cache's directory.
	 *
	 * @return bool True if $dir exists and is writable.
	 */
	public function fcInit($dir) {
		if (is_dir($dir)
			&& is_writable($dir)
		) {
			$this->fcDir = $dir;
			$this->mc = null;
			return true;
		}
		return false;
	}

	/**
	 * Get data from SpinPapi.
	 *
	 * @see  SpinPapi API Specification.
	 * @link http://spinitron.com/user-guide/pdf/SpinPapi-v2.pdf
	 *
	 * @param  array $qp Query parameters. Array with one parameter per array
	 *                   element. Parameter name is element key and parameter
	 *                   value is array value. 'method' element MUST be present.
	 *                   'station', 'papiuser', 'papiversion' SHOULD NOT be
	 *                   included.
	 *
	 * @return array     Data returned by SpinPapi or false on error.
	 */
	public function queryNoCache($qp) {
		$qp = $qp + $this->qp;

		// SpinPapi requires acurate request timestamp in GMT
		$qp['timestamp'] = gmdate('Y-m-d\TH:i:s\Z');

		// Form the request's GET query string.
		ksort($qp);
		$query = array();
		foreach ($qp as $p => $v) {
			$query[] = rawurlencode($p) . '=' . rawurlencode($v);
		}
		$query = implode('&', $query);

		// Ensure the timestamp isn't reused.
		unset($qp['timestamp']);

		// Calculate request signature.
		$signature = rawurlencode(base64_encode(hash_hmac(
			"sha256",
			$this->spHost . "\n" . $this->spUrl . "\n" . $query,
			$this->secret,
			true
		)));

		// Form the request string.
		$request = $this->spHost . $this->spUrl . "?$query&signature=$signature";

		// Send the request and receive the result data.
		$data = file_get_contents('http://' . $request);

		// Return decoded result data or false on JSON error.
		return $data === false ? false : json_decode($data, 1);
	}

	/**
	 * Generate a cache key for a specific SpinPapi query result.
	 *
	 * If a Memcached object available, returns a key for that, otherwise a key
	 * for the file cache (whether its available or not).
	 *
	 * @param  array $qp Query parameters. Array with one parameter per array
	 *                   element. Parameter name is element key and parameter
	 *                   value is array value. 'method' element MUST be present.
	 *                   'station', 'papiuser', 'papiversion' SHOULD NOT be
	 *                   included.
	 *
	 * @return string    A cache key.
	 */
	public function key($qp) {
		$qp = $this->qp + $qp;
		$key = $this->spMethods[$qp['method']];
		foreach ($this->spParams as $p => $abv) {
			if (isset($qp[$p])) {
				$key .= '_' . $abv . '=' . $qp[$p];
			}
		}
		$key = preg_replace('/[^a-z0-9_=-]/', '', $key);

		return $this->mc ? 'SP:' . $key : $key;
	}

	/**
	 * Figure the cache lifetime for a specific SpinPapi query result using a
	 * default generic, application-neutral cache policy.
	 *
	 * If you modify this and you use the file cache, make consistent changes
	 * in SpinPapiClient::fcGC().
	 *
	 * @param  array $qp Query parameters. Array with one parameter per array
	 *                   element. Parameter name is element key and parameter
	 *                   value is array value. 'method' element MUST be present.
	 *                   'station', 'papiuser', 'papiversion' SHOULD NOT be
	 *                   included.
	 *
	 * @return int       Cache lifetime of the query result in seconds.
	 */
	public function expires($qp) {
		$qp = $this->qp + $qp;

		// Figuring the expiration time is a little complex. See the policy comments
		// in the README file.
		if ($qp['method'] == 'getPlaylistsInfo'
			&& isset($qp['UserID'])
			&& $qp['UserID']
		) {
			$expires = self::EXP_M;
		} elseif (isset($qp['SongID']) && $qp['SongID']) {
			$expires = self::EXP_M;
		} elseif (isset($qp['PlaylistID']) && $qp['PlaylistID']) {
			$expires = self::EXP_L;
		} elseif ($qp['method'] === 'getRegularShowsInfo') {
			if (isset($qp['When'])) {
				if (preg_match('/^[0-7]$/', $qp['When'])) {
					$expires = self::EXP_L;
				} elseif ($qp['When'] === 'now') {
					$expires = self::EXP_S;
				} elseif ($qp['When'] === 'today') {
					$expires = strtotime('tomorrow') - time();
					$expires
						+= isset($qp['StartHour'])
						&& is_numeric($qp['StartHour'])
						&& $qp['StartHour'] >= 0
						&& $qp['StartHour'] <= 24
						? $qp['StartHour']*3600
						: 21600;
					$expires %= 86400;
					$expires = min(self::EXP_L, $expires);
				} else {
					$expires = self::EXP_L;
				}
			} else {
				$expires = self::EXP_L;
			}
		} else {
			$expires = self::EXP_S;
		}

		return $expires;
	}

	/**
	 * Set a file cache entry.
	 *
	 * @param  string $key  A cache key, e.g. as provided by SpinPapi::key().
	 * @param  mixed  $data The data to cache in any serializable type.
	 *
	 * @return bool         True on success, false on serialize or file system
	 *                      write error.
	 */
	private function fcSet($key, $data) {
		$data = @serialize($data);
		if (!$data) {
			return false;
		}
		return file_put_contents($this->fcDir . '/' . $key, $data);
	}

	/**
	 * Get a file cache entry.
	 *
	 * @param  string $key     A cache key, e.g. as provided by SpinPapi::key().
	 * @param  int    $expires Lifetime of the cache entry in seconds.
	 *
	 * @return mixed  The cached data in its original type or false on
	 *                cache miss or file system read or unsearialize error.
	 */
	private function fcGet($key, $expires) {
		$f = $this->fcDir . '/' . $key;
		if (file_exists($f) && is_readable($f)) {
			if (filemtime($f) > time() - $expires) {
				$data = file_get_contents($f);
				if ($data) {
					$data = unserialize($data);
					if ($data) {
						// Cache hit. Return the cached data.
						return $data;
					}
				}
			} else {
				// The cache entry expired. Delete it.
				unlink($f);
			}
		}
		return false;
	}

	/**
	 * Run the file cache garbage collector.
	 *
	 * Run this from time to time to delete all expired file cache entries. If
	 * you midify this, the expiration times should be consistent with
	 * SpinPapiClient::expires().
	 *
	 * Note: The garbage collector doesn't know about default policy cache
	 * lifetime overrides you might have used when calling SpinPapi::query().
	 *
	 * @return bool True.
	 */
	public function fcGC() {
		if (!$this->fcDir) {
			return false;
		}
		$exps = array(
			'sg_s' => self::EXP_M, 'sg' => self::EXP_S,
			'ss_p' => self::EXP_L, 'ss' => self::EXP_S,
			'cp' => self::EXP_S,
			'pi_p' => self::EXP_L, 'pi' => self::EXP_S,
			'ps' => self::EXP_M,
			'si' => self::EXP_S,
			'rs_w=now' => self::EXP_S, 'rs' => self::EXP_L,
		);
		$files = glob($this->fcDir . '/*', GLOB_NOSORT);
		if ($files) {
			$now = time();
			foreach ($files as $file) {
				foreach ($exps as $pfx => $exp) {
					if (substr(basename($file), 0, strlen($pfx)) === $pfx) {
						$mtime = filemtime($file);
						if ($mtime && filemtime($file) < $now - $exp) {
							unlink($file);
						}
						continue 2;
					}
				}
			}
		}
		return true;
	}

	/**
	 * Expire the current song in the data cache.
	 *
	 * @return bool False on Memcached or file delete error, true otherwise.
	 */
	public function newNowPlaying() {
		if ($this->mc) {
			$this->mc->delete('SP:sg');
			return $this->mc->getResultCode() === Memcached::RES_SUCCESS;
		} elseif ($this->fcDir) {
			return file_exists($this->fcDir . '/sg')
				? unlink($this->fcDir . '/sg')
				: true;
		} else {
			return true;
		}
	}

	/**
	 * Get SpinPapi data, possibly from cache. Update cache as appropriate. Run
	 * garbage collector at random.
	 *
	 * @see SpinPapi API Specification.
	 * @link http://spinitron.com/user-guide/pdf/SpinPapi-v2.pdf
	 *
	 * @param array $qp Query parameters. Array with one parameter per array
	 * element. Parameter name is element key and parameter value is array value.
	 * 'method' element MUST be present. 'station', 'papiuser', 'papiversion'
	 * SHOULD NOT be included.
	 * @param bool|int $expires Override default policy cache lifetime. Seconds.
	 *
	 * @return array Data returned by SpinPapi or false on error.
	 */
	public function query($qp, $expires = false) {
		$qp = $qp + $this->qp;

		if (false && $this->fcDir && mt_rand(1, self::GC_RATIO) === 1) {
			$this->fcGC();
		}

		if (!isset($this->spMethods[$qp['method']])) {
			// Method is either absent from query params or unrecognized.
			return false;
		}

		// If we have a valid initialized cache, get a key for the query.
		if ($this->mc || $this->fcDir) {
			$key = $this->key($qp);
		}

		// Check the memcache, if there is one.
		if ($this->mc) {
			$data = $this->mc->get($key);
			if ($data !== false
				&& $this->mc->getResultCode() !== Memcached::RES_NOTFOUND
			) {
				// Cache hit. Return the cached data.
				return $data;
			}
		}

		// Check the file cache, if there is one.
		if ($this->fcDir) {
			$data = $this->fcGet($key, $expires ? $expires : $this->expires($qp));
			if ($data !== false) {
				// Cache hit. Return the cached data.
				return $data;
			}
		}

		// Caches (if any) missed. Request the data from Spinitron.
		$data = $this->queryNoCache($qp);

		// False or empty $data means the request failed.
		if (!$data) {
			if ($this->logErrors) {
				trigger_error("SpinPapi request failed.");
			}
			return false;
		}

		if ($this->logErrors && !$data['success']) {
            $message = 'SpinPapi request failed.';
            if (isset($data['errors']) && $data['errors']) {
                $message .= ' Errors: ' . implode(". ", $data['errors']) . '.';
            }
			trigger_error($message);
		}

		// If request went through and the response from Spinitron is valid, cache
		// it, whether the query was sucessful or not.
		if ($this->mc) {
			$wr = $this->mc->set(
				$key, $data,
				$expires ? $expires : $this->expires($qp)
			);
			if ($this->logErrors && $wr === false) {
				trigger_error(
					"Memcached::set() error: " . $this->mc->getResultMessage()
				);
			}
		} elseif ($this->fcDir) {
			$wr = $this->fcSet($key, $data);
			if ($this->logErrors && $wr === false) {
				trigger_error("Error writing to dir: " . $this->fcDir);
			}
		}

		return $data;
	}
}
