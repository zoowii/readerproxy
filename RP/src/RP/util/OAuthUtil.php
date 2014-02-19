<?php
/**
 * Created by PhpStorm.
 * User: zoowii
 * Date: 14-2-19
 * Time: 下午11:19
 */

namespace RP\util;


use RP\models\AccountBinding;
use RP\models\User;

class OAuthUtil
{
    public static function getRelatedUser($type, $openid)
    {
        $accountBinding = AccountBinding::findOneByAttributes(array(
            'source' => $type,
            'binding_id' => $openid
        ));
        if (is_null($accountBinding)) {
            return null;
        }
        $user = $accountBinding->getRelatedUser();
        return $user;
    }

    public static function getDisplayName($type, $userinfo)
    {
        switch ($type) {
            case 'qq':
            {
                return $userinfo['nickname'];
            }
                break;
            default:
                {
                // TODO:
                throw new \Exception("Unsupported oauth2 type");
                }
                break;
        }
    }

} 