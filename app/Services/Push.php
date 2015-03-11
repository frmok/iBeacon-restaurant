<?php 
namespace App\Services;
class Push{
    public function sendNotification(){
        $data = json_decode($data);
        if (!isset($data->message)) {
            $response = array();
            $response['status'] = 'ERROR';
            $response['code']   = 500;
            $response['debug']  = 'Wrong parameters';
            return Response::json($response);
        }
        $passphrase = '123456';
        $message = $data->message;
        $identifiers = $data->identifiers;
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'qk.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        echo 'Connected to APNS' . PHP_EOL;
        $body['aps'] = array(
            'alert' => $message,
            'sound' => 'default'
            );
        $payload = json_encode($body);
        if(count($identifiers) > 0){
            $tokens = DB::table('pushmessage')
            ->whereIn('identifier', $identifiers)->get();
            foreach ($tokens as $token) {
                $msg = chr(0) . pack('n', 32) . pack('H*', $token->token) . pack('n', strlen($payload)) . $payload;
                $result = fwrite($fp, $msg, strlen($msg));
            }
        }
        fclose($fp);
    }
}
?>