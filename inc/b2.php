<?php

require __DIR__ . '/../../../../vendor/autoload.php';

use ChrisWhite\B2\Client;
use ChrisWhite\B2\Bucket;

$client = new Client(getenv('b2accountId'), getenv('b2applicationKey'));
$bucketid = getenv('b2bucket');

// Returns a Bucket object.

$fileList;
$cachedFileList = './.b2filelist';

function getFileList($cacheless = false) {
    if (!$cacheless && $fileList) { /* in memory */
        return $fileList;
    } else if (!$cacheless && file_exists($cachedFileList)) { /* on disk */
        return unserialize(file_get_contents($cachedFileList));
    } else { /* from backblaze */
        $fileList = $client->listFiles([
            'BucketId' => $bucketid
        ]);
        file_put_contents($cachedFileList, serialize($fileList));
        return $fileList;
    }
}
/* show name, date is timestamp. */
function getPlaylist($show, $date) {
    /* if playlist is more than 2 weeks old, we can't stream it! */
    if ($date < strtotime('-2 weeks')) {
        return [
            'success' => false,
            'error' => 'cannot stream shows older than 2 weeks'
        ];
    } else {
        $files = getFileList();
        $expectedFile = str_replace('/', '\\/', utf8_encode($show . ' ' . format('Y-m-d', $date) . '.mp3'));
        echo "searching for file " . $expectedFile;
        foreach ($files as $key => $file) {
            /* matched */
            if ($file->getName() == $expectedFile) {
                
                return [
                    'success' => true,
                    'file' =>];
            }
        }
        /* didn't find. try updating getFileList (takes 2seconds)*/
        $files = getFileList(true);
        $expectedFile = $show . ' ' . format('Y-m-d', $date) . '.mp3';
        foreach ($files as $key => $file) {
            /* matched */
            if ($file->getName() == $expectedFile) {
                return $file;
            }
        }
        /* could not find */
        return [
            'success' => false,
            'error' => 'stream file not found'
        ];
    }
}

?>