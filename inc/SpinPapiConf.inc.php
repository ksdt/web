<?php
/**
 * Configurations for example.php and spquery.
 *
 * Review all the comments following this docblock. Make adjustments as appopriate.
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
 * @uses        SpinPapiClient
 * @author      Tom Worster <tom@spinitron.com>
 * @copyright   2011-2013 Spinitron, LLC
 * @license     BSD 3-Clause License. http://opensource.org/licenses/BSD-3-Clause
 * @version     2013-03-04
 * @link        https://bitbucket.org/Spinitron/spinpapi-php-client
 */

// Review all the following comments. Make adjustments as appopriate.

// Default assumes SpinPapiClient.inc.php is in same diractory as this file.
// Change if it is elsewhere.
require_once dirname(__FILE__) . '/SpinPapiClient.inc.php';

// Set your station's timezone
// See: http://www.php.net/manual/en/timezones.america.php
date_default_timezone_set('America/Los_Angeles');

// Change these three
$mySpUserID  = PAPI_USER;
$mySpSecret  = PAPI_SECRET;
$myStation   = 'ksdt';

// To use a file cache, uncomment and set to the cache directory's path
//$myFCache = '/tmp';

// To use Memcached, uncomment and set your memcache servers
// See: http://www.php.net/manual/en/memcached.addservers.php
//$myMemcache = array(array('localhost', 11211));

// You MUST use data caching. Either use one of the caches in SpinPapiClient
// or implement your own. This is a condition for use of SpinPapi.

// Change this if you need the old API version
$papiVersion = 2;
