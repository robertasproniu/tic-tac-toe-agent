<?php

namespace TicTacToeAgent\Contracts;

interface MoveInterface
{
    public function makeMove (array $boardState, $player = Game::PLAYER_X);
}