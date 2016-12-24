<?php
/**
* Provides Handle to RnCryptor 
*/
class Encryption{

    private $_db;
    function __construct(){
        $this->_db = DB::getInstance();
    }

    public function is_already(){
        $file = $_FILES["InputFile"]["tmp_name"];
        $hash = $this->_generate_hash($file);
        $q = $this->_db->get("file_meta", ["checksum", "=", $hash]);
        if($q->count()){
            return $q->first()->id;
        }
        return false;
    }
    public function encrypt($input_private_key){
        // Include EnCrypter here
        include "rncryptor/Encryptor.php";
        // get file variables
        $file =  $_FILES["InputFile"]["tmp_name"]; //"tmp/x.txt"
        $filename = $_FILES["InputFile"]["name"];
        // get extention
        $ext = split('\.', $filename);
        $ext = $ext[count($ext)-1];
        $file_content = file_get_contents($file); //get file contents
        // generate hash for duplication
        $hashdata = $this->_generate_hash($file);
        // prepare cryptor 
        $cryptor = new \RNCryptor\Encryptor();
        // 10 character long random_key which is going to store in encrypted data at some random pos
        $random_key = str_shuffle( substr("~!`#$#$%^&*()+=}|][{/*+';<:,?.>", 0, 10) );
        // concat both keys and encrypt
        $encryption_key = $random_key . $input_private_key;
        // echo $encryption_key;
        $encryptedB64 = $cryptor->encrypt( $file_content, $encryption_key );

        // generate a random position in encrypted data
        $salt_pos = mt_rand(10, strlen($encryptedB64));

        // insert salt at random position 
        $final_data = substr($encryptedB64, 0, $salt_pos) . ".".$random_key. "." . substr($encryptedB64, $salt_pos);

        // store file in encryptfile folder..

        file_put_contents("encryptedfiles/".md5($filename), $final_data);
        // insert information in db
        if( $this->_db->insert( "file_meta", [
            "filename" => $filename,
            "stored_name" => md5($filename),
            "checksum" => $hashdata,
            "date"     => date("Y-m-d H:i:s")
            ] ) ){
                return true;
        }

        return false;
    }

    public function decrypt($input_private_key, $filechecksum){
        //include decrypter
        include "rncryptor/Decryptor.php";
        // init decryptor
        $decryptor = new \RNCryptor\Decryptor();
        // get file name from db
        $filename = $this->_db->get("file_meta", ["checksum", "=", $filechecksum])->first()->stored_name;
        $imagecrypt = file_get_contents("encryptedfiles/$filename");
        preg_match('/\.(.*)\./', $imagecrypt, $random_key);
        // $imagecrypt = preg_replace('/\.(.*)\./', "", $imagecrypt );
        // dd($imagecrypt);
        $encryption_key = $random_key[1] . $input_private_key;
        // echo $encryption_key;
        $imagebin = $decryptor->decrypt($imagecrypt, $encryption_key);
        file_put_contents("tmp/x1.jpg", $imagebin);
        $im = imagecreatefromstring( $imagebin );
        if ($im) {
            header('Content-type: image/jpeg');
            imagejpeg($im);
            imagedestroy($im);
            exit();
        }
    }

    private function _generate_hash($filename){
        $fh = fopen($filename, "r");
        $readsize = 1024 * 20; //20kb
        fseek($fh, -$readsize, SEEK_END); //last 20kb
        $hashdata = fread($fh, $readsize );
        $checksum = md5($hashdata);
        return $checksum;
    }
}   