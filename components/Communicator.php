<?php

namespace app\components;

use Yii;
use yii\helpers\Json;

class Communicator
{
    private static function initCurl($param)
    {
        $apiAuth = Yii::$app->session->get('APIAuth');
        $resource = curl_init(Yii::$app->params['APIServer'] . $param);
        curl_setopt($resource, CURLOPT_RETURNTRANSFER , true);

        $user = Yii::$app->getUser()->getIdentity();

        if ($apiAuth) {
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Basic '. $apiAuth
            );

            curl_setopt($resource, CURLOPT_HTTPHEADER, $headers);
        }

        return $resource;
    }

    private static function getCurlResult($resource)
    {
        $res = curl_exec($resource);

        if (!curl_errno($resource)) {
            if (curl_getinfo($resource, CURLINFO_HTTP_CODE) == 200) {
                $result = ($res != '') ? JSON::decode($res) : null;
            }
        }
        curl_close($resource);

        return $result ?? [];
    }

    public static function getAllProduct()
    {
        return  self::getCurlResult(self::initCurl('/products'));
    }

    public static function getProduct(int $id)
    {
        return self::getCurlResult(self::initCurl('/products/' . $id));
    }

    public static function insertProduct(array $data)
    {
        $data = json_encode($data);

        $resource = self::initCurl('/products');
        curl_setopt($resource, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($resource, CURLOPT_PROXY_SSL_VERIFYPEER, false);
        curl_setopt($resource, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic '. $_SESSION['APIAuth'],
            'Content-Length: '.strlen($data), 'charset=UTF-8'
        ));
        curl_setopt($resource, CURLOPT_POSTFIELDS, $data);

        return self::getCurlResult($resource);
    }

    public static function updateProduct(array $data)
    {
        $resource = self::initCurl('/products/' . $data['id']);

        $data = json_encode($data);

        curl_setopt($resource, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($resource, CURLOPT_PROXY_SSL_VERIFYPEER, false);
        curl_setopt($resource, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic '. $_SESSION['APIAuth'],
            'Content-Length: '.strlen($data), 'charset=UTF-8'
        ));
        curl_setopt($resource, CURLOPT_POSTFIELDS, $data);

        return self::getCurlResult($resource);
    }

    public static function deleteProduct($id)
    {
        $resource = self::initCurl('/products/' . $id);
        curl_setopt($resource, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return self::getCurlResult($resource);
    }
}
