<?php
    
namespace Contacts\Import;

use Contacts\Import\AbstractAdapter;
use Zend\Json\Json;
use Zend\Http\Client;

class Msn extends AbstractAdapter
{
    protected $requestUrl = "https://apis.live.net/V5.0/me/contacts?access_token=";

    protected function getContactsFromResponse()
    {
        if (!$this->response) {
            return false;
        }
        
        $data = $this->getBody();
      
        if (!$data) {
            return false;
        }

        $data = Json::decode($data,1);

        $users = $data['data'];
        
        if (!$users) {
            return false;
        }
        
        foreach ($users as $key=>$user) {  
            if (!isset($user['email_hashes']) || count($user['email_hashes']) == 0) {
                continue;
            }
            
            $contacts[] = array(
                'name'  => isset($user['name']) ? $user['name'] : null,
                'email' => $user['email_hashes'][0],
            );
        }  
        
        return $contacts; 
    }

    protected function getBody()
    {
        $body = (string) $this->response->getContent();

        if (!$body) {
            return '';
        }

        $transferEncoding = $this->response->getHeaders()->get('Transfer-Encoding');

        if (!empty($transferEncoding)) {
            if (strtolower($transferEncoding->getFieldValue()) == 'chunked') {
                $body = $this->decodeChunkedBody($body);
            }
        }

        $contentEncoding = $this->response->getHeaders()->get('Content-Encoding');

        if (!empty($contentEncoding)) {
            $contentEncoding = $contentEncoding->getFieldValue();
            if ($contentEncoding =='gzip') {
                $body = $this->decodeGzip($body);
            } elseif ($contentEncoding == 'deflate') {
                $body = $this->decodeDeflate($body);
            }
        } 
    
        return $body;
    }

    /**
     * Decode a "chunked" transfer-encoded body and return the decoded text
     *
     * @param  string $body
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function decodeChunkedBody($body)
    {
        $decBody = '';

        while (trim($body)) {
            if (! preg_match("/^([\da-fA-F]+)[^\r\n]*\r\n/sm", $body, $m)) {
                throw new Exception\RuntimeException(
                    "Error parsing body - doesn't seem to be a chunked message"
                );
            }

            $length   = hexdec(trim($m[1]));
            $cut      = strlen($m[0]);
            $decBody .= substr($body, $cut, $length);
            $body     = substr($body, $cut + $length + 2);
        }

        return $decBody;
    }

    /**
     * Decode a gzip encoded message (when Content-encoding = gzip)
     *
     * Currently requires PHP with zlib support
     *
     * @param  string $body
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function decodeGzip($body)
    {
        if (!function_exists('gzinflate')) {
            throw new Exception\RuntimeException(
                'zlib extension is required in order to decode "gzip" encoding'
            );
        }

        return gzinflate(substr($body, 10));
    }

    /**
     * Decode a zlib deflated message (when Content-encoding = deflate)
     *
     * Currently requires PHP with zlib support
     *
     * @param  string $body
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function decodeDeflate($body)
    {
        if (!function_exists('gzuncompress')) {
            throw new Exception\RuntimeException(
                'zlib extension is required in order to decode "deflate" encoding'
            );
        }

        return gzinflate($body);
    }
}
