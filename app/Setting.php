<?php namespace App;
class Setting{
    public static function getName()
    {
        if (\Cache::has('sitename')){
            return \Cache::get('sitename');
        }else{
            $shopName = \DB::table('setting')->where('key', '=', 'sitename')->first()->value;
            \Cache::put('sitename', $shopName, 3600);
            return $shopName;
        }

    }
    public static function getUUID()
    {
        if (\Cache::has('uuid')){
            return \Cache::get('uuid');
        }else{
            $uuid = \DB::table('setting')->where('key', '=', 'uuid')->first()->value;
            \Cache::put('uuid', $uuid, 3600);
            return $uuid;
        }
    }
    public static function getMajor()
    {
        if (\Cache::has('beaconmajor')){
            return \Cache::get('beaconmajor');
        }else{
            $beaconmajor = \DB::table('setting')->where('key', '=', 'beaconmajor')->first()->value;
            \Cache::put('beaconmajor', $beaconmajor, 3600);
            return $beaconmajor;
        }
    }
    public static function getMinor()
    {
        if (\Cache::has('beaconminor')){
            return \Cache::get('beaconminor');
        }else{
            $beaconminor = \DB::table('setting')->where('key', '=', 'beaconminor')->first()->value;
            \Cache::put('beaconminor', $beaconminor, 3600);
            return $beaconminor;
        }
    }

    public static function getMallSystem()
    {
        if (\Cache::has('mallsystem')){
            return \Cache::get('mallsystem');
        }else{
            $mallsystem = \DB::table('setting')->where('key', '=', 'mallsystem')->first()->value;
            \Cache::put('mallsystem', $mallsystem, 3600);
            return $mallsystem;
        }
    }

    public static function getMallUser()
    {
        if (\Cache::has('malluser')){
            return \Cache::get('malluser');
        }else{
            $malluser = \DB::table('setting')->where('key', '=', 'malluser')->first()->value;
            \Cache::put('malluser', $malluser, 3600);
            return $malluser;
        }
    }

    public static function getMallpw()
    {
        if (\Cache::has('mallpw')){
            return \Cache::get('mallpw');
        }else{
            $mallpw = \DB::table('setting')->where('key', '=', 'mallpw')->first()->value;
            \Cache::put('mallpw', $mallpw, 3600);
            return $mallpw;
        }
    }

    public static function getIP()
    {
        if (\Cache::has('ip')){
            return \Cache::get('ip');
        }else{
            $ip = \DB::table('setting')->where('key', '=', 'ip')->first()->value;
            \Cache::put('ip', $ip, 3600);
            return $ip;
        }
    }

    public static function getPort()
    {
        if (\Cache::has('port')){
            return \Cache::get('port');
        }else{
            $port = \DB::table('setting')->where('key', '=', 'port')->first()->value;
            \Cache::put('port', $port, 3600);
            return $port;
        }
    }
    public static function getPPort()
    {
        if (\Cache::has('pport')){
            return \Cache::get('pport');
        }else{
            $pport = \DB::table('setting')->where('key', '=', 'pport')->first()->value;
            \Cache::put('pport', $pport, 3600);
            return $pport;
        }
    }
}
