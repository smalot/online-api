<?php

namespace Smalot\Online\Resources;

use Smalot\Online\Online;

/**
 * Class ODS
 * @author Benjamin HUBERT <benjamin@alpixel.fr>
 * @package Smalot\Online\Resources
 */
class ODS
{
    /**
     * @var Online
     */
    protected $online;

    const ACCESS_LEVEL_ADMIN = "admin";
    const ACCESS_LEVEL_READ_ONLY = "ro";
    const ACCESS_LEVEL_READ_WRITE = "rw";

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
     * Get the list of your ODS
     * @return Array
     */
    public function getOdsList()
    {
        return $this->online->call('/ods');
    }

    /**
     * Get the list of database in a ODS
     * @param $odsId
     * @return Array
     */
    public function getDatabaseList($odsId)
    {
        return $this->online->call('/ods/'.$odsId.'/databases');
    }

    /**
     * @param $odsId
     * @return Array
     */
    public function getOdsDetails($odsId)
    {
        return $this->online->call('/ods/'.$odsId);
    }

    /**
     * @param $odsId
     * @return Array
     */
    public function getOdsBackups($odsId)
    {
        return $this->online->call('/ods/'.$odsId.'/backups');
    }

    /**
     * @param $odsId
     * @return boolean
     */
    public function createOdsBackup($odsId)
    {
        return $this->online->call('/ods/'.$odsId.'/backups', 'POST');
    }

    /**
     * @param $odsId
     * @param $backupId
     * @return Array
     */
    public function getOdsBackup($odsId, $backupId)
    {
        return $this->online->call('/ods/'.$odsId.'/backups/'.$backupId);
    }


    /**
     * Remove a backup on your ODS
     * @param $odsId
     * @param $backupId
     * @return boolean
     */
    public function deleteBackup($odsId, $backupId)
    {
        return $this->online->call('/ods/'.$odsId.'/backups/'.$backupId, 'DELETE');
    }

    /**
     * Create a backup for a database on your ODS
     * @param $odsId
     * @param $databaseName
     * @return boolean
     */
    public function createBackup($odsId, $databaseName)
    {
        return $this->online->call('/ods/'.$odsId.'/databases/'.$databaseName.'/backups', 'POST');
    }

    /**
     * Get the list of backups for a database of your ODS
     * @param $odsId
     * @param $databaseName
     * @return boolean
     */
    public function getBackupsList($odsId, $databaseName)
    {
        return $this->online->call('/ods/'.$odsId.'/databases/'.$databaseName.'/backups');
    }

    /**
     * Get a backup from your ODS
     * @param $odsId
     * @param $databaseName
     * @param $backupId
     * @return Array
     */
    public function getBackup($odsId, $databaseName, $backupId)
    {
        return $this->online->call('/ods/'.$odsId.'/databases/'.$databaseName.'/backups/'.$backupId);
    }

    /**
     * Create a new database
     * @param $odsId
     * @param $databaseName
     * @return Array
     */
    public function createDatabase($odsId, $databaseName)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/databases',
            'POST',
            [
                'database_name' => $databaseName,
            ]
        );
    }

    /**
     * Delete a database
     * @param $odsId
     * @param $databaseName
     * @return Array
     */
    public function deleteDatabase($odsId, $databaseName)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/databases/'.$databaseName,
            'DELETE'
        );
    }

    /**
     * Get the access list of a database of your ODS
     * @param $odsId
     * @param $databaseName
     * @return Array
     */
    public function getDatabaseAccessList($odsId, $databaseName)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/databases/'.$databaseName.'/access'
        );
    }

    /**
     * Create access for a user to a database of your ODS
     * @param $odsId
     * @param $databaseName
     * @param $levelAccess
     * @param $username
     * @return Array
     */
    public function createDatabaseAccess($odsId, $databaseName, $levelAccess = self::ACCESS_LEVEL_READ_WRITE, $username)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/databases/'.$databaseName.'/access',
            'POST',
            [
                'access'   => $levelAccess,
                'username' => $username,
            ]
        );
    }

    /**
     * Get access between a database and a user on your ODS
     * @param $odsId
     * @param $databaseName
     * @param $username
     * @return Array
     */
    public function getDatabaseAccess($odsId, $databaseName, $username)
    {
        return $this->online->call('/ods/'.$odsId.'/databases/'.$databaseName.'/access/'.$username);
    }

    /**
     * Delete an access between a user and a database on your ODS
     * @param $odsId
     * @param $databaseName
     * @param $username
     * @return Array
     */
    public function deleteDatabaseAccess($odsId, $databaseName, $username)
    {
        return $this->online->call('/ods/'.$odsId.'/databases/'.$databaseName.'/access/'.$username, 'DELETE');
    }


    /**
     * Change a user's access level to a database of your ODS
     * @param $odsId
     * @param $databaseName
     * @param $levelAccess
     * @param $username
     * @return Array
     */
    public function changeDatabaseAccess($odsId, $databaseName, $levelAccess = self::ACCESS_LEVEL_READ_WRITE, $username)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/databases/'.$databaseName.'/access/'.$username,
            'PATCH',
            [
                'access' => $levelAccess,
            ]
        );
    }

    /**
     * Get the users list of a ODS
     * @param $odsId
     * @return Array
     */
    public function getUsers($odsId)
    {
        return $this->online->call('/ods/'.$odsId.'/users');
    }

    /**
     * Create an user on the ODS
     * @param $odsId
     * @param $login
     * @param $password
     * @return Array
     */
    public function createUser($odsId, $login, $password)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/users',
            'POST',
            [
                'login'    => $login,
                'password' => $password,
            ]
        );
    }

    /**
     * Get an user on the ODS
     * @param $odsId
     * @param $username
     * @return Array
     */
    public function getUser($odsId, $username)
    {
        return $this->online->call('/ods/'.$odsId.'/users/'.$username);
    }

    /**
     * Delete an user on the ODS
     * @param $odsId
     * @param $username
     * @return Array
     */
    public function deleteUser($odsId, $username)
    {
        return $this->online->call('/ods/'.$odsId.'/users/'.$username, 'DELETE');
    }

    /**
     * Change password of an user of your ODS
     * @param $odsId
     * @param $username
     * @param $password
     * @return Array
     */
    public function changeUserPassword($odsId, $username, $password)
    {
        return $this->online->call(
            '/ods/'.$odsId.'/users/'.$username,
            'PATCH',
            [
                'password' => $password,
            ]
        );
    }

    /**
     * Get the list of access of an user on your ODS
     * @param $odsId
     * @param $username
     * @return Array
     */
    public function getUserAccess($odsId, $username)
    {
        return $this->online->call('/ods/'.$odsId.'/users/'.$username.'/access/');
    }
}
