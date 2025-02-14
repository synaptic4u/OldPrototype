<?php

namespace Synaptic4U\Packages\Authenticate;

use Synaptic4U\Core\Log;

class Authenticate
{
    public function checkRequiredAppPermissions($params)
    {
        $data = null;

        $mod = new Model();

        return $mod->checkRequiredAppPermissions($params);
    }

    public function checkUserPermissionOnApp($params, $appid)
    {
        $data = null;

        $mod = new Model();

        return $mod->checkUserPermissionOnApp($params);
    }

    public function checkUserExists($params)
    {
        $data = null;

        $mod = new Model($params);

        $data = $mod->checkUserExists($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        return $data;
    }

    public function insertLogin($params)
    {
        $data = null;

        $mod = new Model($params);

        $data = $mod->insertLogin($params);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'params' => json_encode($params, JSON_PRETTY_PRINT),
        //     'data' => json_encode($data, JSON_PRETTY_PRINT),
        // ]);

        if (null === $data) {
            return null;
        }

        return $data;
    }

    public function forgotPassword($email)
    {
        $data = null;

        $mod = new Model();

        $data = $mod->forgotPassword($email);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'email' => $email,
        //     'data' => $data,
        // ]);

        if (null === $data) {
            return null;
        }

        return $data;
    }

    public function confirmSecLink($link)
    {
        $data = null;

        $mod = new Model();

        return $mod->confirmSecLink($link);
        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'link' => $link,
        //     'data' => $data,
        // ]);
    }

    public function logout($userid)
    {
        $data = null;

        $mod = new Model();

        $data = $mod->logout($userid);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'userid' => $userid,
        //     'data' => $data,
        // ]);

        if (null === $data) {
            return null;
        }

        return $data;
    }

    public function getWhoAmI($userid)
    {
        $data = null;

        $mod = new Model();

        $data = $mod->getWhoAmI($userid);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'userid' => $userid,
        //     'data' => $data,
        // ]);

        if (null === $data) {
            return null;
        }

        return $data;
    }

    public function clearUserLogin($limit)
    {
        $data = null;

        $mod = new Model();

        $data = $mod->clearUserLogin($limit);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'limit' => $limit,
        //     'data' => $data,
        // ]);

        if (null === $data) {
            return null;
        }

        return $data;
    }

    public function userActivity($userid)
    {
        $data = null;

        $mod = new Model();

        $data = $mod->userActivity($userid);

        // $this->log([
        //     'Location' => __METHOD__ . '(): 0',
        //     'userid' => $userid,
        //     'data' => $data,
        // ]);

        if (null === $data) {
            return null;
        }

        return $data['time'];
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
