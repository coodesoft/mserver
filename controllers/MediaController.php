<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\User;
use app\models\Song;

use FFMpeg\FFMpeg;
use lib\transcoder\Transcoder;
use FFMpeg\Format\Audio\Mp3;

class MediaController extends Controller{

  public function behaviors(){
      return [
          'access' => [
              'class' => AccessControl::className(),
              'rules' => [
                  [
                      'actions' => ['logout', 'song', 'test'],
                      'allow' => true,
                      'roles' => ['?'],
                  ],
              ],
          ],
      ];
  }

  public function actionSong(){
    $songId = Yii::$app->request->get('id');

    $userId = Yii::$app->request->get('u');
    $timestamp = Yii::$app->request->get('t');
    $token = Yii::$app->request->get('token');

    $user = User::findOne($userId);
    $tokenSource = $timestamp . $userId . $user->password_hash;


    if (Yii::$app->getSecurity()->validatePassword($tokenSource, $token)){

      $song = Song::findOne($songId);
      return Yii::$app->response->sendFile( $song->path_song )->send();
    }else
      throw new \Exception("Unauthorized Request", 1);
  }

  public function actionTest(){
    $ffmpeg =  Yii::getAlias('@lib').'/ffmpeg';
    $path = '/opt/lampp/htdocs/radioalbum/catalogo/social/qwe/01\ -\ Vengo.mp3';
    $lala = $ffmpeg.' -i '.$path.' -f mp3 -b:a 128k pipe:1';

    /*
    exec($lala, $o, $v);
    var_dump($o);
    var_dump($v);
    */
    $descriptorspec = array(
       0 => array("pipe", "r"),  // stdin es una tubería usada por el hijo para lectura
       1 => array("pipe", "w"),  // stdout es una tubería usada por el hijo para escritura
       2 => array("file", "/dev/null", "a") // stderr es un fichero para escritura
    );
    $cwd = '/tmp';
    $env = array('some_option' => 'aeiou');
    $out = '';
    $return = '';
    return Yii::$app->response->sendStreamAsFile( $out, 'Vengo.mp3' )->send();

    return $out;

  /*  $process = proc_open($lala, $descriptorspec, $pipes, $cwd, null);
    if (is_resource($process)) {
        // $pipes ahora será algo como:
        // 0 => gestor de escritura conectado al stdin hijo
        // 1 => gestor de lectura conectado al stdout hijo
        // Cualquier error de salida será anexado a /tmp/error-output.txt

        fclose($pipes[0]);

        echo stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Es importante que se cierren todas las tubería antes de llamar a
        // proc_close para evitar así un punto muerto
        $return_value = proc_close($process);


    }
    */
  }
}
