<?php

namespace TicTacToeAgent\Contracts;

interface BoardInterface {

    /**
     * Get current board
     *
     * @return array
     */
    public function getBoard();

    /**
     * Set board with particular values
     *
     * @param array $board
     * @return array
     */
    public function setBoard(array $board);


    /**
     * Clear current board
     *
     * @return array
     */
    public function clearBoard();


    /**
     * Set value for a pa particular given position
     *
     * @param array $position
     * @param $value
     */
    public function setValue(array $position, $value);

    /**
     * Get value for a particular given position
     *
     * @param array $position
     * @return string|null
     */
    public function getValue(array $position);

    /**
     * Check if board is full
     *
     * @return bool
     */
    public function isFull();
}