<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "song".
 *
 * @property int $id
 * @property string $name
 * @property string $path_song
 * @property int $time
 * @property int $bitrate
 * @property int $rate
 * @property int $size
 * @property string $id_referencia
 * @property int $album_id
 *
 * @property PlaylistHasSong[] $playlistHasSongs
 * @property Playlist[] $playlists
 * @property Album $album
 * @property SongHasGenre[] $songHasGenres
 * @property Genre[] $genres
 */
class Song extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'song';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'bitrate', 'rate', 'size', 'album_id'], 'integer'],
            [['album_id'], 'required'],
            [['name', 'id_referencia'], 'string', 'max' => 45],
            [['path_song'], 'string', 'max' => 400],
            [['album_id'], 'exist', 'skipOnError' => true, 'targetClass' => Album::className(), 'targetAttribute' => ['album_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path_song' => 'Path Song',
            'time' => 'Time',
            'bitrate' => 'Bitrate',
            'rate' => 'Rate',
            'size' => 'Size',
            'id_referencia' => 'Id Referencia',
            'album_id' => 'Album ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylistHasSongs()
    {
        return $this->hasMany(PlaylistHasSong::className(), ['song_id' => 'id'])->inverseOf('song');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaylists()
    {
        return $this->hasMany(Playlist::className(), ['id' => 'playlist_id'])->viaTable('playlist_has_song', ['song_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbum()
    {
        return $this->hasOne(Album::className(), ['id' => 'album_id'])->inverseOf('songs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSongHasGenres()
    {
        return $this->hasMany(SongHasGenre::className(), ['song_id' => 'id', 'song_Album_id' => 'album_id'])->inverseOf('song');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGenres()
    {
        return $this->hasMany(Genre::className(), ['id' => 'genre_id'])->viaTable('song_has_genre', ['song_id' => 'id', 'song_Album_id' => 'album_id']);
    }
}
