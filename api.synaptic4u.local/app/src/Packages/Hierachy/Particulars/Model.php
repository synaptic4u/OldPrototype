<?php

namespace Synaptic4U\Packages\Hierachy\Particulars;

use Exception;
use Synaptic4U\Core\DB;
use Synaptic4U\Core\Encryption;
use Synaptic4U\Core\Log;
use Synaptic4U\Core\Validate;

class Model
{
    protected $encrypt;
    protected $db;
    protected $validate;

    public function __construct()
    {
        try {
            $this->encrypt = new Encryption();

            $this->db = new DB();

            $path = dirname(__FILE__, 1).'/Models/';

            $this->validate = new Validate($path);

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'Dir' => dirname(__FILE__, 1),
            //     'path' => $path,
            //     'validate' => serialize($this->validate),
            // ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        }
    }

    public function getPartUsers($params, $id)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = $this->encrypt->unscramble($params['formArray'][1], 'detid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            $sql = 'select hu.hierachyid, hu.userid, concat(u.firstname, " ", u.surname) as users 
                      from hierachy_users hu
                      left join users u
                        on hu.userid = u.userid
                     where hu.hierachyid = ?
                     order by u.firstname, u.surname;';

            $result = $this->db->query([
                $hierachyid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data[$res->users] = [
                    'pass' => null,
                    'message' => null,
                    'userid' => $this->encrypt->scramble($res->userid, 'userid', $params['userid']),
                    'value' => $res->users,
                    'selected' => ((int) $res->userid === (int) $id) ? 1 : 0,
                ];

                if (null === $data[$res->users]['userid']) {
                    throw new Exception('Unable to encrypt '.$res->users.' userid.');
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'id' => $id,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function store($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['hierachyid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
            $params['formArray']['detid'] = (int) $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
            $params['formArray']['particularid'] = (int) $this->encrypt->unscramble($params['formArray']['particularid'], 'particularid');
            $params['formArray']['contactuserid'] = (int) $this->encrypt->unscramble($params['formArray']['contactuserid'], 'userid');
            $params['formArray']['imageid'] = (int) $this->encrypt->unscramble($params['formArray']['imageid'], 'imageid');

            // $this->log([
            //     'Location' => __METHOD__.'(): 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            if (null === $params['formArray']['hierachyid']) {
                throw new Exception('Unable to decrypt HierachyId');
            }
            if (null === $params['formArray']['detid']) {
                throw new Exception('Unable to decrypt detid');
            }
            if (null === $params['formArray']['particularid']) {
                throw new Exception('Unable to decrypt particularid');
            }
            if (null === $params['formArray']['contactuserid']) {
                throw new Exception('Unable to decrypt contactuserid');
            }

            $data = $this->validate->isValid('Particulars', $params['formArray']);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'insert into hierachy_particulars(contactuserid, detid, phone, address, ispostal, website, postal)
                            values(?, ?, ?, ?, ?, ?, ?);';

                $result = $this->db->query([
                    $data['contactuserid']['value'],
                    $data['detid']['value'],
                    $data['phone']['value'],
                    $data['address']['value'],
                    ('on' === (string) $params['formArray']['hierachyispostal']) ? 1 : 0,
                    $data['website']['value'],
                    $data['postal']['value'],
                ], $sql, __METHOD__);

                $data['particularid']['id'] = $this->db->getLastId();
                $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
                $data['detid']['value'] = $this->encrypt->scramble($data['detid']['value'], 'detid', $params['userid']);
                $data['particularid']['value'] = $this->encrypt->scramble($data['particularid']['id'], 'particularid', $params['userid']);

                if (null === $data['hierachyid']['value']) {
                    throw new Exception('Unable to encrypt hierachyid.');
                }
                if (null === $data['detid']['value']) {
                    throw new Exception('Unable to encrypt detid.');
                }
                if (null === $data['particularid']['value']) {
                    throw new Exception('Unable to encrypt particularid.');
                }

                // $this->log([
                //     'Location' => __METHOD__.'(): 4',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ((int) $data['particularid']['id'] > 0) {
                    $sql = 'insert into hierachy_images(particularid, logo, name, width, height, size)
                            values(?,?,?,?,?,?)';

                    $result = $this->db->query([
                        $data['particularid']['id'],
                        $data['hierachy_profile_logo']['value']['content'],
                        $data['hierachy_profile_logo']['value']['name'],
                        $data['hierachy_profile_logo']['value']['width'],
                        $data['hierachy_profile_logo']['value']['height'],
                        $data['hierachy_profile_logo']['value']['size'],
                    ], $sql, __METHOD__);

                    $data['imageid']['value'] = $this->db->getLastId();

                    if ($data['imageid'] < 1) {
                        throw new Exception('Could not insert hierachy_images.');
                    }
                } else {
                    throw new Exception('Could not insert hierachy_particulars.');
                }

                $this->log([
                    'Location' => __METHOD__.'(): 5',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            }

            if (null === $data) {
                throw new Exception('Could not insert Particulars');
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
            $data = null;
        }

        return $data;
    }

    public function update($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $data['status'] = null;

        try {
            // $this->log([
            //     'Location' => __METHOD__.'(): 1',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            $params['formArray']['hierachyid'] = (int) $this->encrypt->unscramble($params['formArray']['hierachyid'], 'hierachyid');
            $params['formArray']['detid'] = (int) $this->encrypt->unscramble($params['formArray']['detid'], 'detid');
            $params['formArray']['particularid'] = (int) $this->encrypt->unscramble($params['formArray']['particularid'], 'particularid');
            $params['formArray']['imageid'] = (int) $this->encrypt->unscramble($params['formArray']['imageid'], 'imageid');
            $params['formArray']['contactuserid'] = (int) $this->encrypt->unscramble($params['formArray']['contactuserid'], 'userid');

            // $this->log([
            //     'Location' => __METHOD__.'(): 2',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            // ]);

            if (null === $params['formArray']['hierachyid']) {
                throw new Exception('Unable to decrypt HierachyId');
            }
            if (null === $params['formArray']['detid']) {
                throw new Exception('Unable to decrypt detid');
            }
            if (null === $params['formArray']['particularid']) {
                throw new Exception('Unable to decrypt particularid');
            }
            if (null === $params['formArray']['imageid']) {
                throw new Exception('Unable to decrypt imageid');
            }
            if (null === $params['formArray']['contactuserid']) {
                throw new Exception('Unable to decrypt contactuserid');
            }

            $data = $this->validate->isValid('Particulars', $params['formArray']);

            if (!is_null($data)) {
                // $this->log([
                //     'Location' => __METHOD__.'(): 3',
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if ($data['return'] > 0) {
                    return $data;
                }

                $sql = 'update hierachy_particulars
                           set contactuserid = ?,
                               phone = ?, 
                               address = ?, 
                               ispostal = ?, 
                               website = ?, 
                               postal = ?
                         where detid = ?
                           and particularid = ?;';

                $result = $this->db->query([
                    $data['contactuserid']['value'],
                    $data['phone']['value'],
                    $data['address']['value'],
                    ('on' === (string) $params['formArray']['hierachyispostal']) ? 1 : 0,
                    $data['website']['value'],
                    $data['postal']['value'],
                    $data['detid']['value'],
                    $data['particularid']['value'],
                ], $sql, __METHOD__);

                $rowcount = $this->db->getrowCount();
                $data['status'] = ($this->db->getStatus()) ? 1 : 0;

                // $this->log([
                //     'Location' => __METHOD__.'(): 4',
                //     'rowcount' => $rowcount,
                //     'success' => $data['status'],
                //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                // ]);

                if (0 === $data['status']) {
                    throw new Exception('Could not update hierachy_particulars.');
                }

                if (isset($data['hierachy_profile_logo']['value']['content']) && strlen($data['hierachy_profile_logo']['value']['content']) > 10) {
                    $sql = 'update hierachy_images
                               set logo = ?, 
                                   name = ?, 
                                   width = ?, 
                                   height = ?, 
                                   size = ?
                             where particularid = ?
                               and imageid = ?';

                    $result = $this->db->query([
                        $data['hierachy_profile_logo']['value']['content'],
                        $data['hierachy_profile_logo']['value']['name'],
                        $data['hierachy_profile_logo']['value']['width'],
                        $data['hierachy_profile_logo']['value']['height'],
                        $data['hierachy_profile_logo']['value']['size'],
                        $data['particularid']['value'],
                        $data['imageid']['value'],
                    ], $sql, __METHOD__);

                    $rowcount = $this->db->getrowCount();
                    $data['status'] += ($this->db->getStatus()) ? 1 : 0;

                    // $this->log([
                    //     'Location' => __METHOD__.'(): 5',
                    //     'rowcount' => $rowcount,
                    //     'success' => $data['status'],
                    //     'params' => json_encode($params, JSON_PRETTY_PRINT),
                    //     'data' => json_encode($data, JSON_PRETTY_PRINT),
                    // ]);

                    if ((string) $data['status'] < 1) {
                        throw new Exception('Could not update hierachy_images.');
                    }
                }

                $data['particularid']['id'] = $data['particularid']['value'];
                $data['particularid']['value'] = $this->encrypt->scramble($data['particularid']['value'], 'particularid', $params['userid']);
                $data['contactuserid']['id'] = $data['contactuserid']['value'];
                $data['contactuserid']['value'] = $this->encrypt->scramble($data['contactuserid']['value'], 'userid', $params['userid']);
                $data['hierachyid']['value'] = $this->encrypt->scramble($data['hierachyid']['value'], 'hierachyid', $params['userid']);
                $data['detid']['value'] = $this->encrypt->scramble($data['detid']['value'], 'detid', $params['userid']);
                $data['imageid']['value'] = $this->encrypt->scramble($data['imageid']['value'], 'imageid', $params['userid']);

                if (null === $data['hierachyid']['value']) {
                    throw new Exception('Unable to encrypt hierachyid.');
                }
                if (null === $data['detid']['value']) {
                    throw new Exception('Unable to encrypt detid.');
                }
                if (null === $data['particularid']['value']) {
                    throw new Exception('Unable to encrypt particularid.');
                }
                if (null === $data['contactuserid']['value']) {
                    throw new Exception('Unable to encrypt contactuserid.');
                }
                if (null === $data['imageid']['value']) {
                    throw new Exception('Unable to encrypt imageid.');
                }

                $this->log([
                    'Location' => __METHOD__.'(): 6',
                    'params' => json_encode($params, JSON_PRETTY_PRINT),
                    'data' => json_encode($data, JSON_PRETTY_PRINT),
                ]);
            }

            if (null === $data) {
                throw new Exception('Could not insert Particulars');
            }
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
            $data = null;
        }

        return $data;
    }

    public function show($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;
        $hierachyid = null;
        $detid = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = $this->encrypt->unscramble($params['formArray'][1], 'detid');

                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt HierachyId');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'hierachyid' => $hierachyid,
            //     'detid' => $detid,
            // ]);

            $sql = 'select h.hierachyid, hd.detid, hd.name,
                           case when isnull(hp.particularid) then 0 else hp.particularid end as particularid,
                           concat(uhp.firstname, " ", uhp.surname) as contactuser,
                           case when isnull(hp.ispostal) then 0 else hp.ispostal end as ispostal,
                           hp.phone, hp.address, hp.website, hp.postal, 
                           case when isnull(hi.imageid) then 0 else hi.imageid end as imageid,
                           case when isnull(hi.particularid) then null else hi.logo end as logo,
                           case when isnull(hi.particularid) then null else hi.name end as logoname,
                           case when isnull(hi.particularid) then null else hi.width end as logowidth,
                           case when isnull(hi.particularid) then null else hi.height end as logoheight,
                           case when isnull(hi.particularid) then null else hi.size end as logosize
                      from hierachy h
                      join hierachy_det hd
                        on h.hierachyid = hd.hierachyid
                      left join hierachy_particulars hp
                        on hd.detid = hp.detid
                      left join users uhp
                        on hp.contactuserid = uhp.userid
                      left join hierachy_images hi
                        on hp.particularid = hi.particularid
                     where h.hierachyid = ?
                       and hd.detid = ?;';

            $result = $this->db->query([
                $hierachyid,
                $detid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data = [
                    'id' => $res->particularid,
                    'hierachyname' => $res->name,
                    'hierachyid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    ],
                    'detid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->detid, 'detid', $params['userid']),
                    ],
                    'particularid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->particularid, 'particularid', $params['userid']),
                    ],
                    'contactuser' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->contactuser,
                    ],
                    'phone' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->phone,
                    ],
                    'address' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->address,
                    ],
                    'ispostal' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->ispostal,
                    ],
                    'website' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->website,
                    ],
                    'postal' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->postal,
                    ],
                    'imageid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->imageid, 'imageid', $params['userid']),
                    ],
                    'logo' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logo,
                    ],
                    'logoname' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logoname,
                    ],
                    'logowidth' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logowidth,
                    ],
                    'logoheight' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logoheight,
                    ],
                    'logosize' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logosize,
                    ],
                ];

                if (null === $data['hierachyid']) {
                    throw new Exception('Unable to encrypt hierachyid.');
                }
                if (null === $data['detid']) {
                    throw new Exception('Unable to encrypt detid.');
                }
                if (null === $data['particularid']) {
                    throw new Exception('Unable to encrypt particularid.');
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'hierachyid' => $hierachyid,
                'detid' => $detid,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function edit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);
        $data = null;

        try {
            if (sizeof($params['formArray']) > 1) {
                $hierachyid = $this->encrypt->unscramble($params['formArray'][0], 'hierachyid');
                $detid = $this->encrypt->unscramble($params['formArray'][1], 'detid');
                $particularid = $this->encrypt->unscramble($params['formArray'][2], 'particularid');

                if (null === $particularid) {
                    throw new Exception('Unable to decrypt particularid');
                }
                if (null === $detid) {
                    throw new Exception('Unable to decrypt detid');
                }
                if (null === $hierachyid) {
                    throw new Exception('Unable to decrypt hierachyid');
                }
            } else {
                throw new Exception('Form params do not meet the required length');
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'hierachyid' => $particularid,
            //     'detid' => $detid,
            // ]);

            $sql = 'select h.hierachyid, hd.detid, hd.name,
                           case when isnull(hp.particularid) then 0 else hp.particularid end as particularid,
                           hp.contactuserid,
                           case when isnull(hp.ispostal) then 0 else hp.ispostal end as ispostal,
                           hp.phone, hp.address, hp.website, hp.postal, 
                           case when isnull(hi.imageid) then 0 else hi.imageid end as imageid,
                           case when isnull(hi.particularid) then null else hi.logo end as logo,
                           case when isnull(hi.particularid) then null else hi.name end as logoname,
                           case when isnull(hi.particularid) then null else hi.width end as logowidth,
                           case when isnull(hi.particularid) then null else hi.height end as logoheight,
                           case when isnull(hi.particularid) then null else hi.size end as logosize
                      from hierachy h
                      join hierachy_det hd
                        on h.hierachyid = hd.hierachyid
                      left join hierachy_particulars hp
                        on hd.detid = hp.detid
                      left join users uhp
                        on hp.contactuserid = uhp.userid
                      left join hierachy_images hi
                        on hp.particularid = hi.particularid
                     where h.hierachyid = ?
                       and hd.detid = ?
                       and hp.particularid = ?;';

            $result = $this->db->query([
                $hierachyid,
                $detid,
                $particularid,
            ], $sql, __METHOD__);

            foreach ($result as $res) {
                $data = [
                    'hierachyname' => $res->name,
                    'hierachyid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->hierachyid, 'hierachyid', $params['userid']),
                    ],
                    'detid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->detid, 'detid', $params['userid']),
                    ],
                    'particularid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->particularid, 'particularid', $params['userid']),
                        'id' => $res->particularid,
                    ],
                    'contactuserid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->contactuserid, 'contactuserid', $params['userid']),
                        'id' => $res->contactuserid,
                    ],
                    'phone' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->phone,
                    ],
                    'address' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->address,
                    ],
                    'ispostal' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->ispostal,
                    ],
                    'website' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->website,
                    ],
                    'postal' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->postal,
                    ],
                    'imageid' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $this->encrypt->scramble($res->imageid, 'imageid', $params['userid']),
                    ],
                    'logo' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logo,
                    ],
                    'logoname' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logoname,
                    ],
                    'logowidth' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logowidth,
                    ],
                    'logoheight' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logoheight,
                    ],
                    'logosize' => [
                        'pass' => null,
                        'message' => null,
                        'value' => $res->logosize,
                    ],
                ];

                if (null === $data['hierachyid']) {
                    throw new Exception('Unable to encrypt hierachyid.');
                }
                if (null === $data['detid']) {
                    throw new Exception('Unable to encrypt detid.');
                }
                if (null === $data['particularid']) {
                    throw new Exception('Unable to encrypt particularid.');
                }
                if (null === $data['contactuserid']) {
                    throw new Exception('Unable to encrypt contactuserid.');
                }
                if (null === $data['imageid']) {
                    throw new Exception('Unable to encrypt imageid.');
                }
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'hierachyid' => $particularid,
                'detid' => $detid,
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'data' => json_encode($data, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);

            $data = null;
        }

        return $data;
    }

    public function callsUpdate($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['particulars'] = $this->encrypt->scramble('Hierachy\Particulars\Particulars', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['particulars']:
                    throw new Exception('Encryption failed of method: particulars');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
            }

            // $this->log([
            //     'Location' => __METHOD__.'()',
            //     'params' => json_encode($params, JSON_PRETTY_PRINT),
            //     'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            // ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
        }
    }

    public function callsShow($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['particulars'] = $this->encrypt->scramble('Hierachy\Particulars\Particulars', 'controller', $params['userid']);
            $calls['edit'] = $this->encrypt->scramble('edit', 'method', $params['userid']);
            $calls['applications'] = $this->encrypt->scramble('Hierachy\Applications\Applications', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['particulars']:
                    throw new Exception('Encryption failed of controller: particulars');

                    break;

                case null === $calls['edit']:
                    throw new Exception('Encryption failed of method: edit');

                    break;

                case null === $calls['applications']:
                    throw new Exception('Encryption failed of controller: applications');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
        }
    }

    public function callsEdit($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['particulars'] = $this->encrypt->scramble('Hierachy\Particulars\Particulars', 'controller', $params['userid']);
            $calls['update'] = $this->encrypt->scramble('update', 'method', $params['userid']);
            $calls['delete'] = $this->encrypt->scramble('delete', 'method', $params['userid']);

            switch (true) {
                case null === $calls['particulars']:
                    throw new Exception('Encryption failed of controller: particulars');

                    break;

                case null === $calls['update']:
                    throw new Exception('Encryption failed of method: update');

                    break;

                case null === $calls['delete']:
                    throw new Exception('Encryption failed of method: delete');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
        }
    }

    public function callsCreate($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['particulars'] = $this->encrypt->scramble('Hierachy\Particulars\Particulars', 'controller', $params['userid']);
            $calls['store'] = $this->encrypt->scramble('store', 'method', $params['userid']);
            $calls['applications'] = $this->encrypt->scramble('Hierachy\Applications\Applications', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['particulars']:
                    throw new Exception('Encryption failed of controller: particulars');

                    break;

                case null === $calls['store']:
                    throw new Exception('Encryption failed of method: store');

                    break;

                case null === $calls['applications']:
                    throw new Exception('Encryption failed of controller: applications');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
        }
    }

    public function callsCreated($params)
    {
        $this->log([
            'Location' => __METHOD__.'() - FLOW DIAGRAM',
        ]);

        try {
            $calls = [];

            $calls['particulars'] = $this->encrypt->scramble('Hierachy\Particulars\Particulars', 'controller', $params['userid']);
            $calls['show'] = $this->encrypt->scramble('show', 'method', $params['userid']);

            switch (true) {
                case null === $calls['particulars']:
                    throw new Exception('Encryption failed of controller: particulars');

                    break;

                case null === $calls['show']:
                    throw new Exception('Encryption failed of method: show');

                    break;
            }

            $this->log([
                'Location' => __METHOD__.'()',
                'params' => json_encode($params, JSON_PRETTY_PRINT),
                'calls' => json_encode($calls, JSON_PRETTY_PRINT),
            ]);
        } catch (Exception $e) {
            $calls = null;

            $this->error([
                'Location' => __METHOD__.'()',
                '$e' => $e->__toString(),
            ]);
        } finally {
            return $calls;
        }
    }

    protected function error($msg)
    {
        new Log($msg, 'error');
    }

    protected function log($msg)
    {
        new Log($msg, 'activity', 3);
    }
}
