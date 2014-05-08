<?php
class ZExtraLib_UploadFtpImgServer {
    protected $_hostFtp;
    protected $_userFtp;
    protected $_passwordFtp;
    protected $_file;
    protected $_conecFtp;
    function __construct() 
    {
        $configFtp = ZExtraLib_Server::getFile(0)->upload;
        $this->_hostFtp = $configFtp['host'];
        $this->_userFtp = $configFtp['user'];
        $this->_passwordFtp = $configFtp['password'];
        $this->_file = $configFtp['fileBase'];
        $this->_conecFtp = ftp_connect($this->_hostFtp); 
    }
    function connect(){
        $resultado = ftp_login($this->_conecFtp, $this->_userFtp, $this->_passwordFtp);
        $result = ! ((! $this->_conecFtp) || (! $resultado));
        ftp_pasv($this->_conecFtp, true);
        return $result;
    }
    function upFile($remoteFile, $localFile)
    {
        ftp_put($this->_conecFtp, $remoteFile,$localFile, FTP_BINARY);
        ftp_chmod($this->_conecFtp, 0777, $remoteFile);
    }
    
    function upAsincFile($remoteFile, $localFile)
    {
        ftp_nb_put($this->_conecFtp, $remoteFile, $localFile, FTP_BINARY);
        ftp_chmod($this->_conecFtp, 0777, $remoteFile);
    }
    
    function newDirectory($ruta, $nomdir,$permisos=null)
    {
        $nroPermiso=empty($permisos)?0777:$permisos;
        ftp_chdir($this->_conecFtp, $ruta);
        if (!@ftp_chdir($this->_conecFtp, $nomdir)) {
                @ftp_mkdir($this->_conecFtp, $nomdir);
                @ftp_chmod($this->_conecFtp,$nroPermiso, $nomdir);
        }
    }
    
    function existe($remote_file){
       // @ftp_chmod($this->_conecFtp, 0777, $remote_file);
        return (@ftp_chdir($this->_conecFtp, $remote_file));
    }
    
    function asignarPermisos($ruta,$permisos){
        @ftp_chmod($this->_conecFtp, $permisos, $ruta);
    }
    
    function delete ($remote_file)
    {
    	return ftp_delete($this->_conecFtp, $remote_file);
    }
    function closeConect()
    {
        ftp_close($this->_conecFtp);
    }
    function moveFile($rutaOrigin,$rutaEnd)
    {
        ftp_rename($this->_conecFtp,$rutaOrigin,$rutaEnd);
        //ftp_chmod($this->_conecFtp, 0777,$rutaEnd);
    }
    function copyFile($fileOrigen,$fileDestino,$rutaDestino)
    {
        ftp_chdir($this->_conecFtp,$rutadestino);
        // realizamos la copia
        ftp_put($this->_conecFtp,$filedestino,$fileorigen,FTP_BINARY);
    }
    function existFile($remote_file)
    {
        $res = ftp_size($this->_conecFtp,$remote_file);
        if ($res != -1) {
            return true;
        } else {
            return false;
        }

    }
    
}