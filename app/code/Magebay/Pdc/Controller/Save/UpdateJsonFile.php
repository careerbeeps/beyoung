<?php
namespace Magebay\Pdc\Controller\Save;
use Magento\Framework\App\Filesystem\DirectoryList;

class UpdateJsonFile extends \Magento\Framework\App\Action\Action {

    public function execute() {
		$fileSystem = $this->_objectManager->get('Magento\Framework\Filesystem');
		$mediaPath = $fileSystem->getDirectoryWrite(DirectoryList::MEDIA)->getAbsolutePath();
		$postData = $this->getRequest()->getPost();
        $response = array(
            'status' => 'error',
            'message' => 'Unable to save json file!'
        );
		$jsonContent = "";
		$currentFile = '';
        $_jsonInfo = array();
		$newPrevewSvg = array();
        if(!isset($postData['json_content'])) {
			//Try get data another way, fix mod_security problem
			$postString = file_get_contents("php://input");
			if($postString != "") {
                $_jsonInfo = json_decode($postString, true);
                if(isset($_jsonInfo['chem_gio']) && $_jsonInfo['chem_gio']) {
					$tempPreviewSvg = $_jsonInfo['chem_gio'];
					if(count($tempPreviewSvg))
					{
						foreach($tempPreviewSvg as $tempSvg)
						{
							$newPrevewSvg[$tempSvg['svg_side_id']] = $tempSvg['image_result'];
						}
					}
                }
				//current file
				$currentFile = isset($_jsonInfo['file_json']) ? $_jsonInfo['file_json'] : '';
			} else {
				$this->getResponse()->setBody(json_encode($response));
				return;
			}
        } else {
			//$jsonContent = $postData['json_content'];
		}
		if(count($newPrevewSvg) && $currentFile != '')
		{
			//reold file data 
			$myfile = fopen($mediaPath.'pdp/json/'.$currentFile, "r") or die("Unable to open file!");
			if($myfile)
			{
				$jsonFile = fread($myfile,filesize($mediaPath.'pdp/json/'.$currentFile));
			}
			fclose($myfile);
			$arJsons = json_decode($jsonFile);
			$arJsons = (array)$arJsons;
			foreach($arJsons as $key => $arJson)
			{
				$arJson = (array)$arJson;
				$arJson['new_prevew_svg'] = $newPrevewSvg[$key];
				$arJsons[$key] = $arJson;
			}
			$newJsonContent = json_encode($arJsons);
			try
			{
				$jsonBaseDir = $mediaPath.'pdp/json/';
				//clear file before update
				file_put_contents($jsonBaseDir . $currentFile, "");
				$result = file_put_contents($jsonBaseDir . $currentFile, $newJsonContent);
				if ($result) {
					$response = array(
						'status' => 'success',
						'message' => 'You have update file success'
					);
				}
			}
			catch(\Exception $e) {
				$response['message'] = "Can not save json file to database!";
			}
		}
        $this->getResponse()->setBody(json_encode($response));
    }
}
