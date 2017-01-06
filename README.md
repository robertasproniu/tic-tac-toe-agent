# tic-tac-toe agent

A PHP Tic Tac Toe Game package with AI included

for installing just type:


## Installing
You can install the library via Composer, or by downloading it directly on GitHub.

### Composer
Set up a Composer project, then run the following command:
```
php composer.phar require robertasproniu/tic-tac-toe-agent
```
 
 
 ## Basic usage
 To create a game instance, use:
 ```php
 <?php
 require_once __DIR__ . '/vendor/autoload.php'; // Composer
 // OR
 require_once __DIR__ . '/src/TicTacToe.php'; // ZIP download
 
 use \TicTacToeAgent\TicTacToe;
 use \TicTacToeAgent\Board;
 
 $game = new TicTacToe(new Board()); // Create a new game
 ```
 Now, you have created a new game with a empty board. 
 
 You can see the board by using the `getBoard()` method:
 ```php
 print_r( $game->getBoard() );
 ```

Also you can set players symbols by accessing `setPlayers()` method:
 ```php
$game->setPlayers(['X', 'O']);
 ```

For getting recommended move for a player you need to pass board and player symbol 
by using `makeMove(array $board, $player )` method:
 ```php
$move = $game->makeMove(array_fill(0, 9, null), 'X'); // board can be multidimensional array too

print_r($move); // [2,0,'X'] if available position else [];

 ```