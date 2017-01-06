<?php


namespace TicTacToeAgent;

use TicTacToeAgent\Contracts\BoardInterface;
use TicTacToeAgent\Contracts\GameInterface;

abstract class Game implements GameInterface
{

    const PLAYER_X = 'X';
    const PLAYER_O = 'O';

    protected $board = [];

    private $players = [];

    private $positions = [];

    function __construct(BoardInterface $board)
    {
        // set board object
        $this->board = $board;

        // set players
        $this->setPlayers([
            self::PLAYER_X,
            self::PLAYER_O
        ]);

        // get winning positions
        $this->positions = $this->winningPositions();
    }
    /**
     * Get game players
     * @return array
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Set players
     *
     * @param array $players
     * @return void
     *
     * @throws \Exception
     */

    public function setPlayers(array $players = [])
    {
        if (empty($players)
            || count($players) != 2
            || array_unique(array_filter($players)) < 2
        ) {
            return false;
        }

        $this->players = $players;
    }

    /**
     * Get the current board
     * @return array
     *
     */
    public function getBoard()
    {
        return $this->board->getBoard();
    }

    /**
     * Set an existing board
     * @param array $board The board array
     * @return array
     *
     */
    public function setBoard(array $board)
    {
        $this->board->setBoard($board);

        $this->positions = $this->winningPositions();

        $this->isFinished();
    }

    /**
     * Get state o
     * @return object
     */
    public function getState()
    {
        return (object)[
            'active' => $this->isFinished() ? false : true,
            'winner' => $this->getWinner()
        ];
    }

    /**
     * return recommended value
     * @param string $player
     * @return array
     */
    protected function getMove($player = self::PLAYER_X)
    {
        $move = $this->calculateBestMove($player);

        if (!empty($move)) {
            $this->board->setValue($move, $player);
        }

        return $move;
    }

    /**
     * Check if the game has ended
     * @return bool
     *
     */
    private function isFinished()
    {
        // if board is full or we have a winner then game over,
        return (bool) ($this->board->isFull() || $this->getWinner());
    }

    /**
     * Get the winner of the game, if there is one
     * @return string|bool self::PLAYER_X or self::PLAYER_O if found, false if there's no winner
     *
     */

    private function getWinner()
    {
        foreach ($this->winningValues() as $values) {
            // skip for empty cells
            if (count(array_unique($values)) == 1 && empty(end($values))) {
                continue;
            }

            //hooray we have a winner
            if (count(array_unique($values)) == 1) {
                $winner = array_unique($values);
                return array_shift($winner);
            }
        }

        return false;
    }

    /**
     * Determine winning values
     *
     * @return array
     */
    private function winningValues()
    {
        return array_merge(
        // rows
            $this->board->getBoard(),
            // columns
            $this->transpose($this->board->getBoard()),
            // diagonals
            $this->diagonals()
        );
    }


    /**
     *
     * Determine winning positions for game
     *
     * @return array
     */

    private function winningPositions()
    {
        $board = count($this->board);

        $boardReverseArray = range($board - 1, 0);

        $winningPositions = [];

        foreach ($this->board->getBoard() as $positionX => $row) {

            $columnIndex = ($positionX + $board);

            foreach ($row as $positionY => $value) {
                // set winning positions on rows
                $winningPositions[$positionX][] = [$positionX, $positionY];

                //set winning positions on cols
                $winningPositions[$columnIndex][] = [$positionY, $positionX];
            }

            //set winning positions on diagonals
            $winningPositions[2 * $board][] = [$positionX, $positionX];

            $winningPositions[2 * $board + 1][] = [$boardReverseArray[$positionX], $positionX];
        }

        ksort($winningPositions);

        return $winningPositions;
    }

    /**
     * transpose array
     *
     * @param array $array
     * @return array
     */
    private function transpose(array $array)
    {
        array_unshift($array, null);

        return  call_user_func_array('array_map', $array);
    }

    /**
     * prepare diagonals
     *
     * @return array
     */

    private function diagonals()
    {

        $diagonals = array_fill(0, 2, []);

        $diagonalIndexes = range(0, count($this->board) - 1);

        $diagonalIndexesReverse = array_reverse($diagonalIndexes);

        foreach ($diagonalIndexes as $index => $value) {

            array_push($diagonals[0], $this->board->getValue([$value, $value]));

            array_push($diagonals[1], $this->board->getValue([$value, $diagonalIndexesReverse[$index]]));
        }

        return $diagonals;
    }


    /**
     * Get the best move of a player
     *
     * @param string $player
     * @param bool $stopOpponentPlayer Whether to try to stop the opponent from winning
     * @param int $playerMoveScore
     *
     * @return array The position of the best tile
     *
     */

    private function calculateBestMove($player, $stopOpponentPlayer = true, &$playerMoveScore = 0)
    {
        $winningMoves = []; // the best tile(s)

        $opponentMoveScore = 0; // the higher moveScore of the opponent

        $opponentMove = []; // keep opponent best move

        if (!$this->checkPlayer($player) || $this->isFinished()) {
            return $winningMoves;
        }

        // try to stop the opponent from winning
        if ($stopOpponentPlayer) {
            // get the opponent's best move
            $opponentMove = $this->calculateBestMove($this->getOpponentPlayer($player), false, $opponentMoveScore);
        }

        foreach ($this->board->getBoard() as $positionX => $col) {

            foreach ($col as $positionY => $tile) {

                $moveScore = $this->calculateMoveScore($player, [$positionX, $positionY]);

                if (!is_null($moveScore)) {
                    if ($moveScore > $playerMoveScore) {

                        $winningMoves = [[$positionX, $positionY]];
                        $playerMoveScore = $moveScore;

                        continue;
                    }

                    if ($moveScore == $playerMoveScore) {

                        array_push($winningMoves, [$positionX, $positionY]);

                        continue;
                    }
                }
            }
        }

        if ($stopOpponentPlayer && $opponentMoveScore >= 10 && $playerMoveScore < 10) {
            return $opponentMove;
        }

        if (count($winningMoves)) {

            return $winningMoves[array_rand($winningMoves)];
        }

        return $winningMoves;
    }

    /**
     * Check if a player is valid
     *
     * @param string $player symbol representing a player
     * @return bool
     *
     */
    private function checkPlayer($player)
    {
        if (!in_array($player, $this->players)) {
            return false;
        }

        return true;
    }

    /**
     * Get the opponent of a player
     * @param int $player The player
     * @return int|bool The integer representing the opponent if found
     *
     */
    private function getOpponentPlayer($player)
    {
        if (!in_array($player, $this->players)) {
            return false;
        }

        $opponentArray = array_diff($this->players, [$player]);

        return array_shift($opponentArray);
    }

    /**
     * Get the score of a move
     *
     * @param int $player The player
     * @param array $position
     *
     * @return int The overall rating (the higher the better)
     */
    private function calculateMoveScore($player, array $position)
    {
        if ($this->board->getValue($position)) {
            return null;
        }

        $totalScore = 0;

        foreach ($this->positions as $row) {

            if (!in_array($position, $row)) {
                continue;
            }

            $countOpponentMoves = 0;
            $countPlayerMoves = 0;

            foreach ($row as $positionTile) {

                $positionValue = $this->board->getValue($positionTile);
                if ($positionValue) {

                    $countOpponentMoves++;

                    if ($positionValue == $player) {
                        $countPlayerMoves++;

                        $countOpponentMoves--;
                    }

                }

            }


            if (!$countOpponentMoves && $countPlayerMoves == 2) {
                $totalScore += 10;
                continue;

            }
            if (!$countOpponentMoves && $countPlayerMoves == 1) {
                $totalScore += 2;
                continue;

            }
            if (!$countOpponentMoves && !$countPlayerMoves) {
                $totalScore++;
                continue;
            }
        }
        return $totalScore;
    }
}
