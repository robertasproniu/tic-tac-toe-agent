<?php


namespace TicTacToeAgent;

use TicTacToeAgent\Contracts\MoveInterface;

class TicTacToe extends Game implements MoveInterface
{
    /**
     * Get move for a particular player
     *
     * @param array $boardState
     * @param string $player
     * @return array
     */

    public function makeMove(array $boardState, $player = Game::PLAYER_A)
    {
        $this->setBoard($boardState);

        $recommendedMove =  $this->getMove($player) ?: [];

        return $recommendedMove ? array_merge($recommendedMove , [$player]): [];
    }

}