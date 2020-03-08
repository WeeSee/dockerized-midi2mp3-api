<?php 

class Midi2Mp3 
{
    const TMP_DIR = '/tmp/converter-data';
    private $id;
    private $dir;
    private $inputFile;
    private $logFile;

	
    //-----------------------------------------
    // INFO
    //-----------------------------------------
	
    public function info() 
    {
		return [
			'apiName' => 'midi2mp3',
			'version' => array(
				'api' => '1.0',
			),
			'description' => 'midi to mp3 audio files cnverter',
        ];		
	}
	
    //-----------------------------------------
    // Converter
    //-----------------------------------------

    public function convert($midiData) 
    {
        $success = true;
        $message = '';

        try {
            $this->initPath();
            file_put_contents($this->inputFile,$midiData);

            // converter execution
            // $cmd  = "lilypond -o $this->dir $this->inputFile >> $this->logFile 2>&1";
            $cmd  = "cp ".
                $this->inputFile." ".
                $this->dir."/output.mp3 ".
                ">> ".$this->logFile." 2>&1";

            $this->log("cmd = [$cmd]");
            exec($cmd,$op,$retVal);
            if ($retVal!=0) {
                throw new Exception("Error while executing conversion");
            }

        } catch (\Throwable $e) {
            $success = false;
            $message = $e->getMessage();
        } 
        $result = $this->getConvertResponse($success,$message);

        //$this->deleteSessionData();
        return $result;
    }

    private function initPath() {
        $this->id = uniqid();
        $this->dir = self::TMP_DIR . '/' . $this->id;
        $this->inputFile = $this->dir . '/' . $this->id . ".mid";
        $this->logFile = $this->dir . '/' . $this->id . ".log";
        $this->deleteSessionData();
        mkdir($this->dir,0777, true);
        $this->log("start conversion");
    }

    private function getConvertResponse($success, $message) {
        return array(
            'statusCode' => $success ? 'OK' : 'ERROR',
            'message' => $message,
            'base64Mp3Data' => $this->getResultFile('mp3'),
            'logs' => $this->getLogData()
        );
    }

    private function getResultFile($ext) {
        $result = '';
        $file = $this->dir."/output.$ext";
        if (is_file($file)) {
            $result = base64_encode(file_get_contents($file));
        }
        return $result;
    }

    private function getLogData() {
        $log = array();
        if (is_file($this->logFile)) {
            $log[] = array(
                'title' => 'Midi->Mp3 conversion',
                'content' => file_get_contents($this->logFile)
            );
        }
        return $log;
    }

    private function deleteSessionData() {
        //$cmd = "rm -rf " . $this->dir;
        $cmd = "rm -rf " . self::TMP_DIR ."/*";
        exec($cmd,$op,$retVal);
    }

    protected function log($message)
    {
        file_put_contents(
            $this->logFile,date("Ymd-His").": ".$message."\n",
            FILE_APPEND
        );
    }

}