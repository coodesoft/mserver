<?php

namespace lib\transcoder;

use Yii;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

class Transcoder{

  private static $instance;

  static function instance(){
    if (!isset(self::$instance)){
      self::$instance = new Transcoder;
    }
    return self::$instance;
  }


  function changeFormat($old, $new){
    $path = '/opt/lampp/htdocs/radioalbum/catalogo/social/qwe/01 - Vengo.mp3';
    $lala = 'ffmpeg -i '.$path.' -c:a libfdk_aac -b:a 128k output.m4a';
    exec($lala, $o, $v);
    var_dump($o);
    var_dump($v);
  }

  static function create(){
    return FFMpeg::create(array(
      'ffmpeg.binaries'  => Yii::getAlias('@lib').'/ffmpeg',
      'ffprobe.binaries' => Yii::getAlias('@lib').'/ffprobe')
    );
  }

  static function ffmpeg($logger = null){
    return FFMpeg::create( array('ffmpeg.binaries'  => Yii::getAlias('@lib').'/ffmpeg') );
  }

  static function ffprobe($logger = null){
    return FFProbe::create(array( 'ffprobe.binaries' => Yii::getAlias('@lib').'/ffprobe') );
  }

}
