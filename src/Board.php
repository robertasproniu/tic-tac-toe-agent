<?php


namespace TicTacToeAgent;

use TicTacToeAgent\Contracts\BoardInterface;

class Board implements BoardInterface, \Countable
{
    private $size = 3;

    private $board = [];

    public function __construct()
    {
        $this->initBoard();
    }

    /**
     * Set Board Values
     *
     * @param array $board
     * @return array
     *
     * @throws \Exception
     */

    public function setBoard(array $board)
    {
        if (empty($board)) {
            return false;
        }

        // is not associative array and total size of array is not the same as board size then invalidate board
        if (($boardSize = count($board, COUNT_RECURSIVE)) == count($board) && $boardSize != pow($this->size, 2)) {
            return false;
        }

        // if not associative then set the boar as one
        if (count($board, COUNT_RECURSIVE) == count($board)) {
            $this->board = array_chunk($board, $this->size);
        } else {
            $this->board = $board;
        }

        // if distinct values is greater than 2 then invalidate board
        $boardDistinctValues = array_unique(array_filter(array_reduce($this->board, 'array_merge', [])));
        if (count($boardDistinctValues) > 2) {
            return false;
        }

        return $this->board;
    }

    /**
     * Get board
     *
     * @return array
     */

    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Clear board
     *
     * @return array
     */

    public function clearBoard()
    {
        $this->initBoard();
    }

    /**
     * Set value to a particular position on board
     *
     * @param array $position
     * @param $value
     *
     * @
     */

    public function setValue(array $position, $value)
    {
        if (!$this->validatePosition($position)) {
           return false;
        }

        list ($xPos, $yPos) = $position;

        if (!empty($this->board[$xPos][$yPos])) {
            return false;
        }

        $this->board[$xPos][$yPos] = $value;

    }

    /**
     *
     * Get the value from particular position
     *
     * @param array $position
     * @return string
     *
     */

    public function getValue(array $position)
    {
        if (!$this->validatePosition($position)) {
            return false;
        }

        list ($xPos, $yPos) = $position;

        return $this->board[ (int) $xPos][ (int) $yPos];
    }

    /**
     * Check if board is full
     * @return bool
     */
    public function isFull()
    {
        return (bool) (pow($this->size, 2) == count(array_filter(array_reduce($this->getBoard(), 'array_merge', []))));
    }

    public function count()
    {
        return $this->size;
    }

    /**
     * Validate position
     *
     * @param array $position
     * @return bool
     */
    private function validatePosition(array $position)
    {
        if (empty($position)
            || count($position) != 2
            || count($position) != count(array_filter($position, 'is_numeric'))
        ) {
            return false;
        }

        return true;
    }

    /**
     * Initialize board by given size
     */
    private function initBoard()
    {
        $this->setBoard(array_fill(0, pow($this->size, 2), null));
    }
}