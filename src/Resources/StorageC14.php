<?php

namespace Smalot\Online\Resources;

use Smalot\Online\Online;

/**
 * Class StorageC14
 * @package Smalot\Online\Resources
 */
class StorageC14
{
    /**
     * Parity (standard, or enterprise; default: standard).
     */
    const PARITY_STD = 'standard';
    const PARITY_ENT = 'enterprise';

    /**
     * @var Online
     */
    protected $online;

    /**
     * StorageC14 constructor.
     *
     * @param Online $online
     */
    public function __construct(Online $online)
    {
        $this->online = $online;
    }

    /**
     * Returns a list of links to the platforms.
     *
     * @return array
     */
    public function getPlatformList()
    {
        return $this->online->call('/storage/c14/platform');
    }

    /**
     * Returns information about a platform.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getPlatformDetails($id)
    {
        return $this->online->call('/storage/c14/platform/'.$id);
    }

    /**
     * Returns a list of available file transfer protocols.
     *
     * @return array
     */
    public function getProtocolList()
    {
        return $this->online->call('/storage/c14/protocol');
    }

    /**
     * Returns a list of links to the user's safes.
     *
     * @param int $count
     * @param string $maxId
     * @param string $sinceId
     *
     * @return array
     */
    public function getSafeList($count = null, $maxId = null, $sinceId = null)
    {
        $parameters = array(
          'count' => $count,
          'max_id' => $maxId,
          'since_id' => $sinceId,
        );

        $parameters = array_filter($parameters, function($val) {return !is_null($val);});

        return $this->online->call('/storage/c14/safe', 'GET', $parameters);
    }

    /**
     * Creates a safe on the user's account, returns its id with an HTTP code 201.
     *
     * @param string $name
     * @param string $description
     *
     * @return string
     */
    public function createSafe($name, $description)
    {
        $parameters = array(
          'name' => $name,
          'description' => $description,
        );

        return $this->online->call('/storage/c14/safe', 'POST', $parameters);
    }

    /**
     * Returns information about a safe.
     *
     * @param string $uuidSafe
     *
     * @return array
     */
    public function getSafeDetails($uuidSafe)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe);
    }

    /**
     * Deletes a safe. Returns nothing, with an HTTP code 204.
     *
     * @param string $uuidSafe
     *
     * @return array
     */
    public function deleteSafe($uuidSafe)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe, 'DELETE');
    }

    /**
     * Edits a safe on the user's account. Returns nothing, with an HTTP code 204.
     *
     * @param string $uuidSafe
     * @param string $name
     * @param string $description
     *
     * @return array
     */
    public function updateSafe($uuidSafe, $name, $description)
    {
        $parameters = array(
          'name' => $name,
          'description' => $description,
        );

        return $this->online->call('/storage/c14/safe/'.$uuidSafe, 'PATCH', $parameters);
    }

    /**
     * Returns a list of archives in the user's safe.
     *
     * @param string $uuidSafe
     * @param int $count
     * @param string $maxId
     * @param string $sinceId
     *
     * @return array
     */
    public function getArchiveList($uuidSafe, $count = null, $maxId = null, $sinceId = null)
    {
        $parameters = array(
          'count' => $count,
          'max_id' => $maxId,
          'since_id' => $sinceId,
        );

        $parameters = array_filter($parameters, function($val) {return !is_null($val);});

        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive', 'GET', $parameters);
    }

    /**
     * Creates an archive in the safe, returns its id with an HTTP code 201.
     *
     * @param string $uuidSafe
     * @param string $name
     * @param string $description
     * @param string $parity
     * @param array $protocols
     * @param array $sshKeys
     * @param int $days
     * @param array $platforms
     *
     * @return string
     */
    public function createArchive($uuidSafe, $name, $description, $parity = null, $protocols = array(), $sshKeys = array(), $days = null, $platforms = array())
    {
        if (!in_array($parity, array(self::PARITY_STD, self::PARITY_ENT))) {
            $parity = self::PARITY_STD;
        }

        $days = intval($days);
        if (!in_array($days, array(2, 5, 7))) {
            $days = 7;
        }

        $parameters = array(
          'name' => $name,
          'description' => $description,
          'parity' => $parity,
          'protocols' => $protocols,
          'ssh_keys' => $sshKeys,
          'days' => $days,
          'platforms' => $platforms,
        );

        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive', 'POST', $parameters);
    }

    /**
     * Returns information about an Archive.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     *
     * @return array
     */
    public function getArchiveDetails($uuidSafe, $uuidArchive)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive);
    }

    /**
     * Deletes an archive. Returns nothing, with an HTTP code 204.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     *
     * @return void
     */
    public function deleteArchive($uuidSafe, $uuidArchive)
    {
        $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive, 'DELETE');
    }

    /**
     * Edits an archive. Returns nothing, with an HTTP code 204.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $name
     * @param string $description
     *
     * @return void
     */
    public function updateArchive($uuidSafe, $uuidArchive, $name, $description)
    {
        $parameters = array(
          'name' => $name,
          'description' => $description,
        );

        $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive, 'PATCH', $parameters);
    }

    /**
     * Archives the files from temporary storage, returns true with an HTTP code 202.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     *
     * @return bool
     */
    public function doArchive($uuidSafe, $uuidArchive)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/archive', 'POST');
    }

    /**
     * Returns information about an archive's temporary storage.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     *
     * @return array
     */
    public function getBucketDetails($uuidSafe, $uuidArchive)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/bucket');
    }

    /**
     * Returns list of archive jobs.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $status
     * @param int $count
     * @param string $maxId
     * @param string $sinceId
     *
     * @return array
     */
    public function getJobList($uuidSafe, $uuidArchive, $status = null, $count = null, $maxId = null, $sinceId = null)
    {
        $parameters = array(
          'status' => $status,
          'count' => $count,
          'max_id' => $maxId,
          'since_id' => $sinceId,
        );

        $parameters = array_filter($parameters, function($val) {return !is_null($val);});

        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/job', 'GET', $parameters);
    }

    /**
     * Returns information of a job.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $uuidJob
     *
     * @return array
     */
    public function getJobDetails($uuidSafe, $uuidArchive, $uuidJob)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/job/'.$uuidJob);
    }

    /**
     * Returns an archive's encryption key.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     *
     * @return string
     */
    public function getKey($uuidSafe, $uuidArchive)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/key');
    }

    /**
     * Sets an archive's encryption key. Returns nothing, with an HTTP code 204.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $key
     *
     * @return void
     */
    public function enterKey($uuidSafe, $uuidArchive, $key)
    {
        $parameters = array(
          'key' => $key,
        );

        $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/key', 'POST', $parameters);
    }

    /**
     * Deletes an archive's encryption key. Returns nothing, with an HTTP code 204.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     *
     * @return void
     */
    public function deleteKey($uuidSafe, $uuidArchive)
    {
        $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/key', 'DELETE');
    }

    /**
     * Returns a list of locations on the user's archive.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param int $count
     * @param string $maxId
     * @param string $sinceId
     *
     * @return array
     */
    public function getLocationList($uuidSafe, $uuidArchive, $count = null, $maxId = null, $sinceId = null)
    {
        $parameters = array(
          'count' => $count,
          'max_id' => $maxId,
          'since_id' => $sinceId,
        );

        $parameters = array_filter($parameters, function($val) {return !is_null($val);});

        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/location', 'GET', $parameters);
    }

    /**
     * Returns a list of locations on the user's archive.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $uuidLocation
     *
     * @return array
     */
    public function getLocationDetails($uuidSafe, $uuidArchive, $uuidLocation)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/location/'.$uuidLocation);
    }

    /**
     * Returns a list of locations on the user's archive.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $uuidLocation
     *
     * @return bool
     */
    public function doVerify($uuidSafe, $uuidArchive, $uuidLocation)
    {
        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/location/'.$uuidLocation.'/verify', 'POST');
    }

    /**
     * Unarchives files into temporary storage, returns true with an HTTP code 202.
     *
     * @param string $uuidSafe
     * @param string $uuidArchive
     * @param string $locationId
     * @param bool $rearchive
     * @param string $key
     * @param array $protocols
     * @param array $sshKeys
     *
     * @return bool
     */
    public function doUnarchive($uuidSafe, $uuidArchive, $locationId, $rearchive = true, $key = '', $protocols = array(), $sshKeys = array())
    {
        $parameters = array(
          'location_id' => $locationId,
          'rearchive' => (bool) $rearchive,
          'key' => $key,
          'protocols' => $protocols,
          'ssh_keys' => $sshKeys,
        );

        return $this->online->call('/storage/c14/safe/'.$uuidSafe.'/archive/'.$uuidArchive.'/unarchive', 'POST', $parameters);
    }
}
