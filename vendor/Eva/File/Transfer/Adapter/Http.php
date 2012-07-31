<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_File
 */

namespace Eva\File\Transfer\Adapter;

/**
 * File transfer adapter class for the HTTP protocol
 *
 * @category  Zend
 * @package   Zend_File_Transfer
 */
class Http extends \Zend\File\Transfer\Adapter\Http
{

    /**
     * Receive the file from the client (Upload)
     *
     * @param  string|array $files (Optional) Files to receive
     * @return boolean
     */
    public function receive($files = null)
    {
        if (!$this->isValid($files)) {
            return false;
        }

        $check = $this->getFiles($files);
        foreach ($check as $file => $content) {

            //Eva: if any php upload error found, will not upload anymore
            if($content['error'] > 0) {
                continue;
            }

            if (!$content['received']) {
                $directory   = '';
                $destination = $this->getDestination($file);
                if ($destination !== null) {
                    $directory = $destination . DIRECTORY_SEPARATOR;
                }

                //Eva: Save file original name here
                $this->files[$file]['original_name'] = $content['name'];

                $filename = $directory . $content['name'];
                $rename   = $this->getFilter('Rename');
                if ($rename !== null) {
                    //Eva: input file info into filter
                    $tmp = $rename->getNewName($content['tmp_name'], false, $content);
                    if ($tmp != $content['tmp_name']) {
                        $filename = $tmp;
                    }

                    if (dirname($filename) == '.') {
                        $filename = $directory . $filename;
                    }

                    $key = array_search(get_class($rename), $this->files[$file]['filters']);
                    unset($this->files[$file]['filters'][$key]);
                }

                // Should never return false when it's tested by the upload validator
                if (!move_uploaded_file($content['tmp_name'], $filename)) {
                    if ($content['options']['ignoreNoFile']) {
                        $this->files[$file]['received'] = true;
                        $this->files[$file]['filtered'] = true;
                        continue;
                    }

                    $this->files[$file]['received'] = false;
                    return false;
                }

                if ($rename !== null) {
                    $this->files[$file]['destination'] = dirname($filename);
                    $this->files[$file]['name']        = basename($filename);
                }

                $this->files[$file]['tmp_name'] = $filename;
                $this->files[$file]['received'] = true;
            }

            if (!$content['filtered']) {
                if (!$this->filter($file)) {
                    $this->files[$file]['filtered'] = false;
                    return false;
                }

                $this->files[$file]['filtered'] = true;
            }
        }

        return true;
    }

    /**
     * Checks if the files are valid
     *
     * @param  string|array $files (Optional) Files to check
     * @return boolean True if all checks are valid
     */
    public function isValid($files = null)
    {
        // Workaround for WebServer not conforming HTTP and omitting CONTENT_LENGTH
        $content = 0;
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $content = $_SERVER['CONTENT_LENGTH'];
        } elseif (!empty($_POST)) {
            $content = serialize($_POST);
        }

        // Workaround for a PHP error returning empty $_FILES when form data exceeds php settings
        if (empty($this->files) && ($content > 0)) {
            if (is_array($files)) {
                $files = current($files);
            }

            $temp = array($files => array(
                'name'  => $files,
                'error' => 1));
            $validator = $this->validators['Zend\Validator\File\Upload'];
            $validator->setTranslator($this->getTranslator())
                      ->setFiles($temp)
                      ->isValid($files, null);
            $this->messages += $validator->getMessages();
            return false;
        }


        $check = $this->getFiles($files, false, true);
        if (empty($check)) {
            return false;
        }

        $translator      = $this->getTranslator();
        $this->messages = array();
        $break           = false;
        foreach ($check as $key => $content) {
            if (array_key_exists('validators', $content) &&
                in_array('Zend\Validator\File\Count', $content['validators'])) {
                $validator = $this->validators['Zend\Validator\File\Count'];
                $count     = $content;
                if (empty($content['tmp_name'])) {
                    continue;
                }

                if (array_key_exists('destination', $content)) {
                    $checkit = $content['destination'];
                } else {
                    $checkit = dirname($content['tmp_name']);
                }

                $checkit .= DIRECTORY_SEPARATOR . $content['name'];
                    $validator->addFile($checkit);
            }
        }

        if (isset($count)) {
            if (!$validator->isValid($count['tmp_name'], $count)) {
                $this->messages += $validator->getMessages();
            }
        }

        foreach ($check as $key => $content) {
            $fileerrors  = array();
            if (array_key_exists('validators', $content) && $content['validated']) {
                continue;
            }

            if (array_key_exists('validators', $content)) {
                foreach ($content['validators'] as $class) {
                    $validator = $this->validators[$class];
                    if (method_exists($validator, 'setTranslator')) {
                        $validator->setTranslator($translator);
                    }

                    if (($class === 'Zend\Validator\File\Upload') and (empty($content['tmp_name']))) {
                        $tocheck = $key;
                    } else {
                        $tocheck = $content['tmp_name'];
                    }

                    if (!$validator->isValid($tocheck, $content)) {
                        $fileerrors += $validator->getMessages();
                    }

                    if (!empty($content['options']['ignoreNoFile']) and (isset($fileerrors['fileUploadErrorNoFile']))) {
                        unset($fileerrors['fileUploadErrorNoFile']);
                        break;
                    }

                    if (($class === 'Zend\Validator\File\Upload') and (count($fileerrors) > 0)) {
                        break;
                    }

                    if (($this->break[$class]) and (count($fileerrors) > 0)) {
                        $break = true;
                        break;
                    }
                }
            }

            if (count($fileerrors) > 0) {
                $this->files[$key]['validated'] = false;
            } else {
                $this->files[$key]['validated'] = true;
            }

            //EvaEngine : Changed messages by key sort
            if($fileerrors) {
                $this->messages[$key] = $fileerrors;
            }

            if ($break) {
                break;
            }
        }

        if (count($this->messages) > 0) {
            return false;
        }

        return true;
    }


    /**
     * Has a file been uploaded ?
     *
     * @param  array|string|null $file
     * @return boolean
     */
    public function isUploaded($files = null)
    {
        $files = $this->getFiles($files, false, true);
        if (empty($files)) {
            return false;
        }

        $options = $this->getOptions();
        foreach ($files as $key => $file) {
            if(isset($options[$key]['ignoreNoFile']) && true === $options[$key]['ignoreNoFile']){
                if (!empty($file['name'])) {
                    return true;
                }
            } elseif (empty($file['name'])) {
                return false;
            }
        }

        return true;
    }
}
