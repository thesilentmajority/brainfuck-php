<?php

class Brainfuck{
    // Basic characters in Brainfuck
    // Ref : https://en.wikipedia.org/wiki/Brainfuck
    const OPERATORS = [
        // > Increment the data pointer by one (to point to the next cell to the right).
        '>' => 1,
        // < Decrement the data pointer by one (to point to the next cell to the left).
        '<' => 2,
        // + Increment the byte at the data pointer by one.
        '+' => 3,
        // - Decrement the byte at the data pointer by one.
        '-' => 4,
        // . Output the byte at the data pointer.
        '.' => 5,
        // , Accept one byte of input, storing its value in the byte at the data pointer.
        ',' => 6,
        // [ If the byte at the data pointer is zero, then instead of moving the instruction pointer forward to the next command, jump it forward to the command after the matching ] command.
        '[' => 7,
        // ] If the byte at the data pointer is nonzero, then instead of moving the instruction pointer forward to the next command, jump it back to the command after the matching [ command.
        ']' => 8
    ];
    
    private $debug = false;

    private static function getchar() {
        $char = fgetc(STDIN);
        return $char !== false ? ord($char) : false;
    }

    public function complie($code){
        $program = [];
        $program_counter = 0;
        $jump_stack = [];

        $code_length = strlen($code);
        // Todo: Optimize Brainfuck: Combine consecutive ops. Time: O(n), Space: O(1).
        for($i = 0; $i < $code_length; $i++){
            $char = $code[$i];
            $valid_char = 1;
            switch($char){
                case '>':
                    $program[] = array(self::OPERATORS['>'], 0);
                    break;
                case '<':
                    $program[] = array(self::OPERATORS['<'], 0);
                    break;
                case '+':
                    $program[] = array(self::OPERATORS['+'], 0);
                    break;
                case '-':
                    $program[] = array(self::OPERATORS['-'], 0);
                    break;
                case '.':
                    $program[] = array(self::OPERATORS['.'], 0);
                    break;
                case ',':
                    $program[] = array(self::OPERATORS[','], 0);
                    break;
                case '[':
                    $program[] = array(self::OPERATORS['['], 0);
                    $jump_stack[] = $program_counter;
                    break;
                case ']':
                    if(empty($jump_stack)){
                        throw new ErrorException("Syntax error: Unmatched ']' at position $i");
                    }
                    $start_pos = array_pop($jump_stack);
                    $program[] = array(self::OPERATORS[']'], $start_pos);
                    $program[$start_pos][1] = $program_counter;
                    break;
                default:
                    // Ignore other characters as comments
                    $valid_char = 0;
                    break;
            }
            if($valid_char){
                $program_counter = $program_counter + 1;
            }
        }

        if (!empty($jump_stack)) {
            $position = array_pop($jump_stack);
            throw new ErrorException("Syntax error: Unmatched '[' at position $position");
        }

        return $program;
    }

    public function execute($program){
        $data = array_fill(0, 65535, 0);
        $data_pointer = 0;
        $program_pointer = 0;
        $program_length = count($program);
        while($program_pointer < $program_length){
            $instruction = $program[$program_pointer];
            switch($instruction[0]){
                case self::OPERATORS['>']:
                    $data_pointer = $data_pointer + 1;
                    if($this->debug){echo "Data pointer: $data_pointer\n";} // Debug
                    break;
                case self::OPERATORS['<']:
                    $data_pointer = $data_pointer - 1;
                    if($this->debug){echo "Data pointer: $data_pointer\n";} // Debug
                    break;
                case self::OPERATORS['+']:
                    $data[$data_pointer] = $data[$data_pointer] + 1;
                    if($this->debug){echo "Data: $data[$data_pointer]\n";} // Debug
                    break;
                case self::OPERATORS['-']:
                    $data[$data_pointer] = $data[$data_pointer] - 1;
                    if($this->debug){echo "Data: $data[$data_pointer]\n";} // Debug
                    break;
                case self::OPERATORS['.']:
                    echo chr($data[$data_pointer]);
                    break;
                case self::OPERATORS[',']:
                    $char = self::getchar();
                    if ($char !== false) {
                        $data[$data_pointer] = $char;
                    } else {
                        $data[$data_pointer] = 0; // EOF
                    }
                    if($this->debug){echo "Input: $char\n";} // Debug
                    break;
                case self::OPERATORS['[']:
                    if($data[$data_pointer] == 0){
                        $program_pointer = $instruction[1];
                    }
                    break;
                case self::OPERATORS[']']:
                    if($data[$data_pointer] > 0){
                        $program_pointer = $instruction[1];
                    }
                    break;
            }
            $program_pointer = $program_pointer + 1;
            if($this->debug)sleep(1); // Debug
        }
    }


}


if(isset($argv[1])){
    if(!file_exists($argv[1])){
        echo "File not found\n";
        exit();
    }
}
else{
    echo "Brainfuck script file not given\n";
    exit();
}
$start = microtime(true);
$bf_script = file_get_contents($argv[1]);
$bf = new Brainfuck();
$program = $bf->complie($bf_script);
$bf->execute($program);
$end = microtime(true);
echo "Time taken: " . ($end - $start) . " seconds\n";
?>