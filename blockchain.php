<?php

class  Block
{
    public $nonce;
    public function __construct($index, $timestamp, string $data, $previousHash = null)
    {
        $this->index = $index;
        $this->timestamp = $timestamp;
        $this->data = (string) $data;
        $this->previousHash = $previousHash;
        $this->nonce = 0;
        $this->hash = $this->calculateHash();
    }

    public function calculateHash()
    {

        return hash("sha256", $this->index . $this->timestamp . $this->data . $this->previousHash . $this->nonce);
    }
}

class BlockChain
{
    public function __construct()
    {
        $this->chain = [$this->createGenesisBlock()];
        $this->difficulty = 4;
    }
    private function createGenesisBlock()
    {
        // index, timestamp, datat
        return new Block(0, strtotime("2021-01-01"), "Genesis Block");
    }

    public function getLastBlock()
    {
        return $this->chain[count($this->chain) - 1];
    }
    public function push($block)
    {
        $block->previousHash = $this->getLastBlock()->hash;
        $this->mine($block);
        array_push($this->chain, $block);
    }

    public function mine($block)
    {
        while (substr($block->hash, 0, $this->difficulty) != str_repeat("0", $this->difficulty)) {
            $block->nonce++;
            $block->hash = $block->calculateHash();
        }
        echo "Block mined: " . $block->hash . "\n\n";
    }

    public function isValid()
    {
        for ($i = 0; $i < count($this->chain); $i++) {
            $currentBlock = $this->chain[$i];
            $previousBlock = $this->chain[$i - 1];

            if ($currentBlock->hash != $currentBlock->calculateHash()) {
                return false;
            }
            if ($currentBlock->previousHash != $previousBlock->hash) {
                return false;
            }
        }
        return true;
    }


}


$pincoin = new BlockChain;
echo "mining block1....".PHP_EOL;
$pincoin->push(new Block(1, strtotime("now"), "amount: 56"));

echo "<br>mining block 2...".PHP_EOL;
$pincoin->push(new Block(2,strtotime("now"), "amount:78"));

// in json format
echo json_encode($pincoin, JSON_PRETTY_PRINT);

