const SHA256 = require('crypto-js/sha256');

class Block{
    constructor(index, timestamp, data, previousHash ='' ){
        this.index = index;
        this.timestamp = timestamp;
        this.data = data;
        this.previousHash = previousHash;
        this.nonce = 0;
        this.hash = this.calculateHash()
    }

    calculateHash(){
        return SHA256(this.index+this.timestamp+this.previousHash+JSON.stringify(this.data)+this.nonce).toString();
    }
}

class BlockChain{
    constructor(){
        this.chain = [this.createGenesisBlock()];
        this.diffcltiy = 4;
    }

    createGenesisBlock(){
        return new Block(0, "01/01/2021", "Genesis Block", "0")
    }
    getLatestBlock(){
        return this.chain[this.chain.length-1] ;
    }

    push(block){
        block.previousHash = this.getLatestBlock();
        this.mine(block);
        this.chain.push(block)
    }

    mine(block){
        while(block.hash.substring(0,this.diffcltiy) !== "0".repeat(this.diffcltiy)){
            block.nonce ++;
            block.hash = block.calculateHash();
        }
        console.log("Block mined: " + block.hash + "\n\n");
    }

    isValid(){
        for(var i=0; i<this.chain.length; i++){
            var currentBlock = this.chain[i];
            var  previousBlock = this.chain[i-1];

            if(currentBlock.hash != currentBlock.calculateHash()){
                return false;
            }
            if(currentBlock.previousHash != previousBlock.hash){
                return false;
            }
            return true;
        }
    }

}

const pinCoin = new BlockChain;
console.log("mining block1.....")
pinCoin.push(new Block(1, "02/02/2021", {"amount" : 54}))

console.log("mining block2.....")
pinCoin.push(new Block(2, "23/2/2025", { "amount": 5 }))

console.log("The blockChain: " + JSON.stringify(pinCoin, 0 , 4))
