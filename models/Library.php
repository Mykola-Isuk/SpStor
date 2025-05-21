<?php

namespace models;

use core\Core;

class Library
{
    public static function addGame($userId, $gameId)
    {
        Core::getInstance()->db->insert('library', [
            'user_id' => $userId,
            'game_id' => $gameId
        ]);
    }

    public static function deleteGame($userId, $gameId)
    {
        Core::getInstance()->db->delete('library', [
            'user_id' => $userId,
            'game_id' => $gameId
        ]);
    }

    public static function isGameInLibrary($userId, $gameId)
    {
        $result = Core::getInstance()->db->select('library', '*', [
            'user_id' => $userId,
            'game_id' => $gameId
        ]);

        return !empty($result);
    }

    public static function getGame($userId)
    {
        return Core::getInstance()->db->select('library', '*', [
            'user_id' => $userId
        ]);
    }
}

