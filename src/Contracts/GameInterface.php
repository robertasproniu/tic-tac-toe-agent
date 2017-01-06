<?php

namespace TicTacToeAgent\Contracts;


interface GameInterface
{
    /**
     *
     * GameInterface constructor.
     * @param BoardInterface $board
     */
    public function __construct(BoardInterface $board);

    /**
     * Set players of
     *
     * @param array $players
     * @return mixed
     */

    public function setPlayers(array $players);

    /**
     * Get players of game
     *
     * @return array
     */
    public function getPlayers();


    /**
     * Set Game Board State
     *
     * @param array $board
     * @return mixed
     */
    public function setBoard(array $board);

    /**
     * Get Game Board state
     *
     * @return array
     */
    public function getBoard();

    /**
     * Get game state
     *
     * @return mixed
     */
    public function getState();

}